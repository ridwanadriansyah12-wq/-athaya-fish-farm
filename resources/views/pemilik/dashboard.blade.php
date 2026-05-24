@extends('layouts.app')

@section('title', 'Dashboard Pemilik')

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- Page Header --}}
    <div class="page-header d-flex align-items-center justify-content-between">
        <div>
            <h4><i class="bi bi-speedometer2 me-2"></i>Dashboard Pemilik</h4>
            <p>Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong></p>
        </div>
        <a href="{{ route('pemilik.sales-report') }}" class="btn btn-primary btn-sm d-none d-md-flex">
            <i class="bi bi-bar-chart-line"></i> Lihat Laporan Lengkap
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card stat-card h-100" style="background: linear-gradient(135deg, #4A90A4 0%, #2C5F72 100%);">
                <div class="card-body p-4">
                    <div class="icon-wrap"><i class="bi bi-cash-stack"></i></div>
                    <div class="stat-label">Total Pendapatan</div>
                    <div class="stat-value" style="font-size:1.1rem">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card stat-card h-100" style="background: linear-gradient(135deg, #2C5F72 0%, #1A3A47 100%);">
                <div class="card-body p-4">
                    <div class="icon-wrap"><i class="bi bi-bag-check-fill"></i></div>
                    <div class="stat-label">Total Pesanan</div>
                    <div class="stat-value">{{ $totalOrders ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card stat-card h-100" style="background: linear-gradient(135deg, #87CEEB 0%, #4A90A4 100%);">
                <div class="card-body p-4">
                    <div class="icon-wrap"><i class="bi bi-people-fill"></i></div>
                    <div class="stat-label">Total Pelanggan</div>
                    <div class="stat-value">{{ $totalCustomers ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card stat-card h-100" style="background: linear-gradient(135deg, #5BA3C1 0%, #2C5F72 100%);">
                <div class="card-body p-4">
                    <div class="icon-wrap"><i class="bi bi-fish"></i></div>
                    <div class="stat-label">Total Produk</div>
                    <div class="stat-value">{{ $totalProduk ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        {{-- Status Pesanan --}}
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-pie-chart-fill me-2"></i>Status Pesanan
                </div>
                <div class="card-body">
                    @php
                        $statusItems = [
                            ['label'=>'Pending',      'val'=>$orderStatus['pending'] ?? 0,      'cls'=>'bg-warning',  'icon'=>'bi-hourglass-split'],
                            ['label'=>'Dikonfirmasi', 'val'=>$orderStatus['dikonfirmasi'] ?? 0, 'cls'=>'bg-info',     'icon'=>'bi-check-circle'],
                            ['label'=>'Selesai',      'val'=>$orderStatus['selesai'] ?? 0,      'cls'=>'bg-success',  'icon'=>'bi-check-all'],
                        ];
                    @endphp
                    @foreach($statusItems as $item)
                    <div class="d-flex align-items-center justify-content-between p-3 rounded-3 mb-2"
                         style="background:var(--primary-pale)">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi {{ $item['icon'] }} text-primary"></i>
                            <span class="fw-600" style="font-size:.875rem;font-weight:600">{{ $item['label'] }}</span>
                        </div>
                        <span class="badge {{ $item['cls'] }} rounded-pill px-3">{{ $item['val'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Pendapatan 30 Hari --}}
        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-calendar3 me-2"></i>Pendapatan 30 Hari Terakhir
                </div>
                <div class="card-body d-flex flex-column justify-content-center p-4">
                    <p class="text-muted mb-1" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Total Pendapatan</p>
                    <h2 class="fw-800 mb-3" style="color:#10B981;font-weight:800">
                        Rp {{ number_format($revenueLastMonth ?? 0, 0, ',', '.') }}
                    </h2>
                    <div class="d-flex gap-2">
                        <a href="{{ route('pemilik.sales-report') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-arrow-right-circle"></i> Lihat Laporan Lengkap
                        </a>
                        <a href="{{ route('katalog.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-shop"></i> Katalog
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <span><i class="bi bi-clock-history me-2"></i>Pesanan Terbaru</span>
            <a href="{{ route('pemilik.sales-report') }}" class="btn btn-outline-primary btn-sm">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pelanggan</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders ?? [] as $order)
                    <tr>
                        <td><span class="fw-bold" style="color:var(--primary)">#{{ $order->id }}</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:30px;height:30px;border-radius:50%;background:var(--primary-pale);color:var(--primary-dark);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.75rem">
                                    {{ strtoupper(substr($order->customer->name ?? 'A', 0, 1)) }}
                                </div>
                                {{ $order->customer->name ?? 'Anonim' }}
                            </div>
                        </td>
                        <td><small class="text-muted">{{ $order->created_at->format('d M Y') }}</small></td>
                        <td>
                            @php $st = strtolower($order->status ?? '') @endphp
                            @if($st == 'selesai')
                                <span class="badge bg-success">Selesai</span>
                            @elseif($st == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($st == 'dikonfirmasi')
                                <span class="badge bg-info text-dark">Dikonfirmasi</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($st) }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                            Belum ada pesanan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
