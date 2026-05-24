@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="container mt-5">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-bar-chart-line"></i> Laporan Penjualan</h2>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-success fs-6">{{ $totalSales }} Transaksi Lunas</span>
            <a href="{{ route('admin.report.sales.print', request()->only(['start_date','end_date'])) }}"
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
                <div class="col-md-4">
                    <label for="start_date" class="form-label fw-semibold">Dari Tanggal</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                           value="{{ request('start_date') }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label fw-semibold">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                           value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                </div>
                @if(request('start_date') || request('end_date'))
                <div class="col-md-2">
                    <a href="{{ route('admin.report.sales') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                </div>
                @endif
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
