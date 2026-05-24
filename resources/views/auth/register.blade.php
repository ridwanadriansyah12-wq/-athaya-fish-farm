@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-light border-0">
                        <h4 class="mb-0">
                            <i class="bi bi-person-plus"></i> Daftar Akun Baru
                        </h4>
                    </div>
                    <div class="card-body p-5">
                        <form action="{{ route('register.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="bi bi-person"></i> Nama Lengkap
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope"></i> Email
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                    name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="nomor_telepon" class="form-label">
                                    <i class="bi bi-telephone"></i> Nomor Telepon
                                </label>
                                <input type="tel" class="form-control @error('nomor_telepon') is-invalid @enderror"
                                    id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon') }}" required>
                                @error('nomor_telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="alamat" class="form-label">
                                    <i class="bi bi-geo-alt"></i> Alamat
                                </label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat"
                                    name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>



                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-key"></i> Password
                                </label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Minimal 8 karakter</small>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">
                                    <i class="bi bi-key"></i> Konfirmasi Password
                                </label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" required>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                                <i class="bi bi-check-circle"></i> Daftar
                            </button>
                        </form>

                        <p class="text-center mb-0">
                            Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none">Login di sini</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection