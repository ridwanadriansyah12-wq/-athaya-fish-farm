@extends('layouts.app')

@section('title', 'Daftar')

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
        max-width: 520px;
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
    .auth-brand span { color: #F5A623; }
    .auth-tagline { font-size: 13px; color: #8B949E; }
    .auth-card-body { padding: 2rem; }
    .auth-title {
        font-size: 20px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 1.5rem;
        text-align: center;
    }
    .btn-submit {
        display: block;
        width: 100%;
        background: #F5A623;
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
        background: #D4890F;
        transform: translateY(-1px);
    }
    .auth-link {
        color: #F5A623;
        font-weight: 600;
        text-decoration: none;
        transition: color 200ms ease;
    }
    .auth-link:hover { color: #D4890F; text-decoration: underline; }
    #charCount.text-success { color: #10B981 !important; }
    #charCount.text-danger  { color: #EF4444 !important; }
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
            <h2 class="auth-title">Buat Akun Baru</h2>

            <form action="{{ route('register.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">
                        <i class="bi bi-person"></i> Nama Lengkap
                    </label>
                    <input type="text"
                        class="form-control @error('name') is-invalid @enderror"
                        id="name" name="name"
                        value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope"></i> Email
                    </label>
                    <input type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        id="email" name="email"
                        value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="nomor_telepon" class="form-label">
                        <i class="bi bi-telephone"></i> Nomor Telepon
                    </label>
                    <input type="tel"
                        class="form-control @error('nomor_telepon') is-invalid @enderror"
                        id="nomor_telepon" name="nomor_telepon"
                        value="{{ old('nomor_telepon') }}"
                        pattern="[0-9]+"
                        title="Nomor telepon hanya boleh berisi angka"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                    <small class="text-muted" style="font-size:12px;">Hanya berupa angka (tidak boleh huruf)</small>
                    @error('nomor_telepon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label">
                        <i class="bi bi-geo-alt"></i> Alamat
                    </label>
                    <textarea
                        class="form-control @error('alamat') is-invalid @enderror"
                        id="alamat" name="alamat"
                        rows="3" minlength="30" maxlength="500" required>{{ old('alamat') }}</textarea>
                    <small class="text-muted" style="font-size:12px;">
                        Minimal harus diisi 30 karakter.
                        (<span id="charCount" class="text-danger fw-bold">0</span> karakter)
                    </small>
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="bi bi-key"></i> Password
                    </label>
                    <input type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        id="password" name="password"
                        pattern="(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#])[a-zA-Z0-9!@#]{8,}"
                        title="Minimal 8 karakter, terdiri dari angka, huruf, dan simbol (!, @, #)" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted" style="font-size:12px;">Minimal 8 karakter (harus terdiri dari angka, huruf, dan simbol !, @, #)</small>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">
                        <i class="bi bi-key"></i> Konfirmasi Password
                    </label>
                    <input type="password"
                        class="form-control"
                        id="password_confirmation" name="password_confirmation" required>
                </div>

                <button type="submit" class="btn-submit mb-3">
                    <i class="bi bi-check-circle me-1"></i> Daftar Sekarang
                </button>
            </form>

            <p class="text-center mb-0" style="font-size:14px;color:#6B7280;">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="auth-link">Login di sini</a>
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alamatInput = document.getElementById('alamat');
        const charCountSpan = document.getElementById('charCount');

        function updateCharCount() {
            const count = alamatInput.value.length;
            charCountSpan.textContent = count;
            if (count >= 30) {
                charCountSpan.className = 'text-success fw-bold';
            } else {
                charCountSpan.className = 'text-danger fw-bold';
            }
        }

        if (alamatInput && charCountSpan) {
            alamatInput.addEventListener('input', updateCharCount);
            // Run on load
            updateCharCount();
        }
    });
</script>
@endpush