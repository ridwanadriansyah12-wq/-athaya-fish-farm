@extends('layouts.app')

@section('title', 'Beranda')

@section('extra-css')
<style>
    /* ===================================================================
       WELCOME PAGE — Luxury Aquaculture Dark & Gold
    =================================================================== */

    /* ── HERO SLIDESHOW BACKGROUND ─────────────────────────────────── */
    .hero-section {
        position: relative;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        color: #fff;
        overflow: hidden;
    }

    /* Slideshow container behind everything */
    .hero-slideshow {
        position: absolute;
        inset: 0;
        z-index: 0;
    }

    /* Each slide */
    .hero-slide {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        opacity: 0;
        transition: opacity 1.4s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: opacity, transform;
        animation: kenBurns 8s ease-in-out infinite alternate;
    }
    .hero-slide.active {
        opacity: 1;
    }

    /* Ken Burns zoom effect per slide — stagger via nth-child */
    @keyframes kenBurns {
        0%   { transform: scale(1.00) translateX(0px) translateY(0px); }
        100% { transform: scale(1.10) translateX(-15px) translateY(-8px); }
    }
    .hero-slide:nth-child(2) { animation-direction: alternate-reverse; }
    .hero-slide:nth-child(3) { animation-delay: 1s; }
    .hero-slide:nth-child(4) { animation-direction: alternate-reverse; animation-delay: 0.5s; }
    .hero-slide:nth-child(5) { animation-delay: 1.5s; }

    /* Multi-layer gradient overlay for legibility */
    .hero-overlay {
        position: absolute;
        inset: 0;
        z-index: 1;
        background:
            linear-gradient(to bottom,  rgba(8,15,30,0.55) 0%, rgba(8,15,30,0.25) 40%, rgba(8,15,30,0.70) 100%),
            linear-gradient(to right,   rgba(8,15,30,0.75) 0%, rgba(8,15,30,0.10) 55%, transparent 100%);
    }

    /* Content sits above overlay */
    .hero-content {
        position: relative;
        z-index: 2;
        flex: 1;
        display: flex;
        align-items: center;
        padding: 6rem 0 5rem;
    }

    .hero-badge {
        display: inline-block;
        border: 1px solid #0EA5E9;
        color: #0EA5E9;
        background: rgba(14,165,233,0.10);
        border-radius: 99px;
        padding: 6px 18px;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 0.4px;
        margin-bottom: 1.25rem;
        backdrop-filter: blur(4px);
    }

    .hero-title {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: clamp(36px, 5.5vw, 64px);
        font-weight: 800;
        line-height: 1.10;
        color: #F0F6FC;
        margin-bottom: 1.25rem;
        text-shadow: 0 2px 20px rgba(0,0,0,0.5);
    }

    .hero-title .gold-text {
        color: #0EA5E9;
        text-shadow: 0 0 30px rgba(14,165,233,0.5);
    }

    .hero-subtitle {
        font-family: 'Inter', sans-serif;
        font-size: clamp(14px, 2vw, 17px);
        line-height: 1.75;
        color: rgba(240,246,252,0.80);
        max-width: 500px;
        margin-bottom: 2rem;
        text-shadow: 0 1px 8px rgba(0,0,0,0.4);
    }

    /* CTA Buttons */
    .btn-hero-primary {
        background: #0EA5E9;
        color: #ffffff;
        border: none;
        border-radius: 99px;
        padding: 14px 32px;
        font-size: 15px;
        font-weight: 700;
        font-family: 'Inter', sans-serif;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: transform 200ms ease, box-shadow 200ms ease, background 200ms ease;
        box-shadow: 0 4px 20px rgba(14,165,233,0.40);
    }
    .btn-hero-primary:hover {
        background: #0284C7;
        color: #ffffff;
        transform: scale(1.05);
        box-shadow: 0 8px 32px rgba(14,165,233,0.55);
    }

    .btn-hero-secondary {
        border: 1.5px solid rgba(255,255,255,0.40);
        color: #fff;
        background: rgba(255,255,255,0.08);
        backdrop-filter: blur(8px);
        border-radius: 99px;
        padding: 14px 32px;
        font-size: 15px;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: border-color 200ms ease, background 200ms ease, color 200ms ease;
    }
    .btn-hero-secondary:hover {
        border-color: #0EA5E9;
        color: #0EA5E9;
        background: rgba(14,165,233,0.12);
    }

    /* ── SLIDE INDICATORS ────────────────────────────────────────── */
    .hero-dots {
        position: absolute;
        bottom: 90px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 3;
        display: flex;
        gap: 10px;
        align-items: center;
    }
    .hero-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: rgba(255,255,255,0.40);
        border: none;
        cursor: pointer;
        padding: 0;
        transition: background 0.3s ease, transform 0.3s ease, width 0.3s ease;
        outline: none;
    }
    .hero-dot.active {
        background: #0EA5E9;
        width: 28px;
        border-radius: 99px;
        transform: none;
        box-shadow: 0 0 10px rgba(14,165,233,0.6);
    }

    /* ── SLIDE CAPTION STRIP ─────────────────────────────────────── */
    .hero-slide-caption {
        position: absolute;
        bottom: 60px;
        right: 2rem;
        z-index: 3;
        font-family: 'Inter', sans-serif;
        font-size: 12px;
        color: rgba(255,255,255,0.50);
        letter-spacing: 0.5px;
        text-align: right;
        transition: opacity 0.5s ease;
    }

    /* ── PROGRESS BAR ─────────────────────────────────────────────── */
    .hero-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        width: 0%;
        background: transparent;
        z-index: 3;
        transition: none;
        border-radius: 0 2px 2px 0;
    }
    .hero-progress.animating {
        transition: width linear;
    }

    /* Wave divider */
    .hero-wave {
        position: absolute;
        bottom: 0; left: 0;
        width: 100%; overflow: hidden;
        line-height: 0;
        z-index: 2;
    }
    .hero-wave svg {
        display: block;
        width: calc(100% + 1.3px);
        height: 70px;
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
        border-color: #0EA5E9;
    }

    .feature-icon {
        width: 48px; height: 48px;
        border-radius: 50%;
        background: #E0F2FE;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px;
        color: #0EA5E9;
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
        background: #0EA5E9;
        color: #ffffff;
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
    .btn-catalog-detail:hover { background: #0284C7; color: #ffffff; }

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
        border: 1.5px solid #0EA5E9;
        color: #0EA5E9;
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
        background: #0EA5E9;
        color: #ffffff;
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

    /* ── RESPONSIVE ─────────────────────────────────────────────── */
    @media (max-width: 991px) {
        .hero-content { padding: 5rem 0 4rem; }
        .hero-dots    { bottom: 80px; }
        .hero-slide-caption { display: none; }
    }
    @media (max-width: 767px) {
        .hero-section { min-height: 85vh; }
        .hero-content { padding: 4.5rem 0 4rem; text-align: center; }
        .hero-subtitle { margin-left: auto; margin-right: auto; }
        .hero-dots { bottom: 72px; }
    }
    @media (max-width: 575px) {
        .hero-section { min-height: 80vh; }
        .hero-content { padding: 4rem 0 3.5rem; }
        .btn-hero-primary, .btn-hero-secondary { width: 100%; justify-content: center; }
        .hero-dots { bottom: 64px; }
    }
</style>
@endsection

@section('content')

    {{-- ===================================================================
         HERO SECTION — Background Fish Slideshow
    =================================================================== --}}
    <div class="hero-section" id="hero-section">

        {{-- Background Slideshow --}}
        <div class="hero-slideshow" aria-hidden="true">
            <div class="hero-slide active"
                 style="background-image: url('{{ asset('images/hero-fish/fish-1.jpg') }}');"
                 data-caption="Ikan Cichlid Albino"></div>
            <div class="hero-slide"
                 style="background-image: url('{{ asset('images/hero-fish/fish-2.jpg') }}');"
                 data-caption="Ikan Cichlid Biru Emas"></div>
            <div class="hero-slide"
                 style="background-image: url('{{ asset('images/hero-fish/fish-3.jpg') }}');"
                 data-caption="Ikan Cichlid Pelangi"></div>
            <div class="hero-slide"
                 style="background-image: url('{{ asset('images/hero-fish/fish-4.jpg') }}');"
                 data-caption="Ikan Cichlid Kuning Kelabu"></div>
            <div class="hero-slide"
                 style="background-image: url('{{ asset('images/hero-fish/fish-5.jpg') }}');"
                 data-caption="Ikan Cichlid Biru Ungu"></div>
        </div>

        {{-- Gradient Overlay --}}
        <div class="hero-overlay"></div>

        {{-- Text Content --}}
        <div class="hero-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 col-xl-6 text-center text-md-start">
                        <div class="hero-badge animate-in">#1 E-Commerce Ikan Segar</div>
                        <h1 class="hero-title animate-in" style="transition-delay:80ms">
                            Athaya <span class="gold-text">Fish Farm</span>
                        </h1>
                        <p class="hero-subtitle animate-in" style="transition-delay:160ms">
                            Platform terpercaya untuk membeli ikan segar berkualitas tinggi dan layanan jasa budidaya
                            profesional secara langsung dari ahlinya.
                        </p>

                        <div class="d-flex flex-column flex-sm-row justify-content-center justify-content-md-start gap-3 animate-in" style="transition-delay:240ms">
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
                </div>
            </div>
        </div>

        {{-- Slide Dots Navigation --}}
        <div class="hero-dots" role="tablist" aria-label="Navigasi Slide">
            <button class="hero-dot active" data-slide="0" role="tab" aria-label="Slide 1" aria-selected="true"></button>
            <button class="hero-dot" data-slide="1" role="tab" aria-label="Slide 2" aria-selected="false"></button>
            <button class="hero-dot" data-slide="2" role="tab" aria-label="Slide 3" aria-selected="false"></button>
            <button class="hero-dot" data-slide="3" role="tab" aria-label="Slide 4" aria-selected="false"></button>
            <button class="hero-dot" data-slide="4" role="tab" aria-label="Slide 5" aria-selected="false"></button>
        </div>

        {{-- Slide Caption --}}
        <div class="hero-slide-caption" id="hero-caption">Ikan Cichlid Albino</div>

        {{-- Progress Bar --}}
        <div class="hero-progress" id="hero-progress"></div>

        {{-- Wave Divider --}}
        <div class="hero-wave">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C59.71,118,130.85,130.2,201.3,123.63,243.68,119.67,283.47,105.15,321.39,56.44Z"
                    fill="#F8F9FA"></path>
            </svg>
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
                                    @if($produk->first_image)
                                        <img src="{{ asset('storage/' . $produk->first_image) }}"
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
                                <i class="bi bi-building me-2" style="color:#0EA5E9"></i>Athaya Fish Farm
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
                                    <i class="bi bi-clock-fill mt-1" style="color:#0EA5E9;flex-shrink:0"></i>
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

@section('extra-js')
<script>
(function () {
    'use strict';

    /* ── Config ─────────────────────────────────────────────────── */
    const SLIDE_DURATION = 5000;   // ms between auto-slides
    const FADE_DURATION  = 1400;   // must match CSS transition (ms)

    /* ── DOM refs ───────────────────────────────────────────────── */
    const slides   = Array.from(document.querySelectorAll('.hero-slide'));
    const dots     = Array.from(document.querySelectorAll('.hero-dot'));
    const caption  = document.getElementById('hero-caption');
    const progress = document.getElementById('hero-progress');
    const hero     = document.getElementById('hero-section');

    if (!slides.length) return;  // guard: not on welcome page

    /* ── State ───────────────────────────────────────────────────── */
    let current   = 0;
    let timer     = null;
    let isPaused  = false;

    /* ── Core: go to slide N ─────────────────────────────────────── */
    function goTo(idx) {
        if (idx === current) return;

        // Remove active from current slide & dot
        slides[current].classList.remove('active');
        dots[current].classList.remove('active');
        dots[current].setAttribute('aria-selected', 'false');

        current = (idx + slides.length) % slides.length;

        // Activate new slide & dot
        slides[current].classList.add('active');
        dots[current].classList.add('active');
        dots[current].setAttribute('aria-selected', 'true');

        // Update caption
        if (caption) {
            caption.style.opacity = '0';
            setTimeout(() => {
                caption.textContent = slides[current].dataset.caption || '';
                caption.style.opacity = '1';
            }, 400);
        }

        // Restart progress bar
        restartProgress();
    }

    /* ── Progress bar animation ──────────────────────────────────── */
    function restartProgress() {
        if (!progress) return;
        // Snap to 0 without transition
        progress.classList.remove('animating');
        progress.style.width = '0%';
        // Force reflow so browser registers the reset
        void progress.offsetWidth;
        // Animate to 100% over SLIDE_DURATION
        progress.classList.add('animating');
        progress.style.transition = `width ${SLIDE_DURATION}ms linear`;
        progress.style.width = '100%';
    }

    /* ── Auto-slide timer ────────────────────────────────────────── */
    function startTimer() {
        clearInterval(timer);
        timer = setInterval(() => {
            if (!isPaused) goTo(current + 1);
        }, SLIDE_DURATION);
    }

    /* ── Dot click navigation ────────────────────────────────────── */
    dots.forEach(dot => {
        dot.addEventListener('click', () => {
            goTo(parseInt(dot.dataset.slide, 10));
            startTimer(); // reset interval on manual nav
        });
    });

    /* ── Keyboard navigation ─────────────────────────────────────── */
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight') { goTo(current + 1); startTimer(); }
        if (e.key === 'ArrowLeft')  { goTo(current - 1); startTimer(); }
    });

    /* ── Touch / Swipe support ───────────────────────────────────── */
    let touchStartX = 0;
    let touchStartY = 0;
    hero.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
        touchStartY = e.changedTouches[0].screenY;
    }, { passive: true });
    hero.addEventListener('touchend', (e) => {
        const dx = e.changedTouches[0].screenX - touchStartX;
        const dy = e.changedTouches[0].screenY - touchStartY;
        // Only trigger if horizontal swipe dominates
        if (Math.abs(dx) > Math.abs(dy) && Math.abs(dx) > 50) {
            if (dx < 0) goTo(current + 1);   // swipe left → next
            else        goTo(current - 1);   // swipe right → prev
            startTimer();
        }
    }, { passive: true });

    /* ── Pause on hover (desktop) ────────────────────────────────── */
    hero.addEventListener('mouseenter', () => { isPaused = true; });
    hero.addEventListener('mouseleave', () => { isPaused = false; });

    /* ── Pause when tab/window hidden (battery saving) ───────────── */
    document.addEventListener('visibilitychange', () => {
        isPaused = document.hidden;
        if (!document.hidden) restartProgress();
    });

    /* ── Init ────────────────────────────────────────────────────── */
    restartProgress();
    startTimer();

})();
</script>
@endsection