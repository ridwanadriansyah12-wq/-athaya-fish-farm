@extends('layouts.app')

@section('title', $katalog->nama_produk)

@section('extra-css')
<style>
    /* ── Katalog Show / Product Detail ── */
    .detail-section { padding-top: 3rem; padding-bottom: 3rem; }
    .breadcrumb { margin-bottom: 2rem; font-size: 14px; }
    .breadcrumb a { color: #0EA5E9; text-decoration: none; font-weight: 500; }
    .breadcrumb a:hover { color: #0284C7; text-decoration: underline; }
    .breadcrumb-item.active { color: #6B7280; }

    .detail-img-wrapper {
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #E5E7EB;
        background: #F8F9FA;
        height: 400px;
        position: relative;
    }
    .detail-img-wrapper img { width: 100%; height: 100%; object-fit: cover; }
    .detail-img-wrapper .placeholder {
        display: flex; align-items: center; justify-content: center;
        width: 100%; height: 100%; font-size: 80px; color: #D1D5DB;
    }

    .detail-title {
        font-family: 'Playfair Display', serif;
        font-size: 32px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 0.5rem;
    }
    .detail-category {
        font-size: 14px; font-weight: 500;
        color: #6B7280; margin-bottom: 1.5rem;
        display: flex; align-items: center; gap: 6px;
    }

    .price-box {
        background: #161B22; /* dark surface */
        border-radius: 16px;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255,255,255,0.08);
    }
    .price-box-bg-icon {
        position: absolute; top: -10px; right: -10px;
        font-size: 5rem; color: rgba(14,165,233,0.05);
    }
    .price-badge {
        display: inline-block;
        background: rgba(14,165,233,0.15);
        color: #0EA5E9;
        font-size: 12px; font-weight: 600;
        padding: 4px 12px; border-radius: 99px;
        margin-bottom: 0.75rem;
    }
    .price-val-wrap { display: flex; align-items: baseline; gap: 6px; }
    .price-currency { font-size: 1.25rem; color: #8B949E; font-weight: 500; }
    .price-val { font-size: 2.5rem; font-weight: 700; color: #0EA5E9; line-height: 1; letter-spacing: -1px; }
    .price-unit { font-size: 14px; color: #8B949E; }

    .detail-meta p { font-size: 14px; color: #374151; margin-bottom: 0.5rem; }
    .detail-meta i { color: #0EA5E9; width: 20px; display: inline-block; }

    .desc-box { margin-top: 1.5rem; margin-bottom: 2rem; }
    .desc-title { font-size: 16px; font-weight: 600; color: #111827; margin-bottom: 0.5rem; }
    .desc-text { font-size: 14px; color: #6B7280; line-height: 1.7; }

    /* Action buttons */
    .qty-group { max-width: 140px; }
    .qty-group input { text-align: center; font-weight: 600; }
    .btn-qty { background: #F8F9FA; border-color: #E5E7EB; color: #374151; }
    .btn-qty:hover { background: #E5E7EB; }

    .btn-add-cart {
        border: 1.5px solid #0EA5E9;
        color: #0EA5E9;
        background: transparent;
        font-weight: 600;
        transition: all 200ms ease;
    }
    .btn-add-cart:hover { background: #F0F9FF; color: #0284C7; }
    .btn-buy-now {
        background: #0EA5E9;
        color: #111827;
        font-weight: 600;
        border: none;
        transition: all 200ms ease;
    }
    .btn-buy-now:hover { background: #0284C7; transform: translateY(-1px); }

    /* Similar products */
    .similar-title { font-family: 'Playfair Display', serif; font-size: 24px; font-weight: 700; color: #111827; margin-bottom: 1.5rem; }
    .similar-hr { border-color: #E5E7EB; margin: 4rem 0 2rem; }
</style>
@endsection

@section('content')
<div class="container detail-section">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb animate-in">
            <li class="breadcrumb-item"><a href="{{ route('katalog.index') }}">Katalog</a></li>
            <li class="breadcrumb-item active">{{ $katalog->nama_produk }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        <div class="col-md-5">
            <div class="detail-img-wrapper animate-in" style="transition-delay:60ms; height: auto; min-height: 400px; background: transparent; border: none;">
                @if(!empty($katalog->gambar))
                    @if(count($katalog->gambar) === 1)
                        <img src="{{ asset('storage/' . $katalog->gambar[0]) }}" alt="{{ $katalog->nama_produk }}" class="img-fluid rounded-4 shadow-sm border" style="width: 100%; height: 400px; object-fit: cover;">
                    @else
                        <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner rounded-4 shadow-sm border">
                                @foreach($katalog->gambar as $index => $img)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $img) }}" class="d-block w-100" alt="{{ $katalog->nama_produk }}" style="height: 400px; object-fit: cover;">
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.5); border-radius: 50%; padding: 1.25rem;"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.5); border-radius: 50%; padding: 1.25rem;"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                            
                            {{-- Thumbnails --}}
                            <div class="d-flex gap-2 mt-3 justify-content-center">
                                @foreach($katalog->gambar as $index => $img)
                                    <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="{{ $index }}" class="border p-0 rounded-2 overflow-hidden {{ $index === 0 ? 'border-primary' : '' }}" style="width: 60px; height: 60px; transition: all 0.2s;" onclick="document.querySelectorAll('#productCarousel [data-bs-slide-to]').forEach(b => b.classList.remove('border-primary')); this.classList.add('border-primary');">
                                        <img src="{{ asset('storage/' . $img) }}" class="w-100 h-100" style="object-fit: cover;">
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @else
                    <div class="detail-img-wrapper" style="border: 1px solid #E5E7EB; border-radius: 16px; height: 400px;"><div class="placeholder"><i class="bi bi-fish"></i></div></div>
                @endif
            </div>
        </div>

        <div class="col-md-7 animate-in" style="transition-delay:120ms">
            <h2 class="detail-title">{{ $katalog->nama_produk }}</h2>
            <div class="detail-category">
                <i class="bi bi-tags-fill" style="color:#0EA5E9"></i> {{ $katalog->jenisIkan->nama_jenis }}
            </div>

            <div class="price-box">
                <i class="bi bi-tag-fill price-box-bg-icon"></i>
                <div class="price-badge"><i class="bi bi-star-fill me-1"></i> Penawaran Terbaik</div>
                <div class="price-val-wrap">
                    <span class="price-currency">Rp</span>
                    <span class="price-val">{{ number_format($katalog->harga_satuan, 0, ',', '.') }}</span>
                    <span class="price-unit">/ unit</span>
                </div>
            </div>

            <div class="detail-meta">
                @if($katalog->berat_gram)
                    <p><strong><i class="bi bi-weight"></i> Berat:</strong> {{ $katalog->berat_gram }} gram</p>
                @endif
                @if($katalog->jenisIkan->waktu_panen_hari)
                    <p><strong><i class="bi bi-calendar-check"></i> Estimasi Panen:</strong> {{ $katalog->jenisIkan->waktu_panen_hari }} hari</p>
                @endif
                <p><strong><i class="bi bi-box-seam"></i> Stok Tersedia:</strong>
                    @if($katalog->stok > 0)
                        <span class="badge" style="background:#ECFDF5;color:#065F46;border:1px solid #10B981;">{{ $katalog->stok }} unit</span>
                    @else
                        <span class="badge" style="background:#FEF2F2;color:#991B1B;border:1px solid #EF4444;">Habis</span>
                    @endif
                </p>
            </div>

            @if($katalog->deskripsi)
                <div class="desc-box">
                    <h5 class="desc-title">Deskripsi Produk</h5>
                    <p class="desc-text">{{ $katalog->deskripsi }}</p>
                </div>
            @endif

            @auth
                @if(auth()->user()->isCustomer())
                    {{-- Customer only --}}
                    @if($katalog->stok > 0)
                        <form action="{{ route('cart.add', $katalog) }}" method="POST" class="mt-4">
                            @csrf
                            <div class="input-group qty-group mb-3">
                                <button class="btn btn-qty" type="button" id="decreaseQty"><i class="bi bi-dash"></i></button>
                                <input type="number" name="qty" id="qty" class="form-control" value="1" min="1" max="{{ $katalog->stok }}">
                                <button class="btn btn-qty" type="button" id="increaseQty"><i class="bi bi-plus"></i></button>
                            </div>
                            <div class="d-flex gap-3">
                                <button type="submit" name="action" value="add_to_cart" class="btn btn-lg btn-add-cart flex-fill w-50">
                                    <i class="bi bi-cart-plus me-1"></i> Tambah Keranjang
                                </button>
                                <button type="submit" name="action" value="buy_now" class="btn btn-lg btn-buy-now flex-fill w-50">
                                    <i class="bi bi-bag-check me-1"></i> Beli Sekarang
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-danger mt-4 d-flex align-items-center gap-2">
                            <i class="bi bi-exclamation-circle-fill"></i> Mohon maaf, stok produk ini sedang habis.
                        </div>
                    @endif
                @else
                    {{-- Admin / Pemilik --}}
                    <div class="alert alert-info mt-4 d-flex align-items-center gap-2" style="background:#F0F9FF;border-color:#0EA5E9;color:#0C4A6E;">
                        <i class="bi bi-info-circle-fill fs-5" style="color:#0EA5E9"></i>
                        <div>
                            <strong>Mode Pratinjau</strong> — Anda login sebagai
                            <span class="badge bg-dark ms-1">{{ ucfirst(auth()->user()->role) }}</span>.
                            Fitur pembelian hanya untuk pelanggan.
                        </div>
                    </div>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-buy-now btn-lg w-100 mt-4">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Login untuk Membeli
                </a>
            @endauth
        </div>
    </div>

    @if($produkSerupa->count() > 0)
        <hr class="similar-hr animate-in">
        <h4 class="similar-title animate-in">Produk Serupa</h4>

        <div class="row g-4">
            @foreach($produkSerupa as $produk)
                <div class="col-md-3">
                    <div class="product-card animate-in h-100 d-flex flex-column">
                        <div class="product-image">
                            @if($produk->first_image)
                                <img src="{{ asset('storage/' . $produk->first_image) }}" alt="{{ $produk->nama_produk }}" class="product-img-inner">
                            @else
                                <i class="bi bi-fish text-muted" style="font-size:3.5rem;"></i>
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column" style="padding:1rem;">
                            <h6 style="font-size:15px;font-weight:600;color:#111827;margin-bottom:4px;">{{ $produk->nama_produk }}</h6>
                            <p style="font-size:14px;color:#111827;font-weight:700;margin-bottom:1rem;">Rp {{ number_format($produk->harga_satuan, 0, ',', '.') }}</p>
                            <a href="{{ route('katalog.show', $produk) }}" class="btn btn-sm btn-outline-primary w-100 mt-auto" style="border-color:#0EA5E9;color:#0EA5E9;">
                                <i class="bi bi-eye"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const decreaseBtn = document.getElementById('decreaseQty');
        const increaseBtn = document.getElementById('increaseQty');

        if (decreaseBtn) {
            decreaseBtn.addEventListener('click', function() {
                let qty = document.getElementById('qty');
                if (parseInt(qty.value) > 1) {
                    qty.value = parseInt(qty.value) - 1;
                }
            });
        }

        if (increaseBtn) {
            increaseBtn.addEventListener('click', function() {
                let qty = document.getElementById('qty');
                let max = parseInt(qty.getAttribute('max'));
                if (parseInt(qty.value) < max) {
                    qty.value = parseInt(qty.value) + 1;
                }
            });
        }
    });
</script>
@endpush
