@extends('layouts.app')

@section('title', 'Detail Pesanan - ' . $pesanan->nomor_pesanan)

@section('content')
    <div class="container mt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pesanan.list') }}">Pesanan Saya</a></li>
                <li class="breadcrumb-item active">{{ $pesanan->nomor_pesanan }}</li>
            </ol>
        </nav>

        <div class="row">
            {{-- Kiri: Detail Pesanan --}}
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-receipt text-primary me-2"></i> Pesanan #{{ $pesanan->nomor_pesanan }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>No. Pesanan:</strong> {{ $pesanan->nomor_pesanan }}</p>
                                <p class="mb-1"><strong>Tanggal:</strong>
                                    {{ $pesanan->created_at?->format('d F Y H:i') ?? '-' }}</p>
                                <p class="mb-1"><strong>Customer:</strong> {{ optional($pesanan->customer)->name ?? '-' }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Status:</strong>
                                    @php
                                        $statusColor = match ($pesanan->status) {
                                            'lunas', 'selesai' => 'success',
                                            'pending' => 'warning',
                                            'dikonfirmasi' => 'info',
                                            'ditolak' => 'danger',
                                            default => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }}">
                                        {{ ucfirst(str_replace('_', ' ', $pesanan->status)) }}
                                    </span>
                                </p>
                                <p class="mb-1"><strong>Total Harga:</strong> Rp
                                    {{ number_format($pesanan->total_pembayaran, 0, ',', '.') }}
                                </p>
                                @if($pesanan->catatan_pesanan)
                                    <p class="mb-1"><strong>Catatan:</strong> {{ $pesanan->catatan_pesanan }}</p>
                                @endif
                            </div>
                        </div>

                        {{-- Alamat Pengiriman --}}
                        <div class="p-3 mb-3 rounded border" style="background:#f8f9fa;">
                            <p class="mb-1 small text-muted fw-semibold">
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i>Alamat Pengiriman
                            </p>
                            <p class="mb-0 fw-semibold">
                                {{ $pesanan->alamat_pengiriman ?? '-' }}
                            </p>
                        </div>

                        <!-- Tabel Detail Items -->
                        <h6 class="mb-3 fw-bold">Item Pesanan:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Harga</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($detailPesanan as $detail)
                                        <tr>
                                            <td>{{ optional($detail->katalogIkan)->nama_produk ?? '<em class="text-muted">Produk dihapus</em>' }}
                                            </td>
                                            <td class="text-center">{{ $detail->kuantitas }}</td>
                                            <td class="text-end">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                            <td class="text-end"><strong>Rp
                                                    {{ number_format($detail->subtotal, 0, ',', '.') }}</strong></td>
                                        </tr>
                                        @if($detail->dengan_layanan_budidaya)
                                            <tr class="table-light">
                                                <td colspan="4">
                                                    <small>
                                                        <i class="bi bi-info-circle"></i>
                                                        <strong>Layanan Budidaya:</strong> {{ $detail->durasi_budidaya_hari }} hari
                                                        —
                                                        Rp {{ number_format($detail->biaya_budidaya, 0, ',', '.') }}
                                                    </small>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kanan: Ringkasan Pembayaran --}}
            <div class="col-md-4">
                <div class="card shadow-sm mb-4 sticky-top" style="top: 80px;">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-wallet me-2"></i>Ringkasan Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                            <span class="text-muted">Subtotal Ikan:</span>
                            <strong>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</strong>
                        </div>
                        @if($pesanan->total_jasa_budidaya > 0)
                            <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                <span class="text-muted">Biaya Budidaya:</span>
                                <strong>Rp {{ number_format($pesanan->total_jasa_budidaya, 0, ',', '.') }}</strong>
                            </div>
                        @endif
                        <div class="d-flex justify-content-between mb-4">
                            <h6 class="fw-bold mb-0">Total Bayar:</h6>
                            <h6 class="fw-bold text-success mb-0">Rp
                                {{ number_format($pesanan->total_pembayaran, 0, ',', '.') }}
                            </h6>
                        </div>

                        {{-- Status Pembayaran (jika sudah ada) --}}
                        @if($pesanan->pembayaran && in_array($pesanan->pembayaran->status_pembayaran, ['settlement', 'capture']))
                            <div class="alert alert-success py-2 text-center mb-3">
                                <i class="bi bi-check-circle-fill me-1"></i>
                                <strong>Pembayaran Lunas</strong>
                                @if($pesanan->pembayaran->metode_pembayaran)
                                    <br><small>via
                                        {{ ucwords(str_replace('_', ' ', $pesanan->pembayaran->metode_pembayaran)) }}</small>
                                @endif
                            </div>
                        @endif

                        {{-- Tombol Bayar via Midtrans --}}
                        @if(in_array($pesanan->status, ['dikonfirmasi', 'pembayaran', 'pending']))
                            <button type="button" id="btn-bayar-midtrans" onclick="bayarViaMidtrans()"
                                class="btn btn-success btn-lg w-100 fw-bold">Bayar Sekarang
                            </button>
                            <p class="text-center text-muted mt-2 mb-0" style="font-size: 11px;">
                                <i class="bi bi-shield-check me-1"></i>Transaksi dijamin aman oleh Midtrans
                            </p>
                        @endif

                        @if($pesanan->pembayaran && $pesanan->pembayaran->invoice_path)
                            <a href="{{ asset('storage/' . $pesanan->pembayaran->invoice_path) }}"
                                class="btn btn-outline-secondary btn-sm w-100 mt-2" target="_blank">
                                <i class="bi bi-download"></i> Download Invoice
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Informasi Pengiriman -->
                @if($pesanan->pengiriman)
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold"><i class="bi bi-truck text-primary me-2"></i> Informasi Pengiriman</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-1"><strong>Alamat:</strong> {{ $pesanan->pengiriman->alamat_pengiriman }}</p>
                            <p class="mb-1"><strong>No. Resi:</strong> {{ $pesanan->pengiriman->nomor_resi ?? '-' }}</p>
                            <p class="mb-1"><strong>Kurir:</strong> {{ $pesanan->pengiriman->kurir ?? '-' }}</p>
                            <p class="mb-0"><strong>Status:</strong>
                                {{ ucfirst(str_replace('_', ' ', $pesanan->pengiriman->status)) }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Midtrans Snap JS --}}
    <script src="{{ config('services.midtrans.is_production')
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ config('services.midtrans.client_key') }}">
        </script>

    <script>
        const snapTokenUrl     = "{{ route('pembayaran.snap-token', $pesanan) }}";
        const handleSuccessUrl = "{{ route('pembayaran.handle-success', $pesanan) }}";
        const csrfToken        = "{{ csrf_token() }}";
        const pageUrl          = "{{ route('pesanan.show', $pesanan) }}";

        function bayarViaMidtrans() {
            const btn = document.getElementById('btn-bayar-midtrans');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Memproses...';

            fetch(snapTokenUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
            })
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        alert('Gagal mendapatkan token: ' + data.error);
                        resetBtn(btn);
                        return;
                    }

                    window.snap.pay(data.snap_token, {
                        onSuccess: function (result) {
                            // Langsung update status ke server (tidak tunggu webhook)
                            fetch(handleSuccessUrl, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                },
                            }).finally(() => window.location.reload());
                        },
                        onPending: function (result) {
                            window.location.reload();
                        },
                        onError: function (result) {
                            alert('Pembayaran gagal. Silakan coba lagi.');
                            resetBtn(btn);
                        },
                        onClose: function () {
                            resetBtn(btn);
                        }
                    });
                })
                .catch(err => {
                    console.error(err);
                    alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
                    resetBtn(btn);
                });
        }

        function resetBtn(btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-lock-fill me-1"></i> Bayar via Midtrans';
        }
    </script>

    <style>
        #btn-bayar-midtrans {
            transition: all 0.2s ease;
        }

        #btn-bayar-midtrans:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(25, 135, 84, 0.35);
        }
    </style>
@endsection