<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Athaya Fish Farm</title>
    <meta name="description" content="Athaya Fish Farm — Platform e-commerce ikan segar berkualitas tinggi. Beli ikan segar langsung dari ahlinya dengan layanan budidaya profesional.">

    <!-- Google Fonts: Playfair Display (brand/hero) + Inter (UI) -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        /* ===================================================================
           CSS VARIABLES — Luxury Aquaculture Dark & Gold Theme
        =================================================================== */
        :root {
            /* Core palette */
            --dark-primary:   #0D1117;   /* Hero / page bg */
            --dark-surface:   #161B22;   /* Card on dark / Navbar */
            --gold:           #F5A623;   /* CTA, highlight, badge */
            --gold-hover:     #D4890F;   /* Hover state */
            --text-light:     #F0F6FC;   /* Heading on dark */
            --text-muted-dark:#8B949E;   /* Subtext on dark */
            --white-surface:  #FFFFFF;
            --border-subtle:  rgba(255,255,255,0.08);
            --light-bg:       #F8F9FA;   /* Feature section bg */
            --card-border:    #E5E7EB;
            --text-dark:      #111827;   /* Headings on light */
            --text-gray:      #6B7280;   /* Body on light */

            /* Legacy compat vars still used in some sections */
            --primary:        #F5A623;
            --primary-light:  #FEF3C7;
            --primary-pale:   #FFFBEB;
            --primary-dark:   #0D1117;
            --text-muted:     #6B7280;
            --bg-body:        #F8F9FA;
            --bg-card:        #FFFFFF;
            --border-color:   #E5E7EB;
            --shadow-sm:      0 1px 3px rgba(0,0,0,0.06);
            --shadow-md:      0 8px 24px rgba(0,0,0,0.10);
            --shadow-lg:      0 12px 36px rgba(0,0,0,0.15);
            --radius:         12px;
            --radius-lg:      16px;
            --transition:     all 0.25s ease;
        }

        /* ===== BASE ===== */
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
            color: var(--text-dark);
            font-size: 15px;
            line-height: 1.7;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        h1, h2, h3, h4, h5, h6 { line-height: 1.15; }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--dark-primary); }
        ::-webkit-scrollbar-thumb { background: var(--gold); border-radius: 4px; }

        /* ===================================================================
           NAVBAR
        =================================================================== */
        .navbar {
            background: rgba(13,17,23,0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-subtle);
            padding: 0.4rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .navbar-brand img {
            height: 40px;
            object-fit: contain;
            image-rendering: -webkit-optimize-contrast;
        }

        .nav-link {
            font-family: 'Inter', sans-serif;
            font-size: 14px !important;
            font-weight: 500 !important;
            color: var(--text-muted-dark) !important;
            padding: 0.45rem 0.85rem !important;
            border-radius: 8px;
            transition: color 200ms ease !important;
            display: flex;
            align-items: center;
            gap: 5px;
            position: relative;
        }

        .nav-link:hover {
            color: var(--gold) !important;
            background: transparent !important;
        }

        .nav-link.active {
            color: var(--gold) !important;
            background: transparent !important;
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0.85rem;
            right: 0.85rem;
            height: 2px;
            background: var(--gold);
            border-radius: 2px;
        }

        /* Navbar toggler */
        .navbar-toggler {
            border: 1.5px solid rgba(255,255,255,0.3);
            border-radius: 8px;
            padding: 4px 8px;
        }
        .navbar-toggler-icon { filter: brightness(10); }

        /* User badge pill */
        .user-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--gold);
            border-radius: 99px;
            padding: 5px 14px 5px 5px;
            cursor: pointer;
            transition: background 200ms ease, transform 200ms ease;
            text-decoration: none;
            border: none;
        }
        .user-badge:hover {
            background: var(--gold-hover);
            transform: translateY(-1px);
        }
        .user-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: rgba(0,0,0,0.25);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.75rem;
        }
        .user-name {
            color: #111827;
            font-weight: 700;
            font-size: 0.82rem;
        }

        /* Dropdown */
        .dropdown-menu {
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            padding: 0.5rem;
            min-width: 200px;
            background: #fff;
        }
        .dropdown-item {
            border-radius: 8px;
            padding: 0.55rem 0.9rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
        }
        .dropdown-item:hover {
            background: var(--primary-pale);
            color: var(--gold);
        }
        .dropdown-divider { border-color: var(--border-color); margin: 0.3rem 0; }

        /* Guest nav buttons */
        .nav-btn-register {
            background: var(--gold);
            color: #111827 !important;
            border-radius: 99px;
            padding: 0.35rem 1rem !important;
            font-weight: 600 !important;
            font-size: 13px !important;
            transition: background 200ms ease !important;
        }
        .nav-btn-register:hover {
            background: var(--gold-hover) !important;
            color: #111827 !important;
        }

        /* ===================================================================
           BUTTONS
        =================================================================== */
        .btn {
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 0.875rem;
            border-radius: 8px;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-primary {
            background: var(--gold);
            border: none;
            color: #111827;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(245,166,35,0.25);
        }
        .btn-primary:hover {
            background: var(--gold-hover);
            color: #111827;
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(245,166,35,0.35);
        }
        .btn-outline-primary {
            color: var(--gold);
            border: 1.5px solid var(--gold);
            background: transparent;
        }
        .btn-outline-primary:hover {
            background: var(--gold);
            border-color: var(--gold);
            color: #111827;
            transform: translateY(-1px);
        }
        .btn-outline-secondary {
            color: var(--text-gray);
            border: 1.5px solid var(--border-color);
        }
        .btn-outline-secondary:hover {
            background: var(--bg-body);
            border-color: var(--text-gray);
            color: var(--text-dark);
        }
        .btn-sm { padding: 0.35rem 0.8rem; font-size: 0.8rem; }
        .btn-lg { padding: 0.7rem 1.5rem; font-size: 0.95rem; }

        /* ===================================================================
           CARDS (general — light surface pages)
        =================================================================== */
        .card {
            background: var(--bg-card);
            border: 0.5px solid var(--border-color);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            transition: transform 250ms ease, box-shadow 250ms ease, border-color 250ms ease;
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
            border-color: rgba(245,166,35,0.4);
        }
        .card-header {
            background: #fff;
            border-bottom: 1px solid var(--border-color);
            border-radius: var(--radius) var(--radius) 0 0 !important;
            padding: 1rem 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.9rem;
        }
        .card-body { padding: 1.25rem; }

        /* Stat Cards (pemilik/admin dashboards) */
        .stat-card {
            border: none;
            border-radius: var(--radius-lg);
            overflow: hidden;
            position: relative;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: -20px; right: -20px;
            width: 80px; height: 80px;
            border-radius: 50%;
            background: rgba(255,255,255,.12);
        }
        .stat-card .icon-wrap {
            width: 48px; height: 48px;
            border-radius: 12px;
            background: rgba(255,255,255,.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem; color: #fff;
            margin-bottom: 0.75rem;
        }
        .stat-card .stat-label {
            font-size: 0.78rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: .5px;
            color: rgba(255,255,255,.75); margin-bottom: 0.3rem;
        }
        .stat-card .stat-value {
            font-size: 1.6rem; font-weight: 800;
            color: #fff; line-height: 1;
        }

        /* ===================================================================
           FORMS
        =================================================================== */
        .form-control,
        .form-select {
            border: 1px solid var(--border-color);
            border-radius: 10px;
            font-size: 0.875rem;
            padding: 12px 16px;
            color: var(--text-dark);
            background: #fff;
            transition: border-color 200ms ease, box-shadow 200ms ease;
            font-family: 'Inter', sans-serif;
        }
        .form-control:focus,
        .form-select:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(245,166,35,0.15);
            outline: none;
        }
        .form-control.is-invalid { border-color: #EF4444; }
        .invalid-feedback { font-size: 12px; color: #EF4444; }
        .form-label {
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            font-size: 13px;
            color: #374151;
            margin-bottom: 6px;
        }

        /* ===================================================================
           TABLES
        =================================================================== */
        .table { font-size: 0.875rem; }
        .table thead th {
            font-weight: 700; font-size: 0.78rem;
            text-transform: uppercase; letter-spacing: .5px;
            color: var(--text-dark);
            background: var(--primary-pale);
            border-bottom: 2px solid var(--border-color);
            padding: 0.85rem 1rem;
        }
        .table tbody td {
            padding: 0.85rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #F0F7FA;
            color: var(--text-dark);
        }
        .table-hover tbody tr:hover { background: var(--primary-pale); }

        /* ===================================================================
           BADGES
        =================================================================== */
        .badge {
            font-weight: 600; font-size: 0.75rem;
            padding: 0.35em 0.75em;
            border-radius: 6px; letter-spacing: .2px;
        }

        /* ===================================================================
           ALERTS
        =================================================================== */
        .alert {
            border: none; border-radius: var(--radius);
            font-size: 0.875rem; font-weight: 500;
            padding: 0.9rem 1.1rem;
        }
        .alert-success { background: #ECFDF5; color: #065F46; border-left: 4px solid #10B981; }
        .alert-danger  { background: #FEF2F2; color: #991B1B; border-left: 4px solid #EF4444; }
        .alert-info    { background: var(--primary-pale); color: #7B5A00; border-left: 4px solid var(--gold); }
        .alert-warning { background: #FFFBEB; color: #92400E; border-left: 4px solid #F59E0B; }

        /* ===================================================================
           PAGE HEADER (dashboard inner pages)
        =================================================================== */
        .page-header {
            background: #fff;
            border-radius: var(--radius-lg);
            padding: 1.5rem 1.75rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
        }
        .page-header h2,
        .page-header h4 { color: var(--text-dark); font-weight: 800; margin: 0; }
        .page-header p  { color: var(--text-gray); margin: 0.25rem 0 0; font-size: 0.875rem; }

        /* ===================================================================
           FOOTER
        =================================================================== */
        .footer {
            background: var(--dark-primary);
            color: var(--text-muted-dark);
            padding: 2rem 0;
            margin-top: auto;
            text-align: center;
            font-size: 13px;
            border-top: 1px solid var(--border-subtle);
        }
        .footer p { margin: 0.2rem 0; }
        .footer a  { color: var(--text-muted-dark); text-decoration: none; transition: color 200ms ease; }
        .footer a:hover { color: var(--gold); }
        .footer strong { color: var(--text-light); }

        /* ===================================================================
           WhatsApp Floating Button
        =================================================================== */
        .wa-float {
            position: fixed;
            width: 56px; height: 56px;
            bottom: 30px; right: 30px;
            border-radius: 50%;
            box-shadow: 0 4px 16px rgba(0,0,0,.25);
            z-index: 1000;
            display: flex; align-items: center; justify-content: center;
            text-decoration: none;
            transition: transform .3s ease, box-shadow .3s ease;
            background: transparent;
        }
        .wa-float:hover {
            transform: scale(1.1) translateY(-3px);
            box-shadow: 0 8px 28px rgba(37,211,102,.45);
        }

        /* ===================================================================
           PAGINATION
        =================================================================== */
        .pagination .page-link {
            border-radius: 8px !important;
            margin: 0 2px;
            color: var(--gold);
            border-color: var(--border-color);
            font-weight: 500; font-size: 0.85rem;
        }
        .pagination .page-item.active .page-link {
            background: var(--gold);
            border-color: var(--gold);
            color: #111827;
        }
        .pagination .page-link:hover {
            background: var(--primary-pale);
            border-color: var(--gold);
            color: var(--gold);
        }

        /* ===================================================================
           UTILITIES
        =================================================================== */
        .text-primary       { color: var(--gold) !important; }
        .text-primary-dark  { color: var(--dark-primary) !important; }
        .bg-primary-pale    { background: var(--primary-pale) !important; }
        .font-playfair      { font-family: 'Playfair Display', Georgia, serif; }

        /* ===================================================================
           SCROLL REVEAL
        =================================================================== */
        .animate-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 500ms ease, transform 500ms ease;
        }
        .animate-in.is-visible {
            opacity: 1;
            transform: none;
        }

        /* ===================================================================
           GLOBAL ANIMATIONS
        =================================================================== */
        @keyframes pageFadeInUp {
            0%   { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* ===================================================================
           SWEETALERT2 ROLE THEMES
        =================================================================== */

        /* -- SHARED -- */
        .swal2-popup { font-family: 'Inter', sans-serif !important; }

        /* -- CUSTOMER: Teal segar -- */
        .swal-customer.swal2-popup {
            border-top: 4px solid #4A90A4 !important;
            border-radius: 18px !important;
            box-shadow: 0 20px 60px rgba(74,144,164,.22) !important;
            padding: 2rem 1.75rem 1.5rem !important;
        }
        .swal-customer .swal2-title  { color: #1A4F5E !important; font-weight: 700 !important; font-size: 1.25rem !important; letter-spacing: -0.3px !important; }
        .swal-customer .swal2-html-container { color: #3A6B78 !important; font-size: 0.92rem !important; line-height: 1.6 !important; }
        .swal-customer .swal2-timer-progress-bar { background: linear-gradient(90deg,#4A90A4,#6DB8CC) !important; height: 3px !important; }
        .swal-customer .swal2-footer { border-top: 1px solid #D0EEF5 !important; color: #78909C !important; font-size: 0.8rem !important; }
        .swal-customer.swal2-toast { border-left: 4px solid #4A90A4 !important; border-radius: 12px !important; background: #EAF7FB !important; color: #1A4F5E !important; box-shadow: 0 6px 24px rgba(74,144,164,.18) !important; }

        /* -- ADMIN: Biru profesional -- */
        .swal-admin.swal2-popup {
            border-top: 4px solid #1565C0 !important;
            border-radius: 14px !important;
            box-shadow: 0 16px 48px rgba(21,101,192,.18) !important;
            padding: 2rem 1.75rem 1.5rem !important;
        }
        .swal-admin .swal2-title  { color: #0D47A1 !important; font-weight: 800 !important; font-size: 1.2rem !important; }
        .swal-admin .swal2-html-container { color: #1A2B3C !important; font-size: 0.9rem !important; line-height: 1.6 !important; }
        .swal-admin .swal2-timer-progress-bar { background: linear-gradient(90deg,#1565C0,#1E88E5) !important; height: 3px !important; }
        .swal-admin .swal2-footer { border-top: 1px solid #BBDEFB !important; color: #546E7A !important; font-size: 0.8rem !important; }
        .swal-admin.swal2-toast { border-left: 4px solid #1565C0 !important; border-radius: 10px !important; background: #E3F0FD !important; color: #0D47A1 !important; box-shadow: 0 6px 24px rgba(21,101,192,.15) !important; }

        /* -- PEMILIK: Emas premium -- */
        .swal-pemilik.swal2-popup {
            border-top: 4px solid #B8860B !important;
            border-radius: 18px !important;
            box-shadow: 0 20px 60px rgba(184,134,11,.18) !important;
            background: #FFFDF7 !important;
            padding: 2rem 1.75rem 1.5rem !important;
        }
        .swal-pemilik .swal2-title  { color: #7B5A00 !important; font-weight: 800 !important; font-size: 1.25rem !important; }
        .swal-pemilik .swal2-html-container { color: #5C4200 !important; font-size: 0.92rem !important; line-height: 1.6 !important; }
        .swal-pemilik .swal2-timer-progress-bar { background: linear-gradient(90deg,#B8860B,#D4A017) !important; height: 3px !important; }
        .swal-pemilik .swal2-footer { border-top: 1px solid #F0E6C0 !important; color: #9B7D3A !important; font-size: 0.8rem !important; }
        .swal-pemilik.swal2-toast { border-left: 4px solid #B8860B !important; border-radius: 12px !important; background: #FFF8E1 !important; color: #7B5A00 !important; box-shadow: 0 6px 24px rgba(184,134,11,.15) !important; }

        /* -- SweetAlert buttons -- */
        .swal2-confirm, .swal2-cancel {
            border-radius: 8px !important; font-weight: 600 !important;
            font-size: 0.875rem !important; padding: 0.55rem 1.4rem !important;
            letter-spacing: 0.1px !important; transition: all 0.2s ease !important;
            border: none !important;
        }
        .swal2-confirm:hover { filter: brightness(1.08) !important; transform: translateY(-1px) !important; box-shadow: 0 4px 14px rgba(0,0,0,.15) !important; }
        .swal2-icon { border-width: 2px !important; width: 4.5rem !important; height: 4.5rem !important; margin: 0 auto 1rem !important; }
        .swal2-icon .swal2-icon-content { font-size: 2.4rem !important; }

        /* ===================================================================
           PRODUCT CARD (shared across welcome & katalog)
        =================================================================== */
        .product-card {
            border-radius: 14px;
            overflow: hidden;
            border: 0.5px solid var(--border-color);
            background: #fff;
            box-shadow: var(--shadow-sm);
            transition: transform 250ms ease, box-shadow 250ms ease;
        }
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }
        .product-card:hover .product-img-inner {
            transform: scale(1.05);
        }
        .product-image {
            height: 200px;
            overflow: hidden;
            background: var(--light-bg);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .product-img-inner {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 300ms ease;
        }
        .product-price-badge {
            position: absolute;
            bottom: 10px; right: 10px;
            background: var(--dark-primary);
            color: var(--gold);
            font-family: 'Inter', monospace;
            font-weight: 700; font-size: 12px;
            border-radius: 8px;
            padding: 4px 10px;
        }

        /* ===================================================================
           RESPONSIVE
        =================================================================== */
        @media (max-width: 768px) {
            .navbar { padding: 0.5rem 1rem; }
            .page-header { padding: 1rem 1.25rem; }
            .navbar-collapse {
                background: rgba(13,17,23,0.98);
                border-radius: 12px;
                margin-top: 0.5rem;
                padding: 0.75rem;
                border: 1px solid var(--border-subtle);
            }
            .nav-link.active::after { display: none; }
        }
    </style>
    @yield('extra-css')
</head>

<body>
    <!-- ===== NAVBAR ===== -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Athaya Fish Farm Logo">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-1">

                    <!-- Katalog — semua bisa lihat -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('katalog.*') ? 'active' : '' }}" href="{{ route('katalog.index') }}">
                            <i class="bi bi-shop"></i> Katalog
                        </a>
                    </li>

                    @guest
                    <!-- Tentang Kami — semua bisa lihat -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}#tentang-kami">
                            <i class="bi bi-info-circle"></i> Tentang Kami
                        </a>
                    </li>
                    @endguest

                    @auth
                        {{-- Customer only --}}
                        @if(auth()->user()->isCustomer())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('cart') ? 'active' : '' }}" href="{{ route('cart') }}">
                                    <i class="bi bi-cart3"></i> Keranjang
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('pesanan.*') ? 'active' : '' }}" href="{{ route('pesanan.list') }}">
                                    <i class="bi bi-receipt"></i> Pesanan Saya
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('budidaya.*') ? 'active' : '' }}" href="{{ route('budidaya.create') }}">
                                    <i class="bi bi-droplet-half"></i> Budidaya
                                </a>
                            </li>
                        @endif

                        {{-- Pemilik only --}}
                        @if(auth()->user()->isPemilik())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('pemilik.sales-report') ? 'active' : '' }}" href="{{ route('pemilik.sales-report') }}">
                                    <i class="bi bi-bar-chart-line"></i> Laporan Penjualan
                                </a>
                            </li>
                        @endif

                        {{-- User Dropdown --}}
                        <li class="nav-item dropdown ms-1">
                            <a href="#" class="user-badge text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="user-avatar">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="user-name d-none d-md-inline">{{ Str::limit(auth()->user()->name, 14) }}</span>
                                <i class="bi bi-chevron-down" style="font-size:.65rem;color:#111827"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li class="px-3 py-2">
                                    <div style="font-size:.85rem;font-weight:700;color:#111827">
                                        {{ auth()->user()->name }}
                                    </div>
                                    <div class="text-muted" style="font-size:.75rem">{{ ucfirst(auth()->user()->role) }}</div>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('dashboard') }}">
                                        <i class="bi bi-speedometer2 text-primary"></i> Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-person-circle text-primary"></i> Edit Profil
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>

                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Masuk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-btn-register" href="{{ route('register') }}">
                                <i class="bi bi-person-plus"></i> Daftar
                            </a>
                        </li>
                    @endauth

                </ul>
            </div>
        </div>
    </nav>

    {{-- Flash sessions ditangani oleh SweetAlert2 di bawah --}}

    <!-- Main Content -->
    <main style="flex: 1 0 auto">
        @yield('content')
    </main>

    <!-- ===== FOOTER ===== -->
    <footer class="footer">
        <div class="container">
            <p><i class="bi bi-fish me-1" style="color:var(--gold)"></i> <strong>Athaya Fish Farm</strong></p>
            <p>Sistem Informasi E-Commerce Budidaya Ikan &copy; {{ date('Y') }}</p>
        </div>
    </footer>

    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/6289613130130?text=Halo,%20saya%20ingin%20bertanya%20seputar%20produk%20di%20Athaya%20Fish%20Farm"
        target="_blank" class="wa-float" title="Hubungi kami via WhatsApp">
        <svg viewBox="0 0 32 32" width="56" height="56" xmlns="http://www.w3.org/2000/svg">
            <path d="M16.002 0C7.165 0 0 7.164 0 16.002c0 2.91.776 5.645 2.153 8.01L0 32l8.17-2.147a15.908 15.908 0 0 0 7.832 2.046c8.835 0 16-7.163 16-16.001C32 7.164 24.837 0 16.002 0z" fill="#25D366" />
            <path d="M23.11 18.916c-.352-.176-2.086-1.028-2.408-1.145-.322-.117-.557-.176-.79.176-.234.353-.913 1.145-1.118 1.38-.205.234-.41.264-.76.088a9.833 9.833 0 0 1-2.884-1.782 10.84 10.84 0 0 1-2.001-2.49c-.206-.352-.022-.544.153-.72.158-.158.352-.41.528-.616.176-.205.234-.352.352-.587.117-.234.058-.438-.028-.614-.088-.176-.79-1.905-1.083-2.607-.285-.688-.576-.595-.79-.606-.205-.01-.44-.01-.674-.01-.235 0-.616.088-.938.44-.323.352-1.232 1.203-1.232 2.934 0 1.73 1.261 3.402 1.437 3.637.176.234 2.477 3.782 5.998 5.302.839.362 1.493.578 2.003.74.842.268 1.609.23 2.215.14.68-.102 2.086-.851 2.378-1.672.293-.82.293-1.523.205-1.67-.087-.148-.321-.236-.673-.412z" fill="#FFF" />
        </svg>
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ===================================================
        //   ROLE-BASED NOTIFICATION SYSTEM — SweetAlert2
        // ===================================================

        @auth
            const __ROLE__ = @json(auth()->user()->role ?? 'guest');
        @else
            const __ROLE__ = 'guest';
        @endauth

        // ─── ROLE THEMES ────────────────────────────────────
        const ROLE_THEME = {
            customer: {
                toastPosition: 'bottom-end',
                confirmColor: '#4A90A4',
                cancelColor: '#78909C',
                deleteColor: '#E53935',
                customClass: { popup: 'swal-customer', confirmButton: 'swal-btn-customer' },
                background: '#f0fafc',
                color: '#1A2B3C',
            },
            admin: {
                toastPosition: 'top-end',
                confirmColor: '#1565C0',
                cancelColor: '#546E7A',
                deleteColor: '#C62828',
                customClass: { popup: 'swal-admin', confirmButton: 'swal-btn-admin' },
                background: '#F5F7FA',
                color: '#0D1B2A',
            },
            pemilik: {
                toastPosition: 'top-start',
                confirmColor: '#B8860B',
                cancelColor: '#6B5C3E',
                deleteColor: '#8B0000',
                customClass: { popup: 'swal-pemilik', confirmButton: 'swal-btn-pemilik' },
                background: '#FFFDF7',
                color: '#3B2800',
            },
            guest: {
                toastPosition: 'bottom-end',
                confirmColor: '#4A90A4',
                cancelColor: '#78909C',
                deleteColor: '#E53935',
                customClass: {},
                background: '#fff',
                color: '#333',
            },
        };

        const theme = ROLE_THEME[__ROLE__] || ROLE_THEME['guest'];

        // ─── TOAST ─────────────────────────────────────────
        const Toast = Swal.mixin({
            toast: true,
            position: theme.toastPosition,
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            background: theme.background,
            color: theme.color,
            customClass: theme.customClass,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        // ─── POPUP HELPER ───────────────────────────────────
        function rolePopup(opts) {
            return Swal.fire(Object.assign({
                background: theme.background,
                color: theme.color,
                confirmButtonColor: theme.confirmColor,
                cancelButtonColor: theme.cancelColor,
                customClass: theme.customClass,
            }, opts));
        }

        // ─── PESAN ROLE-SPECIFIC ─────────────────────────────
        const ROLE_LABELS = {
            customer: { loginBtn: 'Mulai Belanja', logoutMsg: 'Sampai jumpa! Terima kasih sudah berbelanja.' },
            admin:    { loginBtn: 'Ke Dashboard Admin', logoutMsg: 'Sesi admin telah berakhir.' },
            pemilik:  { loginBtn: 'Lihat Laporan', logoutMsg: 'Sesi pemilik telah berakhir.' },
            guest:    { loginBtn: 'OK', logoutMsg: '' },
        };
        const labels = ROLE_LABELS[__ROLE__] || ROLE_LABELS['guest'];

        // ─── FLASH: SUCCESS ──────────────────────────────────
        @if(session('success'))
            Toast.fire({ icon: 'success', title: @json(session('success')) });
        @endif

        // ─── FLASH: ERROR ────────────────────────────────────
        @if(session('error'))
            Toast.fire({ icon: 'error', title: @json(session('error')), timer: 6000 });
        @endif

        // ─── FLASH: WARNING ──────────────────────────────────
        @if(session('warning'))
            Toast.fire({ icon: 'warning', title: @json(session('warning')), timer: 5000 });
        @endif

        // ─── FLASH: INFO ─────────────────────────────────────
        @if(session('info'))
            Toast.fire({ icon: 'info', title: @json(session('info')), timer: 5000 });
        @endif

        // ─── FLASH: LOGIN BERHASIL (ROLE-AWARE) ─────────────
        @if(session('login_success'))
            (() => {
                const roleConfigs = {
                    customer: { title: 'Selamat Datang', confirmButtonText: 'Mulai Belanja', footer: '<small>Nikmati produk segar Athaya Fish Farm</small>', backdrop: 'rgba(74,144,164,0.15)' },
                    admin:    { title: 'Panel Admin', confirmButtonText: 'Ke Dashboard', footer: '<small>Sistem berjalan normal</small>', backdrop: 'rgba(21,101,192,0.12)' },
                    pemilik:  { title: 'Selamat Datang, Pemilik', confirmButtonText: 'Lihat Laporan', footer: '<small>Pantau bisnis Anda hari ini</small>', backdrop: 'rgba(184,134,11,0.12)' },
                    guest:    { title: 'Selamat Datang', confirmButtonText: 'OK', footer: '', backdrop: true },
                };
                const rc = roleConfigs[__ROLE__] || roleConfigs['guest'];
                rolePopup(Object.assign({
                    icon: 'success',
                    text: @json(session('login_success')),
                    showConfirmButton: true,
                    timer: 5000,
                    timerProgressBar: true,
                }, rc));
            })();
        @endif

        // ─── FLASH: PAYMENT SUCCESS ──────────────────────────
        @if(session('payment_success'))
            rolePopup({
                icon: 'success',
                title: 'Pembayaran Berhasil',
                html: @json(session('payment_success')),
                showConfirmButton: true,
                confirmButtonText: '<i class="bi bi-box-seam me-1"></i> Lihat Pesanan Saya',
                showCancelButton: true,
                cancelButtonText: '<i class="bi bi-x-lg me-1"></i> Tutup',
                backdrop: 'rgba(16,185,129,0.12)',
                footer: '<small><i class="bi bi-envelope-check me-1"></i> Detail pesanan akan dikirim ke email Anda</small>',
            }).then((result) => {
                @if(session('payment_redirect'))
                    if (result.isConfirmed) {
                        window.location.href = @json(session('payment_redirect'));
                    }
                @endif
            });
        @endif

        // ─── FLASH: PAYMENT FAILED ───────────────────────────
        @if(session('payment_failed'))
            rolePopup({
                icon: 'error',
                title: 'Pembayaran Gagal',
                text: @json(session('payment_failed')),
                showConfirmButton: true,
                confirmButtonText: 'Coba Lagi',
                confirmButtonColor: theme.deleteColor,
                footer: '<small>Hubungi kami jika masalah berlanjut</small>',
            });
        @endif

        // ─── FLASH: VALIDATION ERRORS ───────────────────────
        @if($errors->any())
            (() => {
                const roleTitle = {
                    customer: 'Ada yang Perlu Diperbaiki',
                    admin:    'Validasi Gagal',
                    pemilik:  'Input Tidak Valid',
                    guest:    'Terjadi Kesalahan',
                };
                rolePopup({
                    icon: 'error',
                    title: roleTitle[__ROLE__] || 'Terjadi Kesalahan',
                    html: '<ul style="text-align:left;margin:0;padding-left:1.2rem;line-height:1.8">' +
                        @foreach($errors->all() as $error)
                            '<li>{{ addslashes($error) }}</li>' +
                        @endforeach
                        '</ul>',
                    showConfirmButton: true,
                    confirmButtonText: 'OK, Perbaiki',
                    confirmButtonColor: theme.deleteColor,
                });
            })();
        @endif

        // ─── KONFIRMASI HAPUS (ROLE-AWARE) ───────────────────
        document.addEventListener('DOMContentLoaded', function () {
            const deleteLabels = {
                customer: { title: 'Hapus item ini?', btn: 'Ya, Hapus', cancel: 'Batal' },
                admin:    { title: 'Konfirmasi Penghapusan', btn: 'Hapus Sekarang', cancel: 'Batalkan' },
                pemilik:  { title: 'Tindakan Tidak Dapat Diurungkan', btn: 'Ya, Hapus Data', cancel: 'Tidak Jadi' },
                guest:    { title: 'Yakin?', btn: 'Ya', cancel: 'Tidak' },
            };
            const dl = deleteLabels[__ROLE__] || deleteLabels['guest'];

            document.querySelectorAll('form.confirm-delete').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const msg   = form.dataset.message || 'Data yang dihapus tidak dapat dikembalikan.';
                    const title = form.dataset.title   || dl.title;
                    rolePopup({
                        icon: 'warning',
                        title: title,
                        text: msg,
                        showCancelButton: true,
                        confirmButtonColor: theme.deleteColor,
                        confirmButtonText: dl.btn,
                        cancelButtonText: dl.cancel,
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });
        });

        // ─── PAGE TRANSITION ANIMATIONS ─────────────────────
        document.addEventListener('DOMContentLoaded', function () {
            const animateElements = document.querySelectorAll('.card, .page-header, .table-responsive, .stat-card');
            animateElements.forEach((el, index) => {
                if (!el.closest('.card-body')) {
                    el.style.opacity = '0';
                    const delay = Math.min(index * 0.08, 0.5);
                    el.style.animation = `pageFadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards ${delay}s`;
                }
            });
        });

        // ─── SCROLL REVEAL (Intersection Observer) ───────────
        document.addEventListener('DOMContentLoaded', function () {
            const revealEls = document.querySelectorAll('.animate-in');
            if (!revealEls.length) return;
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12 });
            revealEls.forEach(el => observer.observe(el));
        });
    </script>
    @stack('scripts')
    @yield('extra-js')
</body>

</html>