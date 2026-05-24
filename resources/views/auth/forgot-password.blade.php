@extends('layouts.app')

@section('title', 'Lupa Password')

@section('content')
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-5">
                <div class="card shadow-lg border-0" style="border-radius:16px;overflow:hidden;">

                    {{-- Header --}}
                    <div style="background:linear-gradient(135deg,#101216 0%,#1e2530 100%);padding:2rem 2rem 1.5rem;">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#D4AF37,#E8C34B);
                                        display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-shield-lock-fill" style="color:#101216;font-size:1.3rem;"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" style="color:#fff;">Lupa Password?</h5>
                                <small style="color:rgba(255,255,255,.55);">Athaya Fish Farm</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">

                        {{-- Info --}}
                        <div class="mb-4 p-3 rounded-3 d-flex gap-2 align-items-start"
                             style="background:#F0F7FF;border-left:4px solid #D4AF37;">
                            <i class="bi bi-info-circle-fill mt-1" style="color:#D4AF37;flex-shrink:0;"></i>
                            <span style="font-size:.875rem;color:#374151;">
                                Masukkan email yang terdaftar di sistem. Jika email ditemukan,
                                Anda bisa langsung membuat password baru.
                            </span>
                        </div>

                        {{-- Error --}}
                        @if ($errors->has('email'))
                            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert"
                                 style="border-radius:10px;">
                                <i class="bi bi-x-circle-fill me-2"></i>
                                {{ $errors->first('email') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('password.email') }}" method="POST" id="forgotForm">
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="bi bi-envelope me-1" style="color:#D4AF37;"></i> Alamat Email
                                </label>
                                <input
                                    type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="contoh@email.com"
                                    required
                                    autofocus
                                    style="border-radius:10px;padding:.65rem 1rem;"
                                >
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3"
                                    id="btnCek" style="border-radius:10px;">
                                <i class="bi bi-search me-1"></i> Cek Email & Lanjutkan
                            </button>
                        </form>

                        <div class="text-center">
                            <a href="{{ route('login') }}" class="text-decoration-none"
                               style="font-size:.875rem;color:#D4AF37;font-weight:600;">
                                <i class="bi bi-arrow-left me-1"></i> Kembali ke Login
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('forgotForm').addEventListener('submit', function () {
            const btn = document.getElementById('btnCek');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memeriksa...';
        });
    </script>
    @endpush
@endsection
