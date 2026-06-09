@extends('layouts.app')

@section('title', 'Beranda')

@section('extra-css')
<style>
    /* ===================================================================
       WELCOME PAGE — Luxury Aquaculture Dark & Gold
    =================================================================== */

    /* ── HERO ─────────────────────────────────────────────────────── */
    .hero-section {
        background: #0D1117;
        background-image: radial-gradient(ellipse at 80% 10%, rgba(21,67,135,0.30) 0%, transparent 60%);
        color: #fff;
        padding: 5rem 0 0;
        overflow: hidden;
        position: relative;
    }

    .hero-badge {
        display: inline-block;
        border: 1px solid #F5A623;
        color: #F5A623;
        background: transparent;
        border-radius: 99px;
        padding: 6px 18px;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 0.4px;
        margin-bottom: 1.25rem;
    }

    .hero-title {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: clamp(40px, 6vw, 56px);
        font-weight: 700;
        line-height: 1.12;
        color: #F0F6FC;
        margin-bottom: 1.25rem;
    }

    .hero-title .gold-text {
        color: #F5A623;
    }

    .hero-subtitle {
        font-family: 'Inter', sans-serif;
        font-size: 16px;
        line-height: 1.7;
        color: #8B949E;
        max-width: 520px;
        margin-bottom: 2rem;
    }

    /* CTA Buttons */
    .btn-hero-primary {
        background: #F5A623;
        color: #111827;
        border: none;
        border-radius: 99px;
        padding: 14px 32px;
        font-size: 15px;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: transform 200ms ease, box-shadow 200ms ease, background 200ms ease;
    }
    .btn-hero-primary:hover {
        background: #D4890F;
        color: #111827;
        transform: scale(1.03);
        box-shadow: 0 4px 20px rgba(245,166,35,0.35);
    }

    .btn-hero-secondary {
        border: 1.5px solid rgba(255,255,255,0.25);
        color: #fff;
        background: transparent;
        border-radius: 99px;
        padding: 14px 32px;
        font-size: 15px;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: border-color 200ms ease, background 200ms ease;
    }
    .btn-hero-secondary:hover {
        border-color: #F5A623;
        color: #F5A623;
        background: rgba(245,166,35,0.05);
    }

    /* Hero image */
    .hero-img-frame {
        width: 380px;
        height: 380px;
        border-radius: 50%;
        border: 2px solid rgba(245,166,35,0.5);
        overflow: hidden;
        position: relative;
        display: inline-block;
        filter: drop-shadow(0 0 28px rgba(245,166,35,0.20));
        animation: float 6s ease-in-out infinite;
    }
    .hero-img-frame img {
        width: 100%; height: 100%;
        object-fit: cover;
    }

    @keyframes float {
        0%   { transform: translateY(0px); }
        50%  { transform: translateY(-16px); }
        100% { transform: translateY(0px); }
    }

    /* Wave divider */
    .hero-wave {
        position: absolute;
        bottom: 0; left: 0;
        width: 100%; overflow: hidden;
        line-height: 0;
    }
    .hero-wave svg {
        display: block;
        width: calc(100% + 1.3px);
        height: 56px;
    }

    /* ── FEATURES SECTION ─────────────────────────────────────────── */
    .features-section {
        background: #F8F9FA;
        padding: 5rem 0 4rem;
    }

    .feature-card {
        background: #fff;
        border: 0.5px solid #E5E7EB;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        transition: transform 250ms ease, box-shadow 250ms ease, border-color 250ms ease;
        height: 100%;
    }
    .feature-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.10);
        border-color: #F5A623;
    }

    .feature-icon {
        width: 48px; height: 48px;
        border-radius: 50%;
        background: #FEF3C7;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px;
        color: #D97706;
        margin-bottom: 1rem;
        flex-shrink: 0;
    }

    .feature-title {
        font-family: 'Inter', sans-serif;
        font-size: 18px;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.5rem;
    }

    .feature-desc {
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        color: #6B7280;
        line-height: 1.6;
        margin: 0;
    }

    /* ── CATALOG SECTION ──────────────────────────────────────────── */
    .catalog-section {
        background: #fff;
        padding: 4.5rem 0;
    }

    .section-title {
        font-family: 'Inter', sans-serif;
        font-size: 30px;
        font-weight: 700;
        color: #111827;
        text-align: center;
        margin-bottom: 0.5rem;
    }

    .section-subtitle {
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        color: #6B7280;
        text-align: center;
        margin-bottom: 2.5rem;
    }

    /* Product cards reuse .product-card, .product-image from global */
    .btn-catalog-detail {
        display: block;
        width: 100%;
        background: #F5A623;
        color: #111827;
        border: none;
        border-radius: 8px;
        padding: 9px 0;
        font-size: 14px;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        text-decoration: none;
        text-align: center;
        transition: background 200ms ease;
    }
    .btn-catalog-detail:hover { background: #D4890F; color: #111827; }

    .btn-catalog-secondary {
        background: #E5E7EB;
        color: #6B7280;
        border: none;
        border-radius: 8px;
        padding: 9px 0;
        width: 100%;
        font-size: 14px;
        font-family: 'Inter', sans-serif;
    }

    /* View all button */
    .btn-view-all {
        border: 1.5px solid #F5A623;
        color: #F5A623;
        border-radius: 99px;
        padding: 12px 32px;
        font-size: 15px;
        font-weight: 600;
        background: transparent;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: background 200ms ease, color 200ms ease;
    }
    .btn-view-all:hover {
        background: #F5A623;
        color: #111827;
    }

    /* ── TENTANG SECTION ──────────────────────────────────────────── */
    .about-section {
        background: #F8F9FA;
        padding: 4.5rem 0;
    }

    .about-card {
        background: #fff;
        border: 0.5px solid #E5E7EB;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        height: 100%;
    }

    .about-info-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 10px 0;
        border-bottom: 1px solid #F3F4F6;
    }
    .about-info-item:last-child { border-bottom: none; }
    .about-info-title { font-size: 13px; font-weight: 600; color: #111827; }
    .about-info-val   { font-size: 13px; color: #6B7280; }

    @media (max-width: 991px) {
        .hero-section { padding-top: 3rem; }
        .hero-title   { font-size: 36px; }
        .hero-img-frame { width: 280px; height: 280px; }
        .hero-section .hero-img-col { padding-top: 2rem; }
    }
    @media (max-width: 575px) {
        .btn-hero-primary, .btn-hero-secondary { width: 100%; justify-content: center; }
        .hero-img-frame { width: 220px; height: 220px; }
    }
</style>
@endsection

@section('content')

    {{-- ===================================================================
         HERO SECTION
    =================================================================== --}}
    <div class="hero-section">
        <div class="hero-wave">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C59.71,118,130.85,130.2,201.3,123.63,243.68,119.67,283.47,105.15,321.39,56.44Z"
                    fill="#F8F9FA"></path>
            </svg>
        </div>

        <div class="container position-relative" style="z-index:1; padding-bottom: 4.5rem;">
            <div class="row align-items-center">
                {{-- Text Content --}}
                <div class="col-lg-6 text-center text-lg-start mb-5 mb-lg-0">
                    <div class="hero-badge animate-in">#1 E-Commerce Ikan Segar</div>
                    <h1 class="hero-title animate-in" style="transition-delay:80ms">
                        Athaya <span class="gold-text">Fish Farm</span>
                    </h1>
                    <p class="hero-subtitle animate-in" style="transition-delay:160ms">
                        Platform terpercaya untuk membeli ikan segar berkualitas tinggi dan layanan jasa budidaya
                        profesional secara langsung dari ahlinya.
                    </p>

                    <div class="d-flex flex-column flex-sm-row justify-content-center justify-content-lg-start gap-3 animate-in" style="transition-delay:240ms">
                        @auth
                            <a href="{{ route('katalog.index') }}" class="btn-hero-primary">
                                <i class="bi bi-shop"></i> Mulai Belanja
                            </a>
                            <a href="{{ route('dashboard') }}" class="btn-hero-secondary">
                                <i class="bi bi-speedometer2"></i> Ke Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-hero-primary">
                                <i class="bi bi-box-arrow-in-right"></i> Masuk Sekarang
                            </a>
                            <a href="{{ route('register') }}" class="btn-hero-secondary">
                                <i class="bi bi-person-plus"></i> Buat Akun Baru
                            </a>
                        @endauth
                    </div>
                </div>

                {{-- Image Illustration --}}
                <div class="col-lg-6 d-flex justify-content-center hero-img-col animate-in" style="transition-delay:120ms">
                    <div class="hero-img-frame">
                        <img src="https://images.unsplash.com/photo-1524704796725-9fc3044a58b2?q=80&w=800&auto=format&fit=crop"
                            alt="Ikan Segar Berkualitas dari Athaya Fish Farm">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================================================================
         FEATURE CARDS
    =================================================================== --}}
    <section class="features-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card animate-in">
                        <div class="feature-icon">
                            <i class="bi bi-shop"></i>
                        </div>
                        <h3 class="feature-title">Katalog Lengkap</h3>
                        <p class="feature-desc">Berbagai jenis ikan berkualitas dengan harga terjangkau, dipilih langsung dari kolam budidaya terpercaya.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card animate-in" style="transition-delay:80ms">
                        <div class="feature-icon">
                            <i class="bi bi-droplet-half"></i>
                        </div>
                        <h3 class="feature-title">Layanan Budidaya</h3>
                        <p class="feature-desc">Kami siap membudidayakan ikan Anda secara profesional dengan teknologi dan pengalaman terbaik.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card animate-in" style="transition-delay:160ms">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h3 class="feature-title">Pembayaran Aman</h3>
                        <p class="feature-desc">Sistem pembayaran terintegrasi dengan Midtrans — transaksi terlindungi dan terverifikasi.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================================================================
         KATALOG PRODUK TERBARU
    =================================================================== --}}
    <section class="catalog-section">
        <div class="container">
            <h2 class="section-title animate-in">Katalog Produk Terbaru</h2>
            <p class="section-subtitle animate-in" style="transition-delay:60ms">Pilih ikan segar berkualitas dari kolam kami</p>

            @if(isset($katalog) && $katalog->count() > 0)
                <div class="row g-4">
                    @foreach($katalog as $produk)
                        <div class="col-md-6 col-lg-3">
                            <div class="product-card animate-in h-100 d-flex flex-column">
                                <div class="product-image">
                                    @if($produk->gambar)
                                        <img src="{{ asset('storage/' . $produk->gambar) }}"
                                            alt="{{ $produk->nama_produk }}"
                                            class="product-img-inner">
                                    @else
                                        <i class="bi bi-fish text-muted" style="font-size: 3.5rem;"></i>
                                    @endif
                                    @if($produk->harga_satuan)
                                        <div class="product-price-badge">
                                            Rp {{ number_format($produk->harga_satuan, 0, ',', '.') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body d-flex flex-column" style="padding: 1rem;">
                                    <h6 style="font-size:15px;font-weight:600;color:#111827;margin-bottom:4px;">{{ $produk->nama_produk }}</h6>
                                    <p style="font-size:13px;color:#6B7280;margin-bottom:0.75rem;">
                                        {{ $produk->jenisIkan->nama_jenis ?? 'Umum' }}
                                    </p>

                                    @if($produk->stok > 0)
                                        <small class="text-success mb-3" style="font-size:12px;">
                                            <i class="bi bi-check-circle"></i> Tersedia ({{ $produk->stok }})
                                        </small>
                                        <a href="{{ route('katalog.show', $produk) }}" class="btn-catalog-detail mt-auto">
                                            <i class="bi bi-eye"></i> Lihat Detail
                                        </a>
                                    @else
                                        <small class="text-danger mb-3" style="font-size:12px;">
                                            <i class="bi bi-x-circle"></i> Stok Habis
                                        </small>
                                        <button class="btn-catalog-secondary mt-auto" disabled>Stok Habis</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-5 animate-in">
                    <a href="{{ route('katalog.index') }}" class="btn-view-all">
                        Lihat Semua Katalog <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i> Belum ada produk yang tersedia saat ini.
                </div>
            @endif
        </div>
    </section>

    {{-- ===================================================================
         TENTANG & LOKASI KAMI
    =================================================================== --}}
    <section class="about-section" id="tentang-kami">
        <div class="container">
            <h2 class="section-title animate-in">Tentang &amp; Lokasi Kami</h2>
            <p class="section-subtitle animate-in" style="transition-delay:60ms">Kenali kami lebih dekat dan temukan lokasi mitra budidaya kami</p>

            <div class="row g-4">
                {{-- Kiri: Tentang --}}
                <div class="col-lg-6">
                    <div class="about-card animate-in">
                        <div class="card-body p-4 d-flex flex-column justify-content-center h-100">
                            <h5 class="fw-bold mb-3" style="color:#111827;font-size:18px;">
                                <i class="bi bi-building me-2" style="color:#F5A623"></i>Athaya Fish Farm
                            </h5>
                            <p style="font-size:14px;color:#6B7280;line-height:1.7;margin-bottom:1rem;">
                                Athaya Fish Farm adalah platform e-commerce modern yang menghubungkan petani
                                budidaya ikan dengan konsumen yang membutuhkan ikan berkualitas tinggi. Kami menyediakan
                                solusi lengkap mulai dari penjualan ikan, layanan budidaya, hingga manajemen operasional
                                yang terintegrasi dengan teknologi terkini.
                            </p>
                            <p style="font-size:14px;color:#6B7280;line-height:1.7;margin-bottom:1rem;">
                                Athaya Fish Farm menyediakan fitur penawaran budidaya ikan yang memungkinkan
                                pelanggan mengajukan kebutuhan budidaya secara online dengan mengisi data
                                jenis ikan, jumlah kebutuhan, deskripsi tambahan, serta upload foto kondisi ikan
                                sebagai pendukung proses konsultasi budidaya.
                            </p>
                            <p style="font-size:14px;color:#6B7280;line-height:1.7;margin-bottom:1.5rem;">
                                Percayakan kebutuhan ikan Anda kepada kami dan rasakan perbedaannya!
                            </p>

                            <div>
                                <div class="about-info-item">
                                    <i class="bi bi-geo-alt-fill mt-1" style="color:#EF4444;flex-shrink:0"></i>
                                    <div>
                                        <div class="about-info-title">Lokasi Mitra</div>
                                        <div class="about-info-val">Koordinat: -6.2817560, 107.8764500</div>
                                    </div>
                                </div>
                                <div class="about-info-item">
                                    <i class="bi bi-clock-fill mt-1" style="color:#F5A623;flex-shrink:0"></i>
                                    <div>
                                        <div class="about-info-title">Jam Operasional</div>
                                        <div class="about-info-val">Senin – Sabtu, 08.00 – 17.00 WIB</div>
                                    </div>
                                </div>
                                <div class="about-info-item">
                                    <i class="bi bi-whatsapp mt-1" style="color:#25D366;flex-shrink:0"></i>
                                    <div>
                                        <div class="about-info-title">Hubungi Kami</div>
                                        <div class="about-info-val">Via WhatsApp / Formulir Budidaya</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kanan: Google Maps --}}
                <div class="col-lg-6">
                    <div class="about-card animate-in" style="transition-delay:80ms;overflow:hidden;">
                        <div style="padding:1rem 1.25rem;border-bottom:1px solid #E5E7EB;display:flex;align-items:center;justify-content:space-between;">
                            <span style="font-weight:700;font-size:14px;color:#111827;">
                                <i class="bi bi-pin-map-fill me-2" style="color:#EF4444"></i>Lokasi Mitra Kami
                            </span>
                            <a href="https://www.google.com/maps?q=-6.2817560,107.8764500"
                               target="_blank"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-box-arrow-up-right"></i> Buka di Maps
                            </a>
                        </div>
                        <div class="ratio" style="--bs-aspect-ratio: 65%;">
                            <iframe
                                src="https://maps.google.com/maps?q=-6.2817560,107.8764500&z=16&output=embed"
                                style="border:0; width:100%; height:100%;"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                title="Lokasi Mitra Athaya Fish Farm">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection