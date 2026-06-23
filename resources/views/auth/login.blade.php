@extends('layouts.app')

@section('title', 'Masuk')

@section('extra-css')
<style>
    .auth-page {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 1rem;
        background: #F8F9FA;
    }
    .auth-card {
        background: #fff;
        border-radius: 20px;
        border: 0.5px solid #E5E7EB;
        box-shadow: 0 8px 32px rgba(0,0,0,0.08);
        overflow: hidden;
        width: 100%;
        max-width: 440px;
    }
    .auth-card-header {
        background: #0D1117;
        padding: 2rem 2rem 1.5rem;
        text-align: center;
    }
    .auth-brand {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 22px;
        font-weight: 700;
        color: #F0F6FC;
        margin-bottom: 0.25rem;
    }
    .auth-brand span { color: #0EA5E9; }
    .auth-tagline { font-size: 13px; color: #8B949E; }
    .auth-card-body { padding: 2rem; }
    .auth-title {
        font-size: 20px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 1.5rem;
        text-align: center;
    }
    .auth-divider {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 1.25rem 0;
        color: #9CA3AF;
        font-size: 13px;
    }
    .auth-divider::before,
    .auth-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #E5E7EB;
    }
    .btn-google {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        border: 1.5px solid #E5E7EB;
        border-radius: 10px;
        padding: 11px;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        background: #fff;
        text-decoration: none;
        transition: border-color 200ms ease, background 200ms ease;
    }
    .btn-google:hover {
        border-color: #0EA5E9;
        background: #F0F9FF;
        color: #374151;
    }
    .btn-submit {
        display: block;
        width: 100%;
        background: #0EA5E9;
        color: #111827;
        border: none;
        border-radius: 10px;
        padding: 13px;
        font-size: 15px;
        font-weight: 700;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: background 200ms ease, transform 200ms ease;
    }
    .btn-submit:hover {
        background: #0284C7;
        transform: translateY(-1px);
    }
    .auth-link {
        color: #0EA5E9;
        font-weight: 600;
        text-decoration: none;
        transition: color 200ms ease;
    }
    .auth-link:hover { color: #0284C7; text-decoration: underline; }
</style>
@endsection

@section('content')
<div class="auth-page">
    <div class="auth-card animate-in">
        {{-- Header --}}
        <div class="auth-card-header">
            <div class="auth-brand">Athaya <span>Fish Farm</span></div>
            <p class="auth-tagline">Platform Ikan Segar Berkualitas Tinggi</p>
        </div>

        {{-- Body --}}
        <div class="auth-card-body">
            <h2 class="auth-title">Masuk ke Akun</h2>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('login.store') }}" method="POST" autocomplete="off">
                @csrf

                {{-- Dummy fields — mencegah browser autofill (Chrome/Firefox) --}}
                <input type="text" name="_dummy_user" style="display:none;" tabindex="-1" aria-hidden="true">
                <input type="password" name="_dummy_pass" style="display:none;" tabindex="-1" aria-hidden="true">

                <div class="mb-4">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope"></i> Email
                    </label>
                    <input type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        id="email" name="email"
                        value="{{ old('email') }}"
                        required autofocus autocomplete="off"
                        readonly onfocus="this.removeAttribute('readonly')">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="password" class="form-label mb-0">
                            <i class="bi bi-key"></i> Password
                        </label>
                        <a href="{{ route('password.request') }}" class="auth-link" style="font-size:12px;">
                            Lupa Password?
                        </a>
                    </div>
                    <input type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        id="password" name="password"
                        required autocomplete="new-password"
                        readonly onfocus="this.removeAttribute('readonly')">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember" style="font-size:14px;color:#374151;">
                        Ingat saya
                    </label>
                </div>

                <button type="submit" class="btn-submit mb-3">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                </button>
            </form>

            <div class="auth-divider">ATAU</div>

            <a href="{{ route('auth.google') }}" class="btn-google mb-4">
                <i class="bi bi-google" style="color:#EA4335"></i> Masuk dengan Google
            </a>

            <p class="text-center mb-0" style="font-size:14px;color:#6B7280;">
                Belum punya akun?
                <a href="{{ route('register') }}" class="auth-link">Daftar di sini</a>
            </p>
        </div>
    </div>
</div>
@endsection