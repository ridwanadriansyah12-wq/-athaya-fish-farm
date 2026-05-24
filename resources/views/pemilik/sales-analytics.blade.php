@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="h4 font-weight-bold">Analisis Penjualan</h2>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('pemilik.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('pemilik.sales-analytics') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Dari Tanggal</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary">
                <div class="card-body">
                    <div class="text-primary font-weight-bold text-uppercase mb-1">Total Penjualan</div>
                    <div class="h3 mb-0">{{ $sales->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success">
                <div class="card-body">
                    <div class="text-success font-weight-bold text-uppercase mb-1">Total Pendapatan</div>
                    <div class="h3 mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info">
                <div class="card-body">
                    <div class="text-info font-weight-bold text-uppercase mb-1">Rata-rata Penjualan</div>
                    <div class="h3 mb-0">Rp {{ number_format($averageSale, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning">
                <div class="card-body">
                    <div class="text-warning font-weight-bold text-uppercase mb-1">Penjualan Tertinggi</div>
                    <div class="h3 mb-0">Rp {{ number_format($maxSale, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales by Product -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Penjualan per Produk</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4">Produk</th>
                                <th>Kuantitas Terjual</th>
                                <th class="text-right">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($salesByProduct as $product)
                            <tr>
                                <td class="px-4"><span class="font-weight-bold">{{ $product['product'] }}</span></td>
                                <td>{{ $product['quantity'] }} unit</td>
                                <td class="text-right font-weight-bold">Rp {{ number_format($product['revenue'], 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">Tidak ada data penjualan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Sales -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Riwayat Penjualan</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4">ID</th>
                                <th>Pelanggan</th>
                                <th>Tanggal</th>
                                <th>Total Pendapatan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                            <tr>
                                <td class="px-4">#{{ $sale->id }}</td>
                                <td><span class="font-weight-bold">{{ $sale->user->name ?? 'Anonim' }}</span></td>
                                <td><small class="text-muted">{{ $sale->created_at->format('d M Y H:i') }}</small></td>
                                <td><span class="font-weight-bold">Rp {{ number_format($sale->Total_Pendapatan, 0, ',', '.') }}</span></td>
                                <td>
                                    @if($sale->Status == 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($sale->Status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($sale->Status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Tidak ada data penjualan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="row mt-4">
        <div class="col-md-12">
            {{ $sales->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection
