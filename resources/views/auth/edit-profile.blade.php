@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-0">
                    <h4 class="mb-0">
                        <i class="bi bi-pencil"></i> Edit Profil
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3 text-center">
                            <div style="width: 100px; height: 100px; margin: 0 auto; border-radius: 50%; 
                                        overflow: hidden; background-color: var(--light-blue); 
                                        display: flex; align-items: center; justify-content: center;">
                                @if($user->foto_profil)
                                    <img src="{{ asset('uploads/profil/' . $user->foto_profil) }}" 
                                         alt="Foto Profil" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <i class="bi bi-person-circle" style="font-size: 4rem; color: var(--primary-blue);"></i>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="foto_profil" class="form-label">
                                <i class="bi bi-image"></i> Foto Profil
                            </label>
                            <input type="file" class="form-control @error('foto_profil') is-invalid @enderror" 
                                   id="foto_profil" name="foto_profil" accept="image/*">
                            @error('foto_profil')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Format: JPEG, PNG, JPG, GIF | Max 2MB</small>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="bi bi-person"></i> Nama Lengkap
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ $user->name }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                <i class="bi bi-envelope"></i> Email (tidak bisa diubah)
                            </label>
                            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="nomor_telepon" class="form-label">
                                <i class="bi bi-telephone"></i> Nomor Telepon
                            </label>
                            <input type="tel" class="form-control @error('nomor_telepon') is-invalid @enderror" 
                                   id="nomor_telepon" name="nomor_telepon" value="{{ $user->nomor_telepon }}" required>
                            @error('nomor_telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="alamat" class="form-label">
                                <i class="bi bi-geo-alt"></i> Alamat
                            </label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                      id="alamat" name="alamat" rows="4" required>{{ $user->alamat }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-check-circle"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
