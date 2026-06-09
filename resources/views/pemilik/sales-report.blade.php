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
            <a href="{{ route('pemilik.sales-report.print', request()->all()) }}"
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
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <form method="GET" action="{{ route('pemilik.sales-report') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="filter_type" class="form-label fw-semibold">Tipe Laporan</label>
                    <select class="form-select" id="filter_type" name="filter_type" onchange="toggleFilterFields()">
                        <option value="harian" {{ ($filterType ?? 'harian') == 'harian' ? 'selected' : '' }}>Harian (Rentang Tanggal)</option>
                        <option value="bulanan" {{ ($filterType ?? 'harian') == 'bulanan' ? 'selected' : '' }}>Per Bulan</option>
                        <option value="tahunan" {{ ($filterType ?? 'harian') == 'tahunan' ? 'selected' : '' }}>Per Tahun</option>
                    </select>
                </div>

                {{-- Fields untuk Harian --}}
                <div class="col-md-3 filter-field filter-harian">
                    <label for="start_date" class="form-label fw-semibold">Dari Tanggal</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                           value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3 filter-field filter-harian">
                    <label for="end_date" class="form-label fw-semibold">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                           value="{{ request('end_date') }}">
                </div>

                {{-- Fields untuk Bulanan --}}
                <div class="col-md-3 filter-field filter-bulanan" style="display: none;">
                    <label for="month" class="form-label fw-semibold">Pilih Bulan</label>
                    <select class="form-select" id="month" name="month">
                        @php
                            $months = [
                                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                                '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                                '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                            ];
                            $currentMonth = $selectedMonth ?? date('m');
                        @endphp
                        @foreach($months as $num => $name)
                            <option value="{{ $num }}" {{ $currentMonth == $num ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Fields untuk Tahunan dan Bulanan --}}
                <div class="col-md-3 filter-field filter-tahun-select" style="display: none;">
                    <label for="year" class="form-label fw-semibold">Pilih Tahun</label>
                    <select class="form-select" id="year" name="year">
                        @foreach($availableYears ?? [date('Y')] as $yr)
                            <option value="{{ $yr }}" {{ ($selectedYear ?? date('Y')) == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end gap-2 ms-auto">
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
                        <th class="px-4">No. Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Tanggal Lunas</th>
                        <th>Total Pembayaran</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr>
                        <td class="px-4">
                            <span class="fw-semibold text-primary">{{ $sale->nomor_pesanan }}</span>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $sale->customer->name ?? 'Anonim' }}</div>
                            <small class="text-muted">{{ $sale->customer->email ?? '-' }}</small>
                        </td>
                        <td>
                            @if($sale->pembayaran_at)
                                <div>{{ $sale->pembayaran_at->format('d M Y') }}</div>
                                <small class="text-muted">{{ $sale->pembayaran_at->format('H:i') }} WIB</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="fw-bold text-success">Rp {{ number_format($sale->total_pembayaran, 0, ',', '.') }}</span>
                        </td>
                        <td>
                            <span class="badge bg-success">Lunas</span>
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

@push('scripts')
<script>
function toggleFilterFields() {
    const filterType = document.getElementById('filter_type').value;
    const harianFields = document.querySelectorAll('.filter-harian');
    const bulananFields = document.querySelectorAll('.filter-bulanan');
    const tahunFields = document.querySelectorAll('.filter-tahun-select');

    if (filterType === 'harian') {
        harianFields.forEach(el => el.style.display = 'block');
        bulananFields.forEach(el => el.style.display = 'none');
        tahunFields.forEach(el => el.style.display = 'none');
    } else if (filterType === 'bulanan') {
        harianFields.forEach(el => el.style.display = 'none');
        bulananFields.forEach(el => el.style.display = 'block');
        tahunFields.forEach(el => el.style.display = 'block');
    } else if (filterType === 'tahunan') {
        harianFields.forEach(el => el.style.display = 'none');
        bulananFields.forEach(el => el.style.display = 'none');
        tahunFields.forEach(el => el.style.display = 'block');
    }
}

// Run on page load to initialize correct state
document.addEventListener('DOMContentLoaded', function() {
    toggleFilterFields();
});
</script>
@endpush
