@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-5">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-light border-0">
                        <h4 class="mb-0">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </h4>
                    </div>
                    <div class="card-body p-5">
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
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                    name="email" value="{{ old('email') }}" required autofocus
                                    autocomplete="off"
                                    readonly
                                    onfocus="this.removeAttribute('readonly')">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label for="password" class="form-label mb-0">
                                        <i class="bi bi-key"></i> Password
                                    </label>
                                    <a href="{{ route('password.request') }}"
                                        class="text-decoration-none"
                                        style="font-size:.8rem;color:#D4AF37;font-weight:600;">
                                        Lupa Password?
                                    </a>
                                </div>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" required
                                    autocomplete="new-password"
                                    readonly
                                    onfocus="this.removeAttribute('readonly')">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Ingat saya
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                                <i class="bi bi-key"></i> Login
                            </button>
                        </form>

                        <div class="d-flex align-items-center mb-3">
                            <hr class="flex-grow-1">
                            <span class="px-3 text-muted">ATAU</span>
                            <hr class="flex-grow-1">
                        </div>

                        <a href="{{ route('auth.google') }}" class="btn btn-outline-danger btn-lg w-100 mb-4">
                            <i class="bi bi-google"></i> Login dengan Google
                        </a>

                        <p class="text-center mb-0">
                            Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none">Daftar di
                                sini</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection