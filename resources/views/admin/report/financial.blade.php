@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="h4 font-weight-bold">Laporan Keuangan</h2>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.report.financial') }}" class="row g-3">
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
            <div class="card text-center border-left-primary">
                <div class="card-body">
                    <h3 class="card-title text-muted">Total Pendapatan</h3>
                    <p class="display-6 font-weight-bold text-success">Rp {{ number_format($totalIncome ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-left-danger">
                <div class="card-body">
                    <h3 class="card-title text-muted">Total Pengeluaran</h3>
                    <p class="display-6 font-weight-bold text-danger">Rp {{ number_format($totalExpense ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-left-info">
                <div class="card-body">
                    <h3 class="card-title text-muted">Laba Bersih</h3>
                    <p class="display-6 font-weight-bold text-info">Rp {{ number_format(($totalIncome ?? 0) - ($totalExpense ?? 0), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-left-warning">
                <div class="card-body">
                    <h3 class="card-title text-muted">Margin Keuntungan</h3>
                    <p class="display-6 font-weight-bold text-warning">
                        @php
                            $margin = ($totalIncome ?? 0) > 0 ? (($totalIncome - $totalExpense) / $totalIncome * 100) : 0;
                        @endphp
                        {{ number_format($margin, 2) }}%
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Details Table -->
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
                    @forelse($financialRecords as $record)
                    <tr>
                        <td class="px-4">#{{ $record->id }}</td>
                        <td>
                            <small class="text-muted">{{ $record->created_at->format('d M Y') }}</small>
                        </td>
                        <td>
                            <span>{{ Str::limit($record->Deskripsi ?? '-', 40) }}</span>
                        </td>
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
                        <td colspan="6" class="text-center py-4 text-muted">
                            <p>Tidak ada data keuangan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="row mt-4">
        <div class="col-md-12">
            {{ $financialRecords->links('pagination::bootstrap-4') ?? '' }}
        </div>
    </div>
</div>
@endsection
