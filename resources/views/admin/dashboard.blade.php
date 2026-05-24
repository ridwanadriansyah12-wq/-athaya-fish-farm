@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="container-fluid px-4 py-4">

        {{-- Page Header --}}
        <div class="page-header d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-1"><i class="bi bi-shield-check me-2"></i>Dashboard Admin</h4>
                <p class="mb-0 text-muted">Management System — Athaya Fish Farm</p>
            </div>
            <span class="badge rounded-pill px-3 py-2"
                style="background:var(--primary-pale);color:var(--primary-dark);font-size:.8rem">
                <i class="bi bi-circle-fill me-1" style="font-size:.5rem;color:#10B981"></i> Sistem Aktif
            </span>
        </div>

        {{-- Stat Cards --}}
        <div class="row g-3 mb-4">
            @php
                $cards = [
                    ['label' => 'Total Users', 'val' => $statistik['total_users'] ?? 0, 'icon' => 'bi-people-fill', 'grad' => 'linear-gradient(135deg,#4A90A4,#2C5F72)'],
                    ['label' => 'Customers', 'val' => $statistik['total_customers'] ?? 0, 'icon' => 'bi-person-heart', 'grad' => 'linear-gradient(135deg,#87CEEB,#4A90A4)'],
                    ['label' => 'Total Pesanan', 'val' => $statistik['total_pesanan'] ?? 0, 'icon' => 'bi-bag-check-fill', 'grad' => 'linear-gradient(135deg,#5BA3C1,#2C5F72)'],
                    ['label' => 'Pesanan Selesai', 'val' => $statistik['pesanan_selesai'] ?? 0, 'icon' => 'bi-check-circle-fill', 'grad' => 'linear-gradient(135deg,#2C5F72,#1A3A47)'],
                    ['label' => 'Pending', 'val' => $statistik['pesanan_pending'] ?? 0, 'icon' => 'bi-hourglass-split', 'grad' => 'linear-gradient(135deg,#F59E0B,#D97706)'],
                    ['label' => 'Total Produk', 'val' => $statistik['total_produk'] ?? 0, 'icon' => 'bi-box-seam-fill', 'grad' => 'linear-gradient(135deg,#4A90A4,#87CEEB)'],
                ];
            @endphp
            @foreach($cards as $card)
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card stat-card h-100" style="background: {{ $card['grad'] }}">
                        <div class="card-body p-3">
                            <div class="icon-wrap mb-2" style="width:38px;height:38px;font-size:1.1rem">
                                <i class="bi {{ $card['icon'] }}"></i>
                            </div>
                            <div class="stat-label" style="font-size:.7rem">{{ $card['label'] }}</div>
                            <div class="stat-value" style="font-size:1.6rem">{{ $card['val'] }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Quick Access --}}
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-grid-3x3-gap-fill me-2"></i>Panel Kontrol Cepat
            </div>
            <div class="card-body p-3">
                @php
                    $menus = [
                        [
                            'href' => route('admin.menu.index'),
                            'icon' => 'bi-box-seam-fill',
                            'label' => 'Kelola Produk',
                            'desc' => 'Manajemen katalog ikan',
                            'color' => '#4A90A4',
                            'bg' => '#EBF5F8',
                            'badge' => null,
                        ],
                        [
                            'href' => route('pesanan.all-orders'),
                            'icon' => 'bi-bag-check-fill',
                            'label' => 'Kelola Pesanan',
                            'desc' => 'Manajemen order customer',
                            'color' => '#D97706',
                            'bg' => '#FEF3C7',
                            'badge' => ($statistik['pesanan_pending'] ?? 0) > 0 ? ($statistik['pesanan_pending']) . ' pending' : null,
                        ],
                        [
                            'href' => route('admin.user.index'),
                            'icon' => 'bi-people-fill',
                            'label' => 'Kelola User',
                            'desc' => 'Manajemen pengguna',
                            'color' => '#2C5F72',
                            'bg' => '#E0F0F5',
                            'badge' => null,
                        ],
                        [
                            'href' => route('admin.budidaya.index'),
                            'icon' => 'bi-droplet-half',
                            'label' => 'Kelola Budidaya',
                            'desc' => 'Penawaran dari customer',
                            'color' => '#059669',
                            'bg' => '#D1FAE5',
                            'badge' => null,
                        ],
                        [
                            'href' => route('admin.report.sales'),
                            'icon' => 'bi-graph-up-arrow',
                            'label' => 'Laporan Penjualan',
                            'desc' => 'Analisis & statistik',
                            'color' => '#7C3AED',
                            'bg' => '#EDE9FE',
                            'badge' => null,
                        ],
                    ];
                @endphp
                <div class="row g-3">
                    @foreach($menus as $m)
                        <div class="col-12 col-sm-6 col-md-4 col-lg-auto" style="flex:1;min-width:160px">
                            <a href="{{ $m['href'] }}" class="text-decoration-none d-block h-100">
                                <div class="quick-card h-100 p-3 rounded-3 position-relative overflow-hidden"
                                    style="background:{{ $m['bg'] }};border:1.5px solid {{ $m['color'] }}22;transition:all .2s">
                                    {{-- Badge --}}
                                    @if($m['badge'])
                                        <span class="position-absolute top-0 end-0 m-2 badge rounded-pill"
                                            style="background:#D97706;color:#fff;font-size:.68rem">
                                            {{ $m['badge'] }}
                                        </span>
                                    @endif
                                    {{-- Icon --}}
                                    <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-3"
                                        style="width:52px;height:52px;background:{{ $m['color'] }}20">
                                        <i class="bi {{ $m['icon'] }}" style="font-size:1.5rem;color:{{ $m['color'] }}"></i>
                                    </div>
                                    {{-- Text --}}
                                    <div class="fw-bold mb-1" style="font-size:.9rem;color:{{ $m['color'] }}">{{ $m['label'] }}
                                    </div>
                                    <div class="text-muted" style="font-size:.78rem;line-height:1.3">{{ $m['desc'] }}</div>
                                    {{-- Arrow --}}
                                    <div class="mt-2">
                                        <i class="bi bi-arrow-right-circle"
                                            style="font-size:.85rem;color:{{ $m['color'] }}88"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Recent Pending Orders --}}
        @if($pesanan && $pesanan->count() > 0)
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span>
                        <i class="bi bi-hourglass-split me-2 text-warning"></i>
                        Pesanan Pending Terbaru
                        <span class="badge bg-warning text-dark ms-1">{{ $pesanan->where('status', 'pending')->count() }}</span>
                    </span>
                    <a href="{{ route('pesanan.all-orders') }}" class="btn btn-outline-primary btn-sm">
                        Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">No. Pesanan</th>
                                <th>Pelanggan</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th class="text-center">Status</th>
                                <th class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesanan->where('status', 'pending')->take(5) as $order)
                                <tr>
                                    <td class="ps-4">
                                        <span class="fw-bold"
                                            style="color:var(--primary)">#{{ $order->nomor_pesanan ?? $order->id }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                                style="width:34px;height:34px;background:var(--primary-pale);color:var(--primary-dark);font-size:.78rem;flex-shrink:0">
                                                {{ strtoupper(substr(optional($order->customer)->name ?? 'A', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold" style="font-size:.875rem">
                                                    {{ optional($order->customer)->name ?? 'Dihapus' }}</div>
                                                <small class="text-muted">{{ optional($order->customer)->email ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><small class="text-muted">{{ $order->created_at?->format('d M Y') ?? '-' }}</small></td>
                                    <td class="fw-bold">Rp
                                        {{ number_format($order->detailPesanan?->sum('subtotal') ?? $order->total_harga ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center"><span class="badge bg-warning text-dark">Pending</span></td>
                                    <td class="text-center pe-4">
                                        <a href="{{ route('admin.order.show', $order->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i>Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                                        Tidak ada pesanan pending
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>

    <style>
        .quick-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .1);
        }
    </style>
@endsection