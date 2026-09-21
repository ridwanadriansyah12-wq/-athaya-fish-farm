@extends('layouts.app')

@section('title', $katalog->nama_produk . ' — Athaya Fish Farm')

@section('extra-css')
<style>
    .detail-section { padding-top: 2.5rem; padding-bottom: 3rem; background: #F8F9FA; }

    /* Breadcrumb */
    .breadcrumb-custom { margin-bottom: 1.5rem; font-size: 13px; display: flex; align-items: center; gap: 6px; }
    .breadcrumb-custom a { color: #0EA5E9; text-decoration: none; font-weight: 500; }
    .breadcrumb-custom a:hover { color: #0284C7; text-decoration: underline; }
    .breadcrumb-sep { color: #9CA3AF; }
    .breadcrumb-current { color: #6B7280; }

    /* Main Card */
    .detail-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid #E5E7EB;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        overflow: hidden;
    }

    /* Image gallery */
    .detail-main-img {
        width: 100%; aspect-ratio: 1/1;
        object-fit: cover; border-radius: 12px;
        border: 1px solid #E5E7EB;
    }
    .detail-img-placeholder {
        width: 100%; aspect-ratio: 1/1;
        border-radius: 12px;
        background: #F3F4F6;
        display: flex; align-items: center; justify-content: center;
        font-size: 80px; color: #D1D5DB;
        border: 1px solid #E5E7EB;
    }
    .thumb-wrap { display: flex; gap: 8px; margin-top: 10px; flex-wrap: wrap; }
    .thumb-btn {
        width: 64px; height: 64px;
        border-radius: 8px; overflow: hidden;
        border: 2px solid #E5E7EB;
        padding: 0; cursor: pointer;
        transition: border-color 200ms ease;
        background: none;
    }
    .thumb-btn:hover, .thumb-btn.active { border-color: #0EA5E9; }
    .thumb-btn img { width: 100%; height: 100%; object-fit: cover; }

    /* Info side */
    .detail-jenis-tag {
        display: inline-flex; align-items: center; gap: 5px;
        background: #E0F2FE; color: #0369A1;
        font-size: 12px; font-weight: 600;
        padding: 4px 12px; border-radius: 99px;
        margin-bottom: .75rem;
    }
    .detail-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(22px, 3.5vw, 30px);
        font-weight: 800;
        color: #111827;
        line-height: 1.2;
        margin-bottom: .5rem;
    }

    /* Rating bar */
    .rating-bar { display: flex; align-items: center; gap: 8px; margin-bottom: 1rem; }
    .stars { color: #F59E0B; font-size: 14px; letter-spacing: 1px; }
    .rating-count { font-size: 13px; color: #6B7280; }

    /* Price block */
    .price-block {
        background: linear-gradient(135deg, #0D1117, #161B22);
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.25rem;
        border: 1px solid rgba(255,255,255,.08);
        position: relative; overflow: hidden;
    }
    .price-block::after {
        content: '🐟';
        position: absolute; top: 50%; right: 1rem;
        transform: translateY(-50%);
        font-size: 4rem; opacity: .05;
    }
    .price-label { font-size: 12px; color: #8B949E; margin-bottom: 4px; }
    .price-main { display: flex; align-items: baseline; gap: 6px; }
    .price-rp { font-size: 1.1rem; color: #8B949E; }
    .price-number { font-size: 2.2rem; font-weight: 800; color: #0EA5E9; line-height: 1; letter-spacing: -1px; }
    .price-unit { font-size: 13px; color: #8B949E; }

    /* Meta info */
    .meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 1.25rem; }
    .meta-item {
        background: #F8F9FA; border-radius: 10px;
        padding: .75rem 1rem;
        border: 1px solid #F0F0F0;
    }
    .meta-item-label { font-size: 11px; color: #9CA3AF; font-weight: 600; text-transform: uppercase; letter-spacing: .4px; margin-bottom: 3px; }
    .meta-item-value { font-size: 14px; font-weight: 700; color: #111827; }
    .meta-item-value.in-stock { color: #059669; }
    .meta-item-value.out-stock { color: #DC2626; }

    /* Description */
    .desc-box {
        background: #F8F9FA; border-radius: 12px;
        padding: 1.1rem 1.25rem; margin-bottom: 1.25rem;
        border: 1px solid #F0F0F0;
    }
    .desc-title { font-size: 14px; font-weight: 700; color: #111827; margin-bottom: .5rem; }
    .desc-text { font-size: 14px; color: #4B5563; line-height: 1.75; margin: 0; }

    /* Qty & Actions */
    .qty-row { display: flex; align-items: center; gap: 12px; margin-bottom: 1rem; }
    .qty-control {
        display: flex; align-items: center; border: 1.5px solid #E5E7EB;
        border-radius: 10px; overflow: hidden; background: #fff;
    }
    .qty-btn {
        width: 40px; height: 42px;
        background: #F8F9FA; border: none;
        font-size: 18px; font-weight: 700;
        color: #374151; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: background 150ms ease;
    }
    .qty-btn:hover { background: #E5E7EB; }
    .qty-input {
        width: 52px; height: 42px;
        text-align: center; border: none;
        font-weight: 700; font-size: 15px; color: #111827;
        background: #fff;
        -moz-appearance: textfield;
    }
    .qty-input::-webkit-outer-spin-button,
    .qty-input::-webkit-inner-spin-button { -webkit-appearance: none; }
    .qty-input:focus { outline: none; }

    .btn-add-cart {
        flex: 1; padding: 12px; border-radius: 10px;
        border: 2px solid #0EA5E9; color: #0EA5E9;
        background: transparent; font-weight: 700;
        font-size: 14px; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 6px;
        transition: all 200ms ease;
    }
    .btn-add-cart:hover { background: #F0F9FF; }

    .btn-buy-now {
        flex: 1; padding: 12px; border-radius: 10px;
        border: none; color: #fff;
        background: #0EA5E9; font-weight: 700;
        font-size: 14px; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 6px;
        transition: all 200ms ease;
    }
    .btn-buy-now:hover { background: #0284C7; transform: translateY(-1px); }

    /* WA share */
    .btn-wa-share {
        display: flex; align-items: center; justify-content: center; gap: 6px;
        background: #F0FDF4; color: #16A34A;
        border: 1.5px solid #BBF7D0;
        border-radius: 10px; padding: 9px 16px;
        font-size: 13px; font-weight: 600;
        text-decoration: none;
        transition: all 200ms ease;
    }
    .btn-wa-share:hover { background: #DCFCE7; color: #15803D; }

    /* Guarantee badges */
    .guarantee-row {
        display: flex; gap: 8px; flex-wrap: wrap; margin-top: 1rem;
    }
    .guarantee-badge {
        display: flex; align-items: center; gap: 5px;
        font-size: 11px; font-weight: 600;
        color: #374151; background: #F9FAFB;
        border: 1px solid #E5E7EB;
        border-radius: 8px; padding: 5px 10px;
    }
    .guarantee-badge i { color: #0EA5E9; font-size: 13px; }

    /* Similar products */
    .similar-section { margin-top: 2.5rem; }
    .similar-title { font-family: 'Playfair Display', serif; font-size: 22px; font-weight: 800; color: #111827; margin-bottom: 1.25rem; }
</style>
@endsection

@section('content')
<div class="detail-section">
    <div class="container">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb">
            <div class="breadcrumb-custom animate-in">
                <a href="{{ route('home') }}"><i class="bi bi-house"></i> Beranda</a>
                <span class="breadcrumb-sep">/</span>
                <a href="{{ route('katalog.index') }}">Katalog</a>
                <span class="breadcrumb-sep">/</span>
                <span class="breadcrumb-current">{{ Str::limit($katalog->nama_produk, 30) }}</span>
            </div>
        </nav>

        {{-- Main Detail Card --}}
        <div class="detail-card animate-in" style="transition-delay:60ms;">
            <div class="row g-0">

                {{-- Left: Gallery --}}
                <div class="col-md-5 p-3 p-md-4">
                    @if(!empty($katalog->gambar) && count($katalog->gambar) > 0)
                        <img src="{{ asset('storage/' . $katalog->gambar[0]) }}"
                             alt="{{ $katalog->nama_produk }}"
                             class="detail-main-img" id="main-product-img">

                        @if(count($katalog->gambar) > 1)
                            <div class="thumb-wrap">
                                @foreach($katalog->gambar as $idx => $img)
                                    <button type="button" class="thumb-btn {{ $idx === 0 ? 'active' : '' }}"
                                            onclick="switchImg(this, '{{ asset('storage/' . $img) }}')">
                                        <img src="{{ asset('storage/' . $img) }}" alt="">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="detail-img-placeholder">
                            <i class="bi bi-fish"></i>
                        </div>
                    @endif
                </div>

                {{-- Right: Info --}}
                <div class="col-md-7 p-3 p-md-4 ps-md-3">

                    <span class="detail-jenis-tag">
                        <i class="bi bi-tags-fill"></i> {{ $katalog->jenisIkan->nama_jenis ?? 'Ikan' }}
                    </span>

                    <h1 class="detail-title">{{ $katalog->nama_produk }}</h1>

                    {{-- Rating simulasi --}}
                    <div class="rating-bar">
                        <span class="stars">★★★★★</span>
                        <span style="font-size:14px;font-weight:700;color:#111827;">{{ $ratingSimulasi['nilai'] }}</span>
                        <span class="rating-count">({{ $ratingSimulasi['count'] }} ulasan)</span>
                        <span style="font-size:13px;color:#059669;font-weight:600;">
                            <i class="bi bi-check-circle-fill"></i> Terpercaya
                        </span>
                    </div>

                    {{-- Price block --}}
                    <div class="price-block">
                        <div class="price-label">Harga satuan</div>
                        <div class="price-main">
                            <span class="price-rp">Rp</span>
                            <span class="price-number">{{ number_format($katalog->harga_satuan, 0, ',', '.') }}</span>
                            <span class="price-unit">/ ekor</span>
                        </div>
                    </div>

                    {{-- Meta info grid --}}
                    <div class="meta-grid">
                        <div class="meta-item">
                            <div class="meta-item-label"><i class="bi bi-box-seam me-1"></i>Stok</div>
                            <div class="meta-item-value {{ $katalog->stok > 0 ? 'in-stock' : 'out-stock' }}">
                                @if($katalog->stok > 0)
                                    {{ $katalog->stok }} unit tersedia
                                @else
                                    Stok habis
                                @endif
                            </div>
                        </div>
                        @if($katalog->berat_gram)
                        <div class="meta-item">
                            <div class="meta-item-label"><i class="bi bi-weight me-1"></i>Berat</div>
                            <div class="meta-item-value">{{ $katalog->berat_gram }} gram</div>
                        </div>
                        @endif
                        @if($katalog->jenisIkan?->waktu_panen_hari)
                        <div class="meta-item">
                            <div class="meta-item-label"><i class="bi bi-calendar-check me-1"></i>Estimasi Panen</div>
                            <div class="meta-item-value">{{ $katalog->jenisIkan->waktu_panen_hari }} hari</div>
                        </div>
                        @endif
                        <div class="meta-item">
                            <div class="meta-item-label"><i class="bi bi-truck me-1"></i>Pengiriman</div>
                            <div class="meta-item-value" style="color:#374151;">Langsung dari kolam</div>
                        </div>
                    </div>

                    {{-- Description --}}
                    @if($katalog->deskripsi)
                        <div class="desc-box">
                            <div class="desc-title"><i class="bi bi-info-circle-fill me-2" style="color:#0EA5E9;"></i>Deskripsi Produk</div>
                            <p class="desc-text">{{ $katalog->deskripsi }}</p>
                        </div>
                    @endif

                    {{-- ACTION: Customer --}}
                    @auth
                        @if(auth()->user()->isCustomer())
                            @if($katalog->stok > 0)
                                <form action="{{ route('cart.add', $katalog) }}" method="POST">
                                    @csrf
                                    <div class="qty-row">
                                        <span style="font-size:13px;font-weight:600;color:#374151;">Jumlah:</span>
                                        <div class="qty-control">
                                            <button class="qty-btn" type="button" id="decBtn">−</button>
                                            <input type="number" name="qty" id="qty" class="qty-input"
                                                   value="1" min="1" max="{{ $katalog->stok }}">
                                            <button class="qty-btn" type="button" id="incBtn">+</button>
                                        </div>
                                        <span style="font-size:12px;color:#9CA3AF;">Maks. {{ $katalog->stok }}</span>
                                    </div>
                                    <div class="d-flex gap-2 mb-2">
                                        <button type="submit" name="action" value="add_to_cart" class="btn-add-cart">
                                            <i class="bi bi-cart-plus"></i> Keranjang
                                        </button>
                                        <button type="submit" name="action" value="buy_now" class="btn-buy-now">
                                            <i class="bi bi-bag-check"></i> Beli Sekarang
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="alert alert-danger d-flex align-items-center gap-2 mt-2" style="border-radius:12px;">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    Maaf, stok produk ini sedang habis. Hubungi kami untuk pre-order.
                                </div>
                            @endif

                            {{-- WhatsApp Share --}}
                            <a href="https://wa.me/6289613130130?text={{ urlencode('Halo Athaya Fish Farm, saya tertarik dengan ' . $katalog->nama_produk . ' (Rp ' . number_format($katalog->harga_satuan, 0, ',', '.') . '/ekor). Apakah stok masih ada?') }}"
                               target="_blank" class="btn-wa-share w-100 mb-2">
                                <i class="bi bi-whatsapp"></i> Tanya via WhatsApp
                            </a>
                        @else
                            {{-- Admin/Pemilik preview notice --}}
                            <div class="alert alert-info d-flex align-items-center gap-2"
                                 style="background:#F0F9FF;border:1px solid #0EA5E9;border-radius:12px;color:#0C4A6E;">
                                <i class="bi bi-info-circle-fill" style="color:#0EA5E9;font-size:1.2rem;"></i>
                                <div>
                                    <strong>Mode Pratinjau</strong> — Kamu login sebagai
                                    <span class="badge bg-dark ms-1">{{ ucfirst(auth()->user()->role) }}</span>.
                                    Fitur pembelian tersedia untuk pelanggan.
                                </div>
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-buy-now w-100 text-decoration-none mb-2"
                           style="border-radius:10px;">
                            <i class="bi bi-box-arrow-in-right"></i> Login untuk Membeli
                        </a>
                    @endauth

                    {{-- Guarantee badges --}}
                    <div class="guarantee-row">
                        <div class="guarantee-badge"><i class="bi bi-shield-check"></i> Bayar via Midtrans</div>
                        <div class="guarantee-badge"><i class="bi bi-arrow-clockwise"></i> Stok Akurat</div>
                        <div class="guarantee-badge"><i class="bi bi-fish"></i> Dari Kolam Sendiri</div>
                        <div class="guarantee-badge"><i class="bi bi-chat-dots"></i> Layanan 24/7</div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Similar Products --}}
        @if($produkSerupa->count() > 0)
            <div class="similar-section animate-in" style="transition-delay:120ms;">
                <h2 class="similar-title">Produk Serupa</h2>
                <div class="row g-4">
                    @foreach($produkSerupa as $produk)
                        <div class="col-6 col-md-3">
                            <div class="product-card animate-in h-100 d-flex flex-column">
                                <div class="product-image" style="position:relative;">
                                    @if($produk->first_image)
                                        <img src="{{ asset('storage/' . $produk->first_image) }}"
                                             alt="{{ $produk->nama_produk }}"
                                             class="product-img-inner" loading="lazy">
                                    @else
                                        <i class="bi bi-fish text-muted" style="font-size:2.5rem;"></i>
                                    @endif
                                    @if($produk->harga_satuan)
                                        <div class="product-price-badge">
                                            Rp {{ number_format($produk->harga_satuan, 0, ',', '.') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="d-flex flex-column flex-grow-1" style="padding:.85rem .9rem .9rem;">
                                    <h6 class="product-card-name">{{ $produk->nama_produk }}</h6>
                                    <p class="product-card-price">Rp {{ number_format($produk->harga_satuan, 0, ',', '.') }}</p>
                                    <a href="{{ route('katalog.show', $produk) }}"
                                       class="btn-detail-card mt-auto" style="font-size:12px;padding:7px 0;">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Qty control
    const qtyInput = document.getElementById('qty');
    if (qtyInput) {
        const max = parseInt(qtyInput.getAttribute('max'));
        document.getElementById('decBtn')?.addEventListener('click', () => {
            if (parseInt(qtyInput.value) > 1) qtyInput.value--;
        });
        document.getElementById('incBtn')?.addEventListener('click', () => {
            if (parseInt(qtyInput.value) < max) qtyInput.value++;
        });
    }

    // Image switcher
    window.switchImg = function(btn, src) {
        document.getElementById('main-product-img').src = src;
        document.querySelectorAll('.thumb-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    };
});
</script>
@endpush
