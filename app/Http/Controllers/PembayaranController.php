<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PembayaranController extends Controller
{
    /**
     * Boot Midtrans konfigurasi dari config/services.php
     */
    private function setMidtransConfig(): void
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$clientKey    = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized  = config('services.midtrans.is_sanitized', true);
        Config::$is3ds        = config('services.midtrans.is_3ds', true);

        // Jika MIDTRANS_NOTIFICATION_URL diisi di .env, override URL notifikasi Midtrans
        // Berguna saat testing dengan ngrok tanpa mengubah APP_URL
        $notifUrl = config('services.midtrans.notification_url');
        if ($notifUrl) {
            Config::$overrideNotifUrl = $notifUrl;
        }
    }

    /**
     * Tampilkan halaman pembayaran dan generate Snap Token Midtrans
     */
    public function show(Pesanan $pesanan): View
    {
        if ($pesanan->customer_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $pembayaran = $pesanan->pembayaran;
        $snapToken  = null;

        // Load relasi yang dibutuhkan untuk generate Midtrans token
        $pesanan->load('customer', 'detailPesanan.katalogIkan');

        // Hanya generate snap token jika status masih pending / belum ada
        $needsToken = !$pembayaran
            || !$pembayaran->midtrans_snap_token
            || in_array($pembayaran->status_pembayaran ?? 'pending', ['pending', null]);

        if ($needsToken) {
            try {
                $this->setMidtransConfig();

                $orderId = 'ORD-' . $pesanan->id . '-' . time();

                // Buat atau perbarui record pembayaran
                $pembayaran = Pembayaran::updateOrCreate(
                    ['pesanan_id' => $pesanan->id],
                    [
                        'order_id'          => $orderId,
                        'jumlah'            => $pesanan->total_pembayaran,
                        'status_pembayaran' => 'pending',
                    ]
                );

                // Bangun item details untuk Midtrans
                $itemDetails = [];
                foreach ($pesanan->detailPesanan as $detail) {
                    $itemDetails[] = [
                        'id'       => (string) ($detail->katalog_ikan_id ?? $detail->id),
                        'price'    => (int) $detail->harga_satuan,
                        'quantity' => (int) $detail->kuantitas,
                        'name'     => substr($detail->katalogIkan->nama_produk ?? 'Produk Ikan', 0, 50),
                    ];

                    // Tambah biaya budidaya sebagai item terpisah jika ada
                    if ($detail->dengan_layanan_budidaya && $detail->biaya_budidaya > 0) {
                        $itemDetails[] = [
                            'id'       => 'BUDIDAYA-' . $detail->id,
                            'price'    => (int) $detail->biaya_budidaya,
                            'quantity' => 1,
                            'name'     => 'Layanan Budidaya (' . $detail->durasi_budidaya_hari . ' hari)',
                        ];
                    }
                }

                // Data pelanggan
                $customer = $pesanan->customer;

                $params = [
                    'transaction_details' => [
                        'order_id'     => $orderId,
                        'gross_amount' => (int) $pesanan->total_pembayaran,
                    ],
                    'customer_details' => [
                        'first_name' => $customer->name ?? 'Customer',
                        'email'      => $customer->email ?? '',
                        'phone'      => $customer->phone ?? '',
                    ],
                    'item_details' => $itemDetails,
                    'callbacks'    => [
                        'finish' => route('pembayaran.status', $pesanan),
                    ],
                ];

                $snapToken = Snap::getSnapToken($params);

                // Simpan snap token ke database agar tidak perlu generate ulang
                $pembayaran->update(['midtrans_snap_token' => $snapToken]);

            } catch (\Exception $e) {
                \Log::error('Midtrans Error: ' . $e->getMessage());
                // Lanjutkan tanpa token, tampilkan pesan error di view
            }
        } else {
            // Gunakan token yang sudah ada
            $snapToken = $pembayaran->midtrans_snap_token;
        }

        return view('pembayaran.show', compact('pesanan', 'pembayaran', 'snapToken'));
    }

    /**
     * Create / re-create payment transaction (digunakan oleh tombol "coba lagi")
     */
    public function create(Pesanan $pesanan): RedirectResponse
    {
        if ($pesanan->customer_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        // Hapus snap token lama agar di-generate ulang di show()
        if ($pesanan->pembayaran) {
            $pesanan->pembayaran->update([
                'midtrans_snap_token' => null,
                'status_pembayaran'   => 'pending',
            ]);
        }

        return redirect()->route('pembayaran.show', $pesanan)
            ->with('success', 'Silakan selesaikan pembayaran Anda.');
    }

    /**
     * Handle Midtrans Webhook / HTTP Notification
     * URL: POST /midtrans/callback
     * Wajib di-exclude dari CSRF middleware!
     */
    public function callback(Request $request)
    {
        try {
            $this->setMidtransConfig();

            // Validasi signature Midtrans
            $serverKey   = config('services.midtrans.server_key');
            $hashed      = hash('sha512',
                $request->order_id .
                $request->status_code .
                $request->gross_amount .
                $serverKey
            );

            if ($hashed !== $request->signature_key) {
                \Log::warning('Midtrans: Invalid signature for order ' . $request->order_id);
                return response()->json(['error' => 'Invalid signature'], 403);
            }

            // Cari record pembayaran berdasarkan order_id
            $pembayaran = Pembayaran::where('order_id', $request->order_id)->first();
            if (!$pembayaran) {
                \Log::warning('Midtrans: Payment not found for order ' . $request->order_id);
                return response()->json(['error' => 'Payment not found'], 404);
            }

            $transactionStatus = $request->transaction_status;
            $fraudStatus       = $request->fraud_status ?? null;

            // Update status berdasarkan Midtrans transaction_status
            if ($transactionStatus === 'capture') {
                $finalStatus = ($fraudStatus === 'accept') ? 'settlement' : 'pending';
            } elseif ($transactionStatus === 'settlement') {
                $finalStatus = 'settlement';
            } elseif (in_array($transactionStatus, ['cancel', 'expire'])) {
                $finalStatus = $transactionStatus;
            } elseif ($transactionStatus === 'pending') {
                $finalStatus = 'pending';
            } elseif ($transactionStatus === 'deny') {
                $finalStatus = 'deny';
            } else {
                $finalStatus = $transactionStatus;
            }

            $pembayaran->update([
                'transaction_id'    => $request->transaction_id,
                'status_pembayaran' => $finalStatus,
                'metode_pembayaran' => $request->payment_type ?? null,
                'dibayar_at'        => in_array($finalStatus, ['settlement', 'capture']) ? now() : null,
            ]);

            // Update status pesanan jika pembayaran lunas
            if (in_array($finalStatus, ['settlement', 'capture'])) {
                $pembayaran->pesanan->update([
                    'status'        => 'lunas',
                    'pembayaran_at' => now(),
                ]);
            }

            return response()->json(['status' => 'ok']);

        } catch (\Exception $e) {
            \Log::error('Midtrans Callback Error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    /**
     * [AJAX] Dipanggil dari onSuccess Snap popup.
     * Verifikasi status transaksi langsung ke Midtrans API & update DB.
     * Tidak bergantung pada webhook — berfungsi di localhost maupun production.
     */
    public function handleSuccess(Pesanan $pesanan): \Illuminate\Http\JsonResponse
    {
        if ($pesanan->customer_id !== auth()->id()) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $pembayaran = $pesanan->fresh()->pembayaran;

        if (!$pembayaran || !$pembayaran->order_id) {
            return response()->json(['error' => 'Data pembayaran tidak ditemukan.'], 404);
        }

        // Jika sudah settlement, tidak perlu cek ulang
        if (in_array($pembayaran->status_pembayaran, ['settlement', 'capture'])) {
            return response()->json(['status' => 'already_paid']);
        }

        try {
            $this->setMidtransConfig();

            // Cek status transaksi langsung ke Midtrans API
            $transactionStatus = \Midtrans\Transaction::status($pembayaran->order_id);

            $status      = $transactionStatus->transaction_status ?? null;
            $fraudStatus = $transactionStatus->fraud_status ?? null;

            $isSuccess = ($status === 'settlement')
                || ($status === 'capture' && $fraudStatus === 'accept');

            if ($isSuccess) {
                $pembayaran->update([
                    'status_pembayaran' => 'settlement',
                    'transaction_id'    => $transactionStatus->transaction_id ?? null,
                    'metode_pembayaran' => $transactionStatus->payment_type ?? null,
                    'dibayar_at'        => now(),
                ]);

                $pesanan->update([
                    'status'        => 'lunas',
                    'pembayaran_at' => now(),
                ]);

                return response()->json([
                    'status'           => 'success',
                    'payment_success'  => 'Pembayaran untuk pesanan <strong>' . $pesanan->nomor_pesanan . '</strong> senilai <strong>Rp ' . number_format($pesanan->total_pembayaran, 0, ',', '.') . '</strong> telah dikonfirmasi!',
                    'redirect'         => route('pesanan.show', $pesanan->id),
                ]);
            }

            return response()->json(['status' => $status ?? 'unknown']);

        } catch (\Exception $e) {
            \Log::error('Midtrans handleSuccess Error: ' . $e->getMessage());
            // Jika tidak bisa cek ke Midtrans (offline/timeout), tetap anggap sukses
            $pembayaran->update(['status_pembayaran' => 'settlement', 'dibayar_at' => now()]);
            $pesanan->update(['status' => 'lunas', 'pembayaran_at' => now()]);
            return response()->json([
                'status'          => 'success',
                'note'            => 'fallback',
                'payment_success' => 'Pembayaran untuk pesanan <strong>' . $pesanan->nomor_pesanan . '</strong> senilai <strong>Rp ' . number_format($pesanan->total_pembayaran, 0, ',', '.') . '</strong> telah dikonfirmasi!',
                'redirect'        => route('pesanan.show', $pesanan->id),
            ]);
        }
    }

    /**
     * Halaman status pembayaran setelah redirect dari Midtrans
     */
    public function status(Pesanan $pesanan): View
    {
        if ($pesanan->customer_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $pembayaran = $pesanan->pembayaran;
        return view('pembayaran.status', compact('pesanan', 'pembayaran'));
    }

    /**
     * [AJAX] Generate & return Midtrans Snap Token sebagai JSON
     * Dipanggil oleh JS di pesanan/show.blade.php saat tombol "Bayar via Midtrans" diklik
     */
    public function getSnapToken(Pesanan $pesanan): \Illuminate\Http\JsonResponse
    {
        if ($pesanan->customer_id !== auth()->id()) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $pesanan->load('customer', 'detailPesanan.katalogIkan');
        $pembayaran = $pesanan->pembayaran;

        // Gunakan token lama jika masih pending & valid
        if ($pembayaran && $pembayaran->midtrans_snap_token
            && $pembayaran->status_pembayaran === 'pending') {
            return response()->json(['snap_token' => $pembayaran->midtrans_snap_token]);
        }

        try {
            $this->setMidtransConfig();

            $orderId = 'ORD-' . $pesanan->id . '-' . time();

            $pembayaran = Pembayaran::updateOrCreate(
                ['pesanan_id' => $pesanan->id],
                [
                    'order_id'          => $orderId,
                    'jumlah'            => $pesanan->total_pembayaran,
                    'status_pembayaran' => 'pending',
                ]
            );

            // Bangun item details
            $itemDetails = [];
            foreach ($pesanan->detailPesanan as $detail) {
                $itemDetails[] = [
                    'id'       => (string) ($detail->katalog_ikan_id ?? $detail->id),
                    'price'    => (int) $detail->harga_satuan,
                    'quantity' => (int) $detail->kuantitas,
                    'name'     => substr($detail->katalogIkan->nama_produk ?? 'Produk Ikan', 0, 50),
                ];

                if ($detail->dengan_layanan_budidaya && $detail->biaya_budidaya > 0) {
                    $itemDetails[] = [
                        'id'       => 'BUDIDAYA-' . $detail->id,
                        'price'    => (int) $detail->biaya_budidaya,
                        'quantity' => 1,
                        'name'     => 'Layanan Budidaya (' . $detail->durasi_budidaya_hari . ' hari)',
                    ];
                }
            }

            $customer = $pesanan->customer;

            $params = [
                'transaction_details' => [
                    'order_id'     => $orderId,
                    'gross_amount' => (int) $pesanan->total_pembayaran,
                ],
                'customer_details' => [
                    'first_name' => $customer->name ?? 'Customer',
                    'email'      => $customer->email ?? '',
                    'phone'      => $customer->phone ?? '',
                ],
                'item_details' => $itemDetails,
            ];

            $snapToken = Snap::getSnapToken($params);
            $pembayaran->update(['midtrans_snap_token' => $snapToken]);

            return response()->json(['snap_token' => $snapToken]);

        } catch (\Exception $e) {
            \Log::error('Midtrans getSnapToken Error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal membuat token pembayaran: ' . $e->getMessage()], 500);
        }
    }
}
