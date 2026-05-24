@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="h4 font-weight-bold">Laporan Keuangan</h2>
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
            <form method="GET" action="{{ route('pemilik.financial-overview') }}" class="row g-3">
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

    <!-- Financial Summary -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-success">
                <div class="card-body">
                    <div class="text-success font-weight-bold text-uppercase mb-1">Total Pendapatan</div>
                    <div class="h3 mb-0">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-danger">
                <div class="card-body">
                    <div class="text-danger font-weight-bold text-uppercase mb-1">Total Pengeluaran</div>
                    <div class="h3 mb-0">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info">
                <div class="card-body">
                    <div class="text-info font-weight-bold text-uppercase mb-1">Laba Bersih</div>
                    <div class="h3 mb-0">Rp {{ number_format($netProfit, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning">
                <div class="card-body">
                    <div class="text-warning font-weight-bold text-uppercase mb-1">Margin Keuntungan</div>
                    <div class="h3 mb-0">{{ number_format($profitMargin, 2) }}%</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Income & Expense Trend -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Tren Pendapatan & Pengeluaran (6 Bulan)</h5>
                </div>
                <div class="card-body">
                    <div style="display: flex; justify-content: space-around; align-items: flex-end; height: 300px; padding: 20px;">
                        @forelse($trendData as $month => $data)
                            <div style="display: flex; flex-direction: column; align-items: center; flex: 1;">
                                <div style="display: flex; align-items: flex-end; gap: 5px; height: 250px;">
                                    <div style="width: 20px; height: {{ max(($data['income'] / max(collect($trendData)->pluck('income')) * 250), 10) }}px; background: #28a745; border-radius: 2px;" title="Pendapatan: Rp {{ number_format($data['income'], 0) }}"></div>
                                    <div style="width: 20px; height: {{ max(($data['expense'] / max(collect($trendData)->pluck('expense')) * 250), 10) }}px; background: #dc3545; border-radius: 2px;" title="Pengeluaran: Rp {{ number_format($data['expense'], 0) }}"></div>
                                </div>
                                <small class="mt-2 text-muted">{{ $month }}</small>
                            </div>
                        @empty
                            <p class="text-muted">Tidak ada data</p>
                        @endforelse
                    </div>
                    <div style="margin-top: 20px; display: flex; gap: 30px; justify-content: center;">
                        <div><span style="width: 20px; height: 20px; background: #28a745; border-radius: 2px; display: inline-block; margin-right: 5px;"></span> Pendapatan</div>
                        <div><span style="width: 20px; height: 20px; background: #dc3545; border-radius: 2px; display: inline-block; margin-right: 5px;"></span> Pengeluaran</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Records -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Detail Keuangan</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4">ID</th>
                                <th>Tanggal</th>
                                <th>Deskripsi</th>
                                <th>Pendapatan</th>
                                <th>Pengeluaran</th>
                                <th>Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($records as $record)
                            <tr>
                                <td class="px-4">#{{ $record->id }}</td>
                                <td><small class="text-muted">{{ $record->created_at->format('d M Y') }}</small></td>
                                <td><span>{{ Str::limit($record->Deskripsi ?? '-', 40) }}</span></td>
                                <td>
                                    @if($record->Pendapatan > 0)
                                        <span class="text-success font-weight-bold">Rp {{ number_format($record->Pendapatan, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($record->Pengeluaran > 0)
                                        <span class="text-danger font-weight-bold">Rp {{ number_format($record->Pengeluaran, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="font-weight-bold">
                                        Rp {{ number_format(($record->Pendapatan ?? 0) - ($record->Pengeluaran ?? 0), 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Tidak ada data keuangan</td>
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
            {{ $records->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection
