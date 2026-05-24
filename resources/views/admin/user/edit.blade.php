@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h3 class="mb-0"><i class="bi bi-person-lines-fill text-primary"></i> Edit User</h3>
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

                    <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="nomor_telepon" class="form-label fw-bold">Nomor Telepon</label>
                            <input type="tel" class="form-control" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon', $user->nomor_telepon) }}">
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label fw-bold">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3">{{ old('alamat', $user->alamat) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="role" class="form-label fw-bold">Role <span class="text-danger">*</span></label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="user" @if(old('role', $user->role) === 'user') selected @endif>Customer</option>
                                <option value="admin" @if(old('role', $user->role) === 'admin') selected @endif>Admin</option>
                                <option value="pemilik" @if(old('role', $user->role) === 'pemilik') selected @endif>Pemilik</option>
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save"></i> Update</button>
                            <a href="{{ route('admin.user.index') }}" class="btn btn-light border px-4"><i class="bi bi-x-circle"></i> Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
