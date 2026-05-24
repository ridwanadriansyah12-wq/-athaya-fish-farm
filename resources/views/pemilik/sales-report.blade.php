@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="h4 fw-bold mb-0">
                <i class="bi bi-bar-chart-line me-2" style="color:#4A90A4"></i>
                Laporan Penjualan
            </h2>
            <small class="text-muted">Ringkasan data penjualan</small>
        </div>
        <div class="col-auto d-flex align-items-center gap-2">
            <a href="{{ route('pemilik.sales-report.print', request()->only(['start_date','end_date'])) }}"
               target="_blank"
               class="btn btn-dark btn-sm"
               id="btn-cetak-laporan-pemilik"
               title="Cetak laporan dalam format print-friendly">
                <i class="bi bi-printer-fill me-1"></i> Cetak Laporan
            </a>
            <a href="{{ route('pemilik.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('pemilik.sales-report') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Dari Tanggal</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                           value="{{ request('start_date') }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                           value="{{ request('end_date') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('pemilik.sales-report') }}" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card text-center">
                <div class="card-body">
                    <p class="text-muted small mb-1">Total Penjualan</p>
                    <h3 class="fw-bold text-primary">{{ $totalSales ?? 0 }}</h3>
                    <small class="text-muted">transaksi</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card text-center">
                <div class="card-body">
                    <p class="text-muted small mb-1">Total Pendapatan</p>
                    <h3 class="fw-bold text-success">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card text-center">
                <div class="card-body">
                    <p class="text-muted small mb-1">Rata-rata Penjualan</p>
                    <h3 class="fw-bold text-info">Rp {{ number_format($averageSale ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card text-center">
                <div class="card-body">
                    <p class="text-muted small mb-1">Penjualan Tertinggi</p>
                    <h3 class="fw-bold text-warning">Rp {{ number_format($maxSale ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Penjualan --}}
    <div class="card">
        <div class="card-header">
            <i class="bi bi-table me-1"></i> Detail Penjualan
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
                        <td>{{ $sale->user->name ?? 'Anonim' }}</td>
                        <td>
                            <small class="text-muted">
                                {{ $sale->Tanggal_Penjualan ? $sale->Tanggal_Penjualan->format('d M Y H:i') : '-' }}
                            </small>
                        </td>
                        <td>
                            <span class="fw-bold">Rp {{ number_format($sale->Total_Pendapatan, 0, ',', '.') }}</span>
                        </td>
                        <td>
                            @php $status = strtolower($sale->Status ?? $sale->status ?? '') @endphp
                            @if($status == 'selesai')
                                <span class="badge bg-success">Selesai</span>
                            @elseif($status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($status == 'batal' || $status == 'cancelled')
                                <span class="badge bg-danger">Batal</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($status) }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                            Tidak ada data penjualan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $sales->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection
