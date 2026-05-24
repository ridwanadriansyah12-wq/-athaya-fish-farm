@extends('layouts.app')

@section('title', 'Tambah User Baru')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h3 class="mb-0"><i class="bi bi-person-plus text-primary"></i> Tambah User Baru</h3>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('admin.user.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required minlength="8">
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-bold">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required minlength="8">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="nomor_telepon" class="form-label fw-bold">Nomor Telepon</label>
                            <input type="tel" class="form-control" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon') }}">
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label fw-bold">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3">{{ old('alamat') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="role" class="form-label fw-bold">Role <span class="text-danger">*</span></label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="" disabled selected>-- Pilih Role --</option>
                                <option value="user" @if(old('role') == 'user') selected @endif>Customer</option>
                                <option value="admin" @if(old('role') == 'admin') selected @endif>Admin</option>
                                <option value="pemilik" @if(old('role') == 'pemilik') selected @endif>Pemilik</option>
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save"></i> Simpan</button>
                            <a href="{{ route('admin.user.index') }}" class="btn btn-light border px-4"><i class="bi bi-x-circle"></i> Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
