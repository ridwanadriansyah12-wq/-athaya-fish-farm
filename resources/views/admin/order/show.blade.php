@extends('layouts.app')

@section('title', 'Detail Pesanan — ' . $order->nomor_pesanan)

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- Header --}}
    <div class="page-header d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4><i class="bi bi-receipt me-2"></i>Detail Pesanan</h4>
            <p class="mb-0">
                <a href="{{ route('pesanan.all-orders') }}" class="text-decoration-none" style="color:var(--primary)">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Kelola Pesanan
                </a>
            </p>
        </div>
        <span class="badge rounded-pill px-3 py-2" style="background:var(--primary-pale);color:var(--primary-dark);font-size:.85rem">
            {{ $order->nomor_pesanan }}
        </span>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4">

        {{-- Kiri: Info Pesanan + Item --}}
        <div class="col-lg-8">

            {{-- Info Pelanggan & Pesanan --}}
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span><i class="bi bi-person-circle me-2"></i>Informasi Pesanan</span>
                    @php
                        $badgeMap = [
                            'pending'      => ['bg'=>'#FFC107','color'=>'#000'],
                            'dikonfirmasi' => ['bg'=>'#17A2B8','color'=>'#fff'],
                            'pembayaran'   => ['bg'=>'#6C757D','color'=>'#fff'],
                            'lunas'        => ['bg'=>'#28A745','color'=>'#fff'],
                            'persiapan'    => ['bg'=>'#E83E8C','color'=>'#fff'],
                            'dikirim'      => ['bg'=>'#007BFF','color'=>'#fff'],
                            'selesai'      => ['bg'=>'#20C997','color'=>'#fff'],
                            'ditolak'      => ['bg'=>'#DC3545','color'=>'#fff'],
                        ];
                        $b = $badgeMap[$order->status] ?? ['bg'=>'#6C757D','color'=>'#fff'];
                    @endphp
                    <span class="badge px-3 py-2" style="background:{{ $b['bg'] }};color:{{ $b['color'] }};font-size:.85rem">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="text-muted small mb-1 d-block">Pelanggan</label>
                            <div class="fw-bold">{{ optional($order->customer)->name ?? '-' }}</div>
                            <div class="text-muted small">{{ optional($order->customer)->email ?? '' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <label class="text-muted small mb-1 d-block">Nomor Pesanan</label>
                            <div class="fw-bold" style="color:var(--primary)">{{ $order->nomor_pesanan }}</div>
                        </div>
                        <div class="col-sm-6">
                            <label class="text-muted small mb-1 d-block">Tanggal Pesanan</label>
                            <div class="fw-bold">{{ $order->created_at?->format('d M Y, H:i') ?? '-' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <label class="text-muted small mb-1 d-block">Catatan</label>
                            <div>{{ $order->catatan_pesanan ?? '<span class="text-muted fst-italic">Tidak ada catatan</span>' }}</div>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small mb-1 d-block">
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i>Alamat Pengiriman
                            </label>
                            <div class="fw-semibold">
                                {{ $order->alamat_pengiriman ?? '<span class="text-muted fst-italic">Belum diisi</span>' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Item Pesanan --}}
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-bag-check me-2"></i>Item Pesanan
                    <span class="badge ms-2" style="background:var(--primary-pale);color:var(--primary-dark)">
                        {{ $order->detailPesanan ? $order->detailPesanan->count() : 0 }} item
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Produk</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga Satuan</th>
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-center pe-4">Budidaya</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($order->detailPesanan as $detail)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            @if(optional($detail->katalogIkan)->gambar)
                                                <img src="{{ asset('storage/' . $detail->katalogIkan->gambar) }}"
                                                     alt="{{ $detail->katalogIkan->nama_produk }}"
                                                     class="rounded" style="width:44px;height:44px;object-fit:cover">
                                            @else
                                                <div class="bg-light border rounded d-flex align-items-center justify-content-center"
                                                     style="width:44px;height:44px;flex-shrink:0">
                                                    <i class="bi bi-image text-secondary"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ optional($detail->katalogIkan)->nama_produk ?? 'Produk dihapus' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">{{ $detail->kuantitas }}</span>
                                    </td>
                                    <td class="text-end">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                    <td class="text-end fw-bold" style="color:var(--primary-dark)">
                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center pe-4">
                                        @if($detail->dengan_layanan_budidaya)
                                            <span class="badge" style="background:#E0F6FF;color:#2C5F72">
                                                <i class="bi bi-droplet-half me-1"></i>{{ $detail->durasi_budidaya_hari }} hari
                                            </span>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                                        Tidak ada item dalam pesanan ini
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Ringkasan Total --}}
                @if($order->detailPesanan && $order->detailPesanan->count() > 0)
                <div class="card-footer bg-light">
                    <div class="d-flex flex-column align-items-end gap-1">
                        <div class="d-flex gap-4">
                            <span class="text-muted">Subtotal Produk:</span>
                            <span class="fw-semibold">Rp {{ number_format($order->total_harga ?? $order->detailPesanan->sum('subtotal'), 0, ',', '.') }}</span>
                        </div>
                        @if(($order->total_jasa_budidaya ?? 0) > 0)
                        <div class="d-flex gap-4">
                            <span class="text-muted">Biaya Budidaya:</span>
                            <span class="fw-semibold">Rp {{ number_format($order->total_jasa_budidaya, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="d-flex gap-4 border-top pt-2 mt-1">
                            <span class="fw-bold fs-6">Total Pembayaran:</span>
                            <span class="fw-bold fs-5" style="color:var(--primary)">
                                Rp {{ number_format($order->total_pembayaran ?? $order->total_harga ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
                @endif
            </div>

        </div>

        {{-- Kanan: Update Status + Hapus --}}
        <div class="col-lg-4">

            {{-- Update Status --}}
            <div class="card mb-3">
                <div class="card-header">
                    <i class="bi bi-arrow-repeat me-2"></i>Update Status Pesanan
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.order.status', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Status Saat Ini</label>
                            <select name="status" class="form-select">
                                @foreach(['pending','dikonfirmasi','pembayaran','lunas','persiapan','dikirim','selesai','ditolak','batal'] as $s)
                                <option value="{{ $s }}" @selected($order->status === $s)>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn w-100" style="background:var(--primary);color:#fff">
                            <i class="bi bi-check-circle me-1"></i>Simpan Status
                        </button>
                    </form>
                </div>
            </div>

            {{-- Konfirmasi Cepat (hanya saat pending) --}}
            @if($order->status === 'pending')
            <div class="card mb-3 border-warning">
                <div class="card-header bg-warning bg-opacity-10">
                    <i class="bi bi-hourglass-split me-2 text-warning"></i>Konfirmasi Cepat
                </div>
                <div class="card-body d-grid gap-2">
                    <form action="{{ route('pesanan.konfirmasi', $order->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="aksi" value="terima">
                        <button type="submit" class="btn btn-success w-100"
                                onclick="return confirm('Konfirmasi pesanan ini?')">
                            <i class="bi bi-check-circle me-1"></i>Terima Pesanan
                        </button>
                    </form>
                    <form action="{{ route('pesanan.konfirmasi', $order->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="aksi" value="tolak">
                        <button type="submit" class="btn btn-outline-danger w-100"
                                onclick="return confirm('Tolak pesanan ini?')">
                            <i class="bi bi-x-circle me-1"></i>Tolak Pesanan
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- Info Timestamps --}}
            <div class="card mb-3">
                <div class="card-header">
                    <i class="bi bi-clock-history me-2"></i>Riwayat Waktu
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-2 small">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Dibuat</span>
                            <span class="fw-semibold">{{ $order->created_at?->format('d M Y H:i') ?? '-' }}</span>
                        </div>
                        @if($order->dikonfirmasi_at)
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Dikonfirmasi</span>
                            <span class="fw-semibold">{{ \Carbon\Carbon::parse($order->dikonfirmasi_at)->format('d M Y H:i') }}</span>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Diperbarui</span>
                            <span class="fw-semibold">{{ $order->updated_at?->format('d M Y H:i') ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Hapus Pesanan --}}
            <div class="card border-danger border-opacity-50">
                <div class="card-body">
                    <p class="text-muted small mb-2">Menghapus pesanan bersifat permanen dan tidak dapat dibatalkan.</p>
                    <form action="{{ route('admin.order.destroy', $order->id) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus pesanan {{ $order->nomor_pesanan }}? Tindakan ini tidak dapat dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-trash me-1"></i>Hapus Pesanan
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
