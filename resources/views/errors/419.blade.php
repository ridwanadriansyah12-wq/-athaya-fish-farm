@extends('layouts.app')

@section('title', 'Sesi Kedaluwarsa — 419')

@section('content')
<div class="container py-5 text-center" style="min-height: 65vh; display: flex; align-items: center; justify-content: center;">
    <div class="card border-0 shadow-sm p-4 p-md-5 rounded-4" style="max-width: 480px; width: 100%;">
        <div class="mb-3">
            <span class="d-inline-flex align-items-center justify-content-center rounded-circle"
                  style="width: 70px; height: 70px; background: rgba(245, 158, 11, 0.12); color: #F59E0B; font-size: 32px;">
                <i class="bi bi-clock-history"></i>
            </span>
        </div>
        <h3 class="fw-bold mb-2" style="color: #111827;">Sesi Telah Kedaluwarsa</h3>
        <p class="text-muted mb-4" style="font-size: 14px;">
            Halaman ini telah diam cukup lama demi keamanan akun Anda. Silakan muat ulang halaman untuk melanjutkan aktivitas Anda.
        </p>
        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
            <button onclick="window.location.reload()" class="btn btn-primary px-4 py-2 rounded-3 fw-semibold">
                <i class="bi bi-arrow-clockwise me-1"></i> Muat Ulang Halaman
            </button>
            <a href="{{ url('/') }}" class="btn btn-outline-secondary px-4 py-2 rounded-3 fw-semibold">
                <i class="bi bi-house me-1"></i> Ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
