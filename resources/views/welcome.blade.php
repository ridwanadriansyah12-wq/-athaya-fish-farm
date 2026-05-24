@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <div class="hero-section"
        style="background: linear-gradient(135deg, var(--primary-dark), #1A1D24); color: white; padding: 5rem 0; overflow: hidden; position: relative; margin-bottom: 3rem;">
        <!-- Dekorasi Wave -->
        <div style="position: absolute; bottom: 0; left: 0; width: 100%; overflow: hidden; line-height: 0;">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none"
                style="display: block; width: calc(100% + 1.3px); height: 50px;">
                <path
                    d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C59.71,118,130.85,130.2,201.3,123.63,243.68,119.67,283.47,105.15,321.39,56.44Z"
                    fill="#f8f9fa"></path>
            </svg>
        </div>

        <div class="container position-relative z-1">
            <div class="row align-items-center">
                <!-- Text Content -->
                <div class="col-lg-6 text-center text-lg-start mb-5 mb-lg-0">
                    <span class="badge bg-white text-primary mb-3 px-3 py-2 rounded-pill shadow-sm"
                        style="font-weight: 600; letter-spacing: 1px;">
                        #1 E-Commerce Ikan Segar
                    </span>
                    <h1 class="display-4 fw-bold mb-3" style="line-height: 1.2;">
                        Athaya <span style="color: var(--primary);">Fish Farm</span>
                    </h1>
                    <p class="lead mb-4" style="opacity: 0.9; font-size: 1.15rem;">
                        Platform terpercaya untuk membeli ikan segar berkualitas tinggi dan layanan jasa budidaya
                        profesional secara langsung dari ahlinya.
                    </p>

                    <div class="d-flex flex-column flex-sm-row justify-content-center justify-content-lg-start gap-3">
                        @auth
                            <a href="{{ route('katalog.index') }}" class="btn btn-warning btn-lg px-4 py-3 fw-bold shadow-sm"
                                style="border-radius: 50px; background-color: var(--primary); color: #121212; border: none;">
                                <i class="bi bi-shop me-2"></i> Mulai Belanja
                            </a>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-lg px-4 py-3 fw-bold"
                                style="border-radius: 50px; border-width: 2px;">
                                <i class="bi bi-speedometer2 me-2"></i> Ke Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-warning btn-lg px-4 py-3 fw-bold shadow-sm"
                                style="border-radius: 50px; background-color: var(--primary); color: #121212; border: none; transition: transform 0.2s;">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Masuk Sekarang
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4 py-3 fw-bold"
                                style="border-radius: 50px; border-width: 2px; transition: background 0.2s;">
                                <i class="bi bi-person-plus me-2"></i> Buat Akun Baru
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Image Illustration -->
                <div class="col-lg-6 d-none d-lg-block text-center position-relative">
                    <!-- Lingkaran Background -->
                    <div
                        style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 400px; height: 400px; background: rgba(255,255,255,0.1); border-radius: 50%; filter: blur(20px); z-index: 0;">
                    </div>
                    <!-- Gambar Ikan -->
                    <img src="https://images.unsplash.com/photo-1524704796725-9fc3044a58b2?q=80&w=1000&auto=format&fit=crop"
                        alt="Ikan Segar" class="img-fluid rounded-circle shadow-lg position-relative z-1"
                        style="width: 400px; height: 400px; object-fit: cover; border: 8px solid rgba(255,255,255,0.2); animation: float 6s ease-in-out infinite;">
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .btn-warning:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15) !important;
            filter: brightness(1.1);
        }

        .btn-outline-light:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateY(-3px);
        }
    </style>

    <div class="container">
        <div class="row mb-5">
            <div class="col-md-4 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i style="font-size: 3rem; color: var(--primary);"></i>
                        <h5 class="card-title mt-3">Katalog Lengkap</h5>
                        <p class="card-text">Berbagai jenis ikan berkualitas dengan harga terjangkau</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i style="font-size: 3rem; color: var(--primary-light);"></i>
                        <h5 class="card-title mt-3">Layanan Budidaya</h5>
                        <p class="card-text">Kami siap membudidayakan ikan Anda</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i style="font-size: 3rem; color: var(--primary-light);"></i>
                        <h5 class="card-title mt-3">Pembayaran Aman</h5>
                        <p class="card-text">Sistem pembayaran terintegrasi dengan Midtrans</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Katalog Section -->
        <div class="row mb-5">
            <div class="col-12 text-center mb-4">
                <h3 style="color: var(--primary-dark);"><i class="bi bi-shop"></i> Katalog Produk Terbaru</h3>
                <p class="text-muted">Pilih ikan segar berkualitas dari kolam kami</p>
            </div>

            @if(isset($katalog) && $katalog->count() > 0)
                <div class="row">
                    @foreach($katalog as $produk)
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card product-card h-100 shadow-sm border-0">
                                <div class="product-image"
                                    style="height: 200px; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa; border-radius: 8px 8px 0 0; overflow: hidden;">
                                    @if($produk->gambar)
                                        <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <i class="bi bi-fish text-muted" style="font-size: 4rem;"></i>
                                    @endif
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title fw-bold" style="color: var(--text-dark);">{{ $produk->nama_produk }}</h6>
                                    <p class="card-text text-muted small mb-2" style="font-weight: 500;">
                                        {{ $produk->jenisIkan->nama_jenis ?? 'Umum' }}</p>

                                    <p class="card-text mb-3">
                                        @if($produk->harga_satuan)
                                            <strong style="color: var(--primary-dark); font-size: 1.25rem;">Rp
                                                {{ number_format($produk->harga_satuan, 0, ',', '.') }}</strong>
                                        @endif
                                    </p>

                                    @if($produk->stok > 0)
                                        <small class="text-success mb-3">
                                            <i class="bi bi-check-circle"></i> Tersedia ({{ $produk->stok }})
                                        </small>
                                        <a href="{{ route('katalog.show', $produk) }}" class="btn btn-primary btn-sm mt-auto">
                                            <i class="bi bi-eye"></i> Lihat Detail
                                        </a>
                                    @else
                                        <small class="text-danger mb-3">
                                            <i class="bi bi-x-circle"></i> Stok Habis
                                        </small>
                                        <button class="btn btn-secondary btn-sm mt-auto" disabled>Stok Habis</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="col-12 text-center mt-4">
                    <a href="{{ route('katalog.index') }}" class="btn btn-outline-primary btn-lg rounded-pill px-4">
                        Lihat Semua Katalog <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            @else
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle"></i> Belum ada produk yang tersedia saat ini.
                    </div>
                </div>
            @endif
        </div>

        {{-- Section Tentang + Lokasi --}}
        <div class="row mb-5 mt-2">
            <div class="col-12 text-center mb-4">
                <h3 style="color: var(--primary-dark);">
                    <i class="bi bi-info-circle me-2"></i>Tentang & Lokasi Kami
                </h3>
                <p class="text-muted">Kenali kami lebih dekat dan temukan lokasi mitra budidaya kami</p>
            </div>

            {{-- Kiri: Tentang --}}
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4 d-flex flex-column justify-content-center">
                        <h5 class="fw-bold mb-3" style="color: var(--primary-dark);">
                            <i class="bi bi-building me-2"></i>Athaya Fish Farm
                        </h5>
                        <p class="text-muted mb-3">
                            Athaya Fish Farm adalah platform e-commerce modern yang menghubungkan petani
                            budidaya ikan dengan konsumen yang membutuhkan ikan berkualitas tinggi. Kami menyediakan
                            solusi lengkap mulai dari penjualan ikan, layanan budidaya, hingga manajemen operasional
                            yang terintegrasi dengan teknologi terkini.
                        </p>
                        <p class="text-muted mb-4">
                            Percayakan kebutuhan ikan Anda kepada kami dan rasakan perbedaannya!
                        </p>

                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-geo-alt-fill text-danger mt-1"></i>
                                <div>
                                    <div class="fw-semibold small">Lokasi Mitra</div>
                                    <div class="text-muted small">Koordinat: -6.2830821, 107.8763491</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-clock-fill text-primary mt-1"></i>
                                <div>
                                    <div class="fw-semibold small">Jam Operasional</div>
                                    <div class="text-muted small">Senin – Sabtu, 08.00 – 17.00 WIB</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-whatsapp text-success mt-1"></i>
                                <div>
                                    <div class="fw-semibold small">Hubungi Kami</div>
                                    <div class="text-muted small">Via WhatsApp / Formulir Budidaya</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kanan: Google Maps --}}
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                    <div class="card-header bg-white border-0 py-3 px-4">
                        <span class="fw-bold">
                            <i class="bi bi-pin-map-fill text-danger me-2"></i>Lokasi Mitra Kami
                        </span>
                        <a href="https://www.google.com/maps?q=-6.2830821,107.8763491"
                           target="_blank"
                           class="btn btn-outline-primary btn-sm float-end">
                            <i class="bi bi-box-arrow-up-right me-1"></i>Buka di Maps
                        </a>
                    </div>
                    <div class="ratio" style="--bs-aspect-ratio: 65%;">
                        <iframe
                            src="https://maps.google.com/maps?q=-6.2830821,107.8763491&z=16&output=embed"
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
@endsection