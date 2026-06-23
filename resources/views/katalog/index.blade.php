@extends('layouts.app')

@section('title', 'Katalog Ikan')

@section('extra-css')
<style>
    /* ── Katalog Page ── */
    .katalog-header {
        background: #fff;
        padding: 2.5rem 0 1.5rem;
        border-bottom: 1px solid #E5E7EB;
    }
    .katalog-title {
        font-family: 'Inter', sans-serif;
        font-size: 30px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 0.25rem;
    }
    .katalog-subtitle { font-size: 14px; color: #6B7280; margin: 0; }

    /* Search form */
    .search-form .form-control,
    .search-form .form-select {
        border-radius: 10px;
        border: 1px solid #E5E7EB;
        padding: 11px 16px;
        font-size: 14px;
    }
    .search-form .btn-search {
        background: #0EA5E9;
        color: #111827;
        border: none;
        border-radius: 10px;
        padding: 11px 20px;
        font-weight: 600;
        font-size: 14px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: background 200ms ease;
    }
    .search-form .btn-search:hover { background: #0284C7; }

    /* Catalog grid */
    .catalog-grid-section {
        padding: 2.5rem 0 4rem;
        background: #F8F9FA;
    }

    /* Product card inherits .product-card global styles */
    .btn-catalog-detail {
        display: block;
        width: 100%;
        background: #0EA5E9;
        color: #111827;
        border: none;
        border-radius: 8px;
        padding: 9px 0;
        font-size: 13px;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        text-decoration: none;
        text-align: center;
        transition: background 200ms ease;
    }
    .btn-catalog-detail:hover { background: #0284C7; color: #111827; }
    .btn-catalog-disabled {
        display: block;
        width: 100%;
        background: #E5E7EB;
        color: #9CA3AF;
        border: none;
        border-radius: 8px;
        padding: 9px 0;
        font-size: 13px;
        font-family: 'Inter', sans-serif;
        cursor: not-allowed;
    }
</style>
@endsection

@section('content')

    {{-- ── Katalog Header ── --}}
    <div class="katalog-header">
        <div class="container">
            <h1 class="katalog-title animate-in">
                <i class="bi bi-shop me-2" style="color:#0EA5E9"></i>Katalog Ikan
            </h1>
            <p class="katalog-subtitle animate-in" style="transition-delay:60ms">Temukan ikan segar pilihan terbaik dari kolam kami</p>
        </div>
    </div>

    {{-- ── Search & Filter ── --}}
    <div style="background:#fff;border-bottom:1px solid #E5E7EB;padding:1.25rem 0;">
        <div class="container">
            <form action="{{ route('katalog.index') }}" method="GET" class="search-form">
                <div class="row g-3">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control"
                            placeholder="Cari produk..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <select name="jenis" class="form-select">
                            <option value="">-- Semua Jenis --</option>
                            @foreach($jenisIkan as $jenis)
                                <option value="{{ $jenis->id }}" {{ request('jenis') == $jenis->id ? 'selected' : '' }}>
                                    {{ $jenis->nama_jenis }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn-search">
                            <i class="bi bi-search"></i> Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Product Grid ── --}}
    <div class="catalog-grid-section">
        <div class="container">
            @if($katalog->count() > 0)
                <div class="row g-4">
                    @foreach($katalog as $produk)
                        <div class="col-md-6 col-lg-3">
                            <div class="product-card animate-in h-100 d-flex flex-column">
                                <div class="product-image">
                                    @if($produk->first_image)
                                        <img src="{{ asset('storage/' . $produk->first_image) }}"
                                            alt="{{ $produk->nama_produk }}"
                                            class="product-img-inner">
                                    @else
                                        <i class="bi bi-fish text-muted" style="font-size:3.5rem;"></i>
                                    @endif
                                    @if($produk->harga_satuan)
                                        <div class="product-price-badge">
                                            Rp {{ number_format($produk->harga_satuan, 0, ',', '.') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body d-flex flex-column" style="padding:1rem;">
                                    <h6 style="font-size:15px;font-weight:600;color:#111827;margin-bottom:4px;">
                                        {{ $produk->nama_produk }}
                                    </h6>
                                    <p style="font-size:13px;color:#6B7280;margin-bottom:0.5rem;">
                                        {{ $produk->jenisIkan?->nama_jenis ?? '-' }}
                                    </p>

                                    @if($produk->berat_gram)
                                        <p style="font-size:12px;color:#9CA3AF;margin-bottom:0.75rem;">
                                            <i class="bi bi-weight"></i> {{ $produk->berat_gram }} gr
                                        </p>
                                    @endif

                                    @if($produk->stok > 0)
                                        <small class="text-success mb-3" style="font-size:12px;">
                                            <i class="bi bi-check-circle"></i> Tersedia ({{ $produk->stok }} unit)
                                        </small>
                                        <a href="{{ route('katalog.show', $produk) }}" class="btn-catalog-detail mt-auto">
                                            <i class="bi bi-eye"></i> Lihat Detail
                                        </a>
                                    @else
                                        <small class="text-danger mb-3" style="font-size:12px;">
                                            <i class="bi bi-x-circle"></i> Stok Habis
                                        </small>
                                        <button class="btn-catalog-disabled mt-auto" disabled>Stok Habis</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-5 animate-in">
                    {{ $katalog->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="alert alert-info text-center animate-in">
                    <i class="bi bi-info-circle"></i> Tidak ada produk yang ditemukan
                </div>
            @endif
        </div>
    </div>

@endsection
