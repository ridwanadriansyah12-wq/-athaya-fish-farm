@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="container mt-5">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-bar-chart-line"></i> Laporan Penjualan</h2>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-success fs-6">{{ $totalSales }} Transaksi Lunas</span>
            <a href="{{ route('admin.report.sales.print', request()->all()) }}"
               target="_blank"
               class="btn btn-dark btn-sm"
               title="Cetak Laporan sebagai dokumen print-friendly"
               id="btn-cetak-laporan">
                <i class="bi bi-printer-fill me-1"></i> Cetak Laporan
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.report.sales') }}" class="row g-3 align-items-end">
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
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('admin.report.sales') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body py-4">
                    <p class="text-muted fw-semibold mb-1" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.5px">Total Penjualan</p>
                    <p class="display-6 fw-bold mb-0" style="color:#4A90A4">{{ $totalSales }}</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body py-4">
                    <p class="text-muted fw-semibold mb-1" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.5px">Total Pendapatan</p>
                    <p class="display-6 fw-bold mb-0 text-success">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body py-4">
                    <p class="text-muted fw-semibold mb-1" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.5px">Rata-rata Penjualan</p>
                    <p class="display-6 fw-bold mb-0 text-info">Rp {{ number_format($averageSale ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body py-4">
                    <p class="text-muted fw-semibold mb-1" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.5px">Penjualan Tertinggi</p>
                    <p class="display-6 fw-bold mb-0 text-warning">Rp {{ number_format($maxSale ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Detail --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-receipt"></i> Detail Penjualan</h5>
            <small class="text-muted">Menampilkan pesanan berstatus <span class="badge bg-success">Lunas</span></small>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No. Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Tanggal Lunas</th>
                        <th>Produk</th>
                        <th>Total Pembayaran</th>
                        <th>Metode Bayar</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr>
                        {{-- No. Pesanan --}}
                        <td class="ps-4">
                            <span class="fw-semibold text-primary">{{ $sale->nomor_pesanan }}</span>
                        </td>

                        {{-- Pelanggan --}}
                        <td>
                            <div class="fw-semibold">{{ $sale->customer->name ?? 'Pelanggan Tidak Ditemukan' }}</div>
                            <small class="text-muted">{{ $sale->customer->email ?? '-' }}</small>
                        </td>

                        {{-- Tanggal Lunas --}}
                        <td>
                            @if($sale->pembayaran_at)
                                <div>{{ $sale->pembayaran_at->format('d M Y') }}</div>
                                <small class="text-muted">{{ $sale->pembayaran_at->format('H:i') }} WIB</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        {{-- Produk --}}
                        <td>
                            @if($sale->detailPesanan->isNotEmpty())
                                @foreach($sale->detailPesanan->take(2) as $detail)
                                    <div class="small">
                                        {{ $detail->katalogIkan->nama_produk ?? 'Produk' }}
                                        <span class="text-muted">×{{ $detail->kuantitas }}</span>
                                    </div>
                                @endforeach
                                @if($sale->detailPesanan->count() > 2)
                                    <small class="text-muted">+{{ $sale->detailPesanan->count() - 2 }} item lainnya</small>
                                @endif
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>

                        {{-- Total Pembayaran --}}
                        <td>
                            <span class="fw-bold text-success">Rp {{ number_format($sale->total_pembayaran, 0, ',', '.') }}</span>
                        </td>

                        {{-- Metode Bayar --}}
                        <td>
                            @php $metode = $sale->pembayaran->metode_pembayaran ?? null; @endphp
                            @if($metode)
                                <span class="badge bg-light text-dark border" style="font-size:.78rem">
                                    {{ ucwords(str_replace('_', ' ', $metode)) }}
                                </span>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="text-center">
                            <span class="badge bg-success px-3 py-1">
                                <i class="bi bi-check-circle-fill me-1"></i> Lunas
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Belum ada pesanan yang lunas
                            @if(request('start_date') || request('end_date'))
                                <br><small>Coba ubah rentang tanggal filter</small>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer total --}}
        @if($sales->isNotEmpty())
        <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
            <small class="text-muted">
                Menampilkan {{ $sales->firstItem() }}–{{ $sales->lastItem() }} dari {{ $sales->total() }} transaksi
            </small>
            <strong class="text-success">
                Total Halaman Ini: Rp {{ number_format($sales->sum('total_pembayaran'), 0, ',', '.') }}
            </strong>
        </div>
        @endif
    </div>

    {{-- Pagination --}}
    @if($sales->hasPages())
    <div class="mt-3 d-flex justify-content-end align-items-center gap-2">
        @if($sales->onFirstPage())
            <button class="btn btn-sm btn-outline-secondary" disabled><i class="bi bi-chevron-left"></i></button>
        @else
            <a href="{{ $sales->previousPageUrl() }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-chevron-left"></i></a>
        @endif

        @foreach($sales->getUrlRange(1, $sales->lastPage()) as $page => $url)
            @if($page == $sales->currentPage())
                <button class="btn btn-sm btn-primary" style="min-width:34px">{{ $page }}</button>
            @else
                <a href="{{ $url }}" class="btn btn-sm btn-outline-primary" style="min-width:34px">{{ $page }}</a>
            @endif
        @endforeach

        @if($sales->hasMorePages())
            <a href="{{ $sales->nextPageUrl() }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-chevron-right"></i></a>
        @else
            <button class="btn btn-sm btn-outline-secondary" disabled><i class="bi bi-chevron-right"></i></button>
        @endif
        <small class="text-muted">Halaman {{ $sales->currentPage() }} dari {{ $sales->lastPage() }}</small>
    </div>
    @endif

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
