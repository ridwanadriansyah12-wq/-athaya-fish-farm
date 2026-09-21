@extends('layouts.app')

@section('title', 'Katalog Ikan Segar — Athaya Fish Farm')

@section('extra-css')
<style>
    /* ── KATALOG PAGE — E-Commerce Style ── */

    /* Header */
    .katalog-header {
        background: linear-gradient(135deg, #0D1117 0%, #161B22 100%);
        padding: 3rem 0 2rem;
        position: relative;
        overflow: hidden;
    }
    .katalog-header::before {
        content: '';
        position: absolute;
        top: -40px; right: -60px;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(14,165,233,.15) 0%, transparent 70%);
        border-radius: 50%;
    }
    .katalog-header-content { position: relative; z-index: 1; }
    .katalog-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(26px, 4vw, 38px);
        font-weight: 800;
        color: #F0F6FC;
        margin-bottom: .35rem;
    }
    .katalog-title .accent { color: #0EA5E9; }
    .katalog-subtitle { font-size: 15px; color: #8B949E; margin: 0; }
    .katalog-count-pill {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(14,165,233,.12);
        border: 1px solid rgba(14,165,233,.3);
        color: #0EA5E9; font-size: 13px; font-weight: 600;
        padding: 5px 14px; border-radius: 99px;
        margin-top: .75rem;
    }

    /* ── FILTER BAR ── */
    .filter-bar {
        background: #fff;
        border-bottom: 1px solid #E5E7EB;
        padding: 1.1rem 0;
        position: sticky; top: 58px; z-index: 100;
        box-shadow: 0 2px 8px rgba(0,0,0,.05);
    }
    .filter-bar .form-control,
    .filter-bar .form-select {
        border-radius: 10px;
        border: 1.5px solid #E5E7EB;
        padding: 9px 14px;
        font-size: 14px;
        transition: border-color 200ms ease;
    }
    .filter-bar .form-control:focus,
    .filter-bar .form-select:focus {
        border-color: #0EA5E9;
        box-shadow: 0 0 0 3px rgba(14,165,233,.1);
    }
    .btn-search-filter {
        background: #0EA5E9; color: #fff;
        border: none; border-radius: 10px;
        padding: 9px 20px; font-weight: 600;
        font-size: 14px; display: flex;
        align-items: center; gap: 6px;
        transition: background 200ms ease, transform 150ms ease;
        white-space: nowrap;
    }
    .btn-search-filter:hover { background: #0284C7; transform: translateY(-1px); }
    .btn-reset {
        background: #F3F4F6; color: #6B7280;
        border: 1.5px solid #E5E7EB;
        border-radius: 10px; padding: 9px 14px;
        font-size: 13px; font-weight: 500;
        text-decoration: none; display: flex;
        align-items: center; gap: 5px;
        transition: all 200ms ease;
        white-space: nowrap;
    }
    .btn-reset:hover { background: #E5E7EB; color: #374151; }

    /* Sort pills */
    .sort-pills { display: flex; gap: 6px; flex-wrap: wrap; align-items: center; }
    .sort-pill {
        padding: 5px 14px; border-radius: 99px;
        font-size: 12px; font-weight: 600;
        border: 1.5px solid #E5E7EB;
        color: #6B7280; background: #fff;
        text-decoration: none; cursor: pointer;
        transition: all 150ms ease; white-space: nowrap;
    }
    .sort-pill:hover { border-color: #0EA5E9; color: #0EA5E9; }
    .sort-pill.active {
        background: #0EA5E9; color: #fff;
        border-color: #0EA5E9;
    }

    /* Jenis pills */
    .jenis-pills { display: flex; gap: 6px; flex-wrap: nowrap; overflow-x: auto; align-items: center; padding-bottom: 2px; }
    .jenis-pills::-webkit-scrollbar { height: 3px; }
    .jenis-pills::-webkit-scrollbar-thumb { background: #0EA5E9; border-radius: 2px; }
    .jenis-pill {
        padding: 5px 16px; border-radius: 99px;
        font-size: 12px; font-weight: 600;
        border: 1.5px solid #E5E7EB;
        color: #6B7280; background: #fff;
        text-decoration: none; white-space: nowrap;
        transition: all 150ms ease; flex-shrink: 0;
    }
    .jenis-pill:hover { border-color: #0EA5E9; color: #0EA5E9; }
    .jenis-pill.active {
        background: #0D1117; color: #0EA5E9;
        border-color: #0D1117;
    }

    /* ── PRODUCT GRID SECTION ── */
    .catalog-grid-section {
        padding: 2.5rem 0 4rem;
        background: #F8F9FA;
    }

    /* Card improvements */
    .product-card {
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid #E5E7EB;
        background: #fff;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        transition: transform 250ms ease, box-shadow 250ms ease, border-color 250ms ease;
        position: relative;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 32px rgba(0,0,0,.12);
        border-color: #0EA5E9;
    }
    .product-card:hover .quick-add-btn { opacity: 1; transform: translateY(0); }
    .product-card:hover .product-img-inner { transform: scale(1.06); }

    /* Stock badge */
    .stock-badge-low {
        position: absolute; top: 10px; left: 10px;
        background: #FEF3C7; color: #92400E;
        font-size: 10px; font-weight: 700;
        padding: 3px 8px; border-radius: 6px;
        border: 1px solid #FDE68A;
    }
    .stock-badge-out {
        position: absolute; top: 10px; left: 10px;
        background: #FEF2F2; color: #991B1B;
        font-size: 10px; font-weight: 700;
        padding: 3px 8px; border-radius: 6px;
        border: 1px solid #FECACA;
    }

    /* Quick add button on card hover */
    .quick-add-btn {
        position: absolute; bottom: 0; left: 0; right: 0;
        background: rgba(14,165,233,.92);
        color: #fff; border: none;
        padding: 10px; font-size: 13px; font-weight: 600;
        display: flex; align-items: center; justify-content: center; gap: 6px;
        text-decoration: none;
        opacity: 0; transform: translateY(8px);
        transition: opacity 250ms ease, transform 250ms ease;
    }

    .product-card-name { font-size: 14px; font-weight: 700; color: #111827; margin-bottom: 2px; line-height: 1.3; }
    .product-card-jenis { font-size: 12px; color: #6B7280; margin-bottom: 6px; }
    .product-card-weight { font-size: 11px; color: #9CA3AF; display: flex; align-items: center; gap: 4px; }
    .product-card-price { font-size: 16px; font-weight: 800; color: #0EA5E9; margin: 6px 0; }
    .product-card-stock-ok { font-size: 11px; color: #059669; font-weight: 600; display: flex; align-items: center; gap: 4px; }
    .product-card-stock-no { font-size: 11px; color: #DC2626; font-weight: 600; display: flex; align-items: center; gap: 4px; }

    .btn-detail-card {
        display: block; width: 100%;
        background: #0EA5E9; color: #fff;
        border: none; border-radius: 8px;
        padding: 9px 0; font-size: 13px;
        font-weight: 600; text-decoration: none;
        text-align: center;
        transition: background 200ms ease;
        margin-top: 8px;
    }
    .btn-detail-card:hover { background: #0284C7; color: #fff; }
    .btn-detail-disabled {
        display: block; width: 100%;
        background: #F3F4F6; color: #9CA3AF;
        border: none; border-radius: 8px;
        padding: 9px 0; font-size: 13px;
        text-align: center; cursor: not-allowed;
        margin-top: 8px;
    }

    /* Empty state */
    .empty-state {
        background: #fff; border-radius: 16px;
        border: 1px dashed #D1D5DB;
        padding: 4rem 2rem; text-align: center;
    }
    .empty-state-icon { font-size: 4rem; color: #D1D5DB; margin-bottom: 1rem; }
    .empty-state h5 { font-size: 18px; font-weight: 700; color: #111827; margin-bottom: .5rem; }
    .empty-state p { font-size: 14px; color: #6B7280; }

    /* Result info */
    .result-info { font-size: 13px; color: #6B7280; margin-bottom: 1.25rem; }
    .result-info strong { color: #111827; }
</style>
@endsection

@section('content')

    {{-- ── Katalog Header (dark premium) ── --}}
    <div class="katalog-header">
        <div class="container katalog-header-content">
            <h1 class="katalog-title">
                Katalog <span class="accent">Ikan Segar</span>
            </h1>
            <p class="katalog-subtitle">Langsung dari kolam budidaya — dipanen saat kamu pesan</p>
            <div class="katalog-count-pill">
                <i class="bi bi-fish"></i>
                {{ $totalTersedia }} produk tersedia hari ini
            </div>
        </div>
    </div>

    {{-- ── Filter & Sort Bar ── --}}
    <div class="filter-bar">
        <div class="container">
            <form action="{{ route('katalog.index') }}" method="GET" id="filter-form">
                {{-- Row 1: Search + Cari + Reset --}}
                <div class="d-flex gap-2 align-items-center mb-2 flex-wrap">
                    <div class="flex-grow-1" style="max-width:400px;">
                        <input type="text" name="search" class="form-control"
                               placeholder="🔍  Cari nama produk..." value="{{ request('search') }}">
                    </div>
                    {{-- Simpan sort dan jenis agar tidak hilang saat search --}}
                    <input type="hidden" name="sort" value="{{ request('sort', 'terbaru') }}">
                    <input type="hidden" name="jenis" id="jenis-hidden" value="{{ request('jenis') }}">
                    <button type="submit" class="btn-search-filter">
                        <i class="bi bi-search"></i> Cari
                    </button>
                    @if(request()->anyFilled(['search', 'jenis', 'sort']) && request('sort') !== 'terbaru')
                        <a href="{{ route('katalog.index') }}" class="btn-reset">
                            <i class="bi bi-x-lg"></i> Reset
                        </a>
                    @endif
                </div>

                {{-- Row 2: Jenis Pills + Sort Pills --}}
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="jenis-pills">
                        <a href="{{ route('katalog.index', array_merge(request()->except('jenis', 'page'), ['sort' => request('sort', 'terbaru')])) }}"
                           class="jenis-pill {{ !request('jenis') ? 'active' : '' }}">
                            Semua
                        </a>
                        @foreach($jenisIkan as $jenis)
                            <a href="{{ route('katalog.index', array_merge(request()->except('jenis', 'page'), ['jenis' => $jenis->id, 'sort' => request('sort', 'terbaru')])) }}"
                               class="jenis-pill {{ request('jenis') == $jenis->id ? 'active' : '' }}">
                                {{ $jenis->nama_jenis }}
                            </a>
                        @endforeach
                    </div>
                    <div class="sort-pills">
                        <span style="font-size:12px;color:#9CA3AF;white-space:nowrap;font-weight:500;">Urutkan:</span>
                        @foreach(['terbaru' => 'Terbaru', 'harga_asc' => 'Termurah', 'harga_desc' => 'Termahal', 'stok' => 'Stok Terbanyak'] as $val => $label)
                            <a href="{{ route('katalog.index', array_merge(request()->except('sort', 'page'), ['sort' => $val])) }}"
                               class="sort-pill {{ $sort === $val ? 'active' : '' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Product Grid ── --}}
    <div class="catalog-grid-section">
        <div class="container">

            {{-- Result info --}}
            @if($katalog->count() > 0)
                <p class="result-info animate-in">
                    Menampilkan <strong>{{ $katalog->firstItem() }}–{{ $katalog->lastItem() }}</strong>
                    dari <strong>{{ $katalog->total() }}</strong> produk
                    @if(request('search')) · hasil pencarian "<strong>{{ request('search') }}</strong>" @endif
                    @if(request('jenis')) · jenis <strong>{{ $jenisIkan->find(request('jenis'))?->nama_jenis }}</strong> @endif
                </p>

                <div class="row g-4">
                    @foreach($katalog as $produk)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="product-card animate-in h-100 d-flex flex-column">

                                {{-- Image --}}
                                <div class="product-image" style="position:relative;">
                                    @if($produk->first_image)
                                        <img src="{{ asset('storage/' . $produk->first_image) }}"
                                             alt="{{ $produk->nama_produk }}"
                                             class="product-img-inner"
                                             loading="lazy">
                                    @else
                                        <i class="bi bi-fish text-muted" style="font-size:3rem;"></i>
                                    @endif

                                    {{-- Price badge --}}
                                    @if($produk->harga_satuan)
                                        <div class="product-price-badge">
                                            Rp {{ number_format($produk->harga_satuan, 0, ',', '.') }}
                                        </div>
                                    @endif

                                    {{-- Stock badge --}}
                                    @if($produk->stok <= 0)
                                        <span class="stock-badge-out"><i class="bi bi-x-circle"></i> Habis</span>
                                    @elseif($produk->stok <= 10)
                                        <span class="stock-badge-low"><i class="bi bi-exclamation-triangle"></i> Sisa {{ $produk->stok }}</span>
                                    @endif

                                    {{-- Quick View button on hover --}}
                                    @if($produk->stok > 0)
                                        <a href="{{ route('katalog.show', $produk) }}" class="quick-add-btn">
                                            <i class="bi bi-eye"></i> Lihat Detail
                                        </a>
                                    @endif
                                </div>

                                {{-- Card body --}}
                                <div class="d-flex flex-column flex-grow-1" style="padding:.85rem .9rem .9rem;">
                                    <p class="product-card-jenis">{{ $produk->jenisIkan?->nama_jenis ?? '-' }}</p>
                                    <h6 class="product-card-name">{{ $produk->nama_produk }}</h6>

                                    @if($produk->berat_gram)
                                        <p class="product-card-weight mb-1">
                                            <i class="bi bi-weight"></i> {{ $produk->berat_gram }} gram/ekor
                                        </p>
                                    @endif

                                    <p class="product-card-price">
                                        Rp {{ number_format($produk->harga_satuan, 0, ',', '.') }}
                                    </p>

                                    @if($produk->stok > 0)
                                        <p class="product-card-stock-ok">
                                            <i class="bi bi-check-circle-fill"></i> Stok: {{ $produk->stok }} unit
                                        </p>
                                        <a href="{{ route('katalog.show', $produk) }}" class="btn-detail-card mt-auto">
                                            <i class="bi bi-bag-plus me-1"></i> Lihat & Beli
                                        </a>
                                    @else
                                        <p class="product-card-stock-no">
                                            <i class="bi bi-x-circle-fill"></i> Stok Habis
                                        </p>
                                        <button class="btn-detail-disabled mt-auto" disabled>Tidak Tersedia</button>
                                    @endif
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-5 animate-in">
                    {{ $katalog->links('pagination::bootstrap-5') }}
                </div>

            @else
                <div class="empty-state animate-in">
                    <div class="empty-state-icon"><i class="bi bi-fish"></i></div>
                    <h5>Produk tidak ditemukan</h5>
                    <p>Coba ubah kata kunci pencarian atau pilih kategori lain.</p>
                    <a href="{{ route('katalog.index') }}" class="btn btn-primary mt-2">
                        <i class="bi bi-arrow-left-circle"></i> Lihat Semua Produk
                    </a>
                </div>
            @endif
        </div>
    </div>

@endsection
