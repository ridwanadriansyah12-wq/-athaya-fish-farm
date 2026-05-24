<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\KatalogIkan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

class PesananController extends Controller
{
    /**
     * Show cart
     */
    public function cart(): View
    {
        $cart = session()->get('cart', []);
        $total = 0;
        $items = [];

        foreach ($cart as $id => $qty) {
            $katalog = KatalogIkan::find($id);
            if ($katalog) {
                $items[] = [
                    'katalog' => $katalog,
                    'qty' => $qty,
                    'subtotal' => $katalog->harga_satuan * $qty
                ];
                $total += $katalog->harga_satuan * $qty;
            }
        }

        $user = auth()->user();

        return view('pesanan.cart', compact('items', 'total', 'cart', 'user'));
    }

    /**
     * Add to cart
     */
    public function addToCart(KatalogIkan $katalog, Request $request): RedirectResponse
    {
        $qty = $request->input('qty', 1);
        $cart = session()->get('cart', []);
        
        if (isset($cart[$katalog->id])) {
            $cart[$katalog->id] += $qty;
        } else {
            $cart[$katalog->id] = $qty;
        }

        session()->put('cart', $cart);

        if ($request->input('action') === 'buy_now') {
            return redirect()->route('cart');
        }

        return back()->with('success', 'Produk ditambahkan ke keranjang');
    }

    public function removeFromCart(int $id): RedirectResponse
    {
        $cart = session()->get('cart', []);
        unset($cart[$id]);
        session()->put('cart', $cart);
        return back()->with('success', 'Produk dihapus dari keranjang');
    }

    /**
     * Update cart quantity
     */
    public function updateCart(int $id, Request $request): RedirectResponse
    {
        $action = $request->input('action');
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            if ($action === 'increase') {
                $cart[$id]++;
            } elseif ($action === 'decrease') {
                $cart[$id]--;
                if ($cart[$id] <= 0) {
                    unset($cart[$id]);
                }
            }
            session()->put('cart', $cart);
        }
        
        return back()->with('success', 'Keranjang diperbarui');
    }

    /**
     * Store order — baca langsung dari session cart, tanpa perlu hidden input items
     */
    public function store(Request $request): RedirectResponse
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Keranjang kosong. Tambahkan produk terlebih dahulu.');
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'alamat_pengiriman' => 'required|string|max:500',
            'catatan_pesanan'   => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->route('cart')->withErrors($validator)->withInput();
        }

        $pesanan = Pesanan::create([
            'nomor_pesanan'      => 'ORD-' . strtoupper(Str::random(10)),
            'customer_id'        => auth()->id(),
            'status'             => 'pending',
            'total_harga'        => 0,
            'total_jasa_budidaya'=> 0,
            'total_pembayaran'   => 0,
            'catatan_pesanan'    => $request->catatan_pesanan,
            'alamat_pengiriman'  => $request->alamat_pengiriman,
        ]);

        $totalHarga = 0;

        // Iterasi dari session cart (bukan dari form input)
        foreach ($cart as $id => $qty) {
            $katalog = KatalogIkan::find($id);
            if ($katalog) {
                $subtotal = $katalog->harga_satuan * (int) $qty;

                DetailPesanan::create([
                    'pesanan_id'              => $pesanan->id,
                    'katalog_ikan_id'         => $katalog->id,
                    'kuantitas'               => (int) $qty,
                    'harga_satuan'            => $katalog->harga_satuan,
                    'subtotal'                => $subtotal,
                    'dengan_layanan_budidaya' => false,
                    'durasi_budidaya_hari'    => null,
                    'biaya_budidaya'          => 0,
                ]);

                $totalHarga += $subtotal;
            }
        }

        $pesanan->update([
            'total_harga'         => $totalHarga,
            'total_jasa_budidaya' => 0,
            'total_pembayaran'    => $totalHarga,
        ]);

        session()->forget('cart');

        return redirect()->route('pesanan.show', $pesanan->id)
            ->with('success', '✅ Pesanan ' . $pesanan->nomor_pesanan . ' berhasil dibuat! Silakan lanjutkan pembayaran.')
            ->with('auto_pay', true);
    }

    /**
     * Show order detail
     */
    public function show(Pesanan $pesanan): View
    {
        // Pastikan hanya customer pemilik pesanan atau admin/pemilik yang bisa akses
        if ($pesanan->customer_id !== auth()->id() && !in_array(auth()->user()->role, ['admin', 'pemilik'])) {
            abort(403);
        }

        // Load semua relasi yang dibutuhkan view sekaligus
        $pesanan->load('customer', 'detailPesanan.katalogIkan', 'pembayaran', 'pengiriman');

        $detailPesanan = $pesanan->detailPesanan;

        return view('pesanan.show', compact('pesanan', 'detailPesanan'));
    }

    /**
     * List orders for customer
     */
    public function list(): View
    {
        $pesanan = Pesanan::where('customer_id', auth()->id())->latest()->paginate(10);
        return view('pesanan.list', compact('pesanan'));
    }

    /**
     * Show all orders for admin
     */
    public function allOrders(): View
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya admin yang dapat mengakses.');
        }
        $pesanan = Pesanan::with('detailPesanan', 'customer')->latest()->paginate(15);
        return view('pesanan.all-orders', compact('pesanan'));
    }

    /**
     * Konfirmasi pesanan (admin only)
     */
    public function konfirmasi(Pesanan $pesanan, Request $request): RedirectResponse
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya admin yang dapat mengakses.');
        }

        $aksi = $request->input('aksi');

        if ($aksi === 'terima') {
            $pesanan->update([
                'status' => 'dikonfirmasi',
                'diterima_pemilik_at' => now(),
                'dikonfirmasi_at' => now()
            ]);
            return back()->with('info', '✅ Pesanan dikonfirmasi dan pelanggan telah dinotifikasi.');
        } elseif ($aksi === 'tolak') {
            $pesanan->update([
                'status' => 'ditolak',
                'diterima_pemilik_at' => now()
            ]);
            return back()->with('warning', '❌ Pesanan telah ditolak.');
        }

        return back();
    }
}
