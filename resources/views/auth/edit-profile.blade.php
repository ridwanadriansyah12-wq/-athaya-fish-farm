@extends('layouts.app')

@section('title', 'Edit Profil')

@section('extra-css')
<style>
    .profile-card {
        background: #fff;
        border-radius: 16px;
        border: 0.5px solid #E5E7EB;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .profile-card-header {
        background: #0D1117;
        padding: 1.5rem;
        color: #F0F6FC;
        border-bottom: 1px solid rgba(255,255,255,0.08);
    }
    .profile-card-header h4 { margin: 0; font-weight: 700; font-size: 18px; }
    .profile-card-header i { color: #F5A623; margin-right: 8px; }
    .avatar-wrapper {
        width: 100px; height: 100px;
        margin: 0 auto;
        border-radius: 50%;
        overflow: hidden;
        background-color: #F8F9FA;
        display: flex; align-items: center; justify-content: center;
        border: 2px solid #F5A623;
        box-shadow: 0 4px 12px rgba(245,166,35,0.2);
    }
    .avatar-wrapper img { width: 100%; height: 100%; object-fit: cover; }
    .avatar-wrapper i { font-size: 4rem; color: #D4890F; }
    .btn-submit {
        display: block; width: 100%;
        background: #F5A623; color: #111827;
        border: none; border-radius: 10px;
        padding: 13px; font-size: 15px; font-weight: 700;
        transition: background 200ms ease, transform 200ms ease;
    }
    .btn-submit:hover {
        background: #D4890F; transform: translateY(-1px);
    }
    #charCount.text-success { color: #10B981 !important; }
    #charCount.text-danger  { color: #EF4444 !important; }
</style>
@endsection

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="profile-card animate-in">
                <div class="profile-card-header">
                    <h4><i class="bi bi-pencil"></i> Edit Profil</h4>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4 text-center">
                            <div class="avatar-wrapper mb-3">
                                @if($user->foto_profil)
                                    <img src="{{ asset('uploads/profil/' . $user->foto_profil) }}" alt="Foto Profil">
                                @else
                                    <i class="bi bi-person-fill"></i>
                                @endif
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="foto_profil" class="form-label">
                                <i class="bi bi-image" style="color:#F5A623"></i> Foto Profil
                            </label>
                            <input type="file" class="form-control @error('foto_profil') is-invalid @enderror"
                                   id="foto_profil" name="foto_profil" accept="image/*">
                            @error('foto_profil')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted" style="font-size:12px;">Format: JPEG, PNG, JPG, GIF | Max 2MB</small>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="bi bi-person" style="color:#F5A623"></i> Nama Lengkap
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ $user->name }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                <i class="bi bi-envelope" style="color:#F5A623"></i> Email <span class="text-muted">(tidak bisa diubah)</span>
                            </label>
                            <input type="email" class="form-control" value="{{ $user->email }}" disabled style="background:#F3F4F6">
                        </div>

                        <div class="mb-3">
                            <label for="nomor_telepon" class="form-label">
                                <i class="bi bi-telephone" style="color:#F5A623"></i> Nomor Telepon
                            </label>
                            <input type="tel" class="form-control @error('nomor_telepon') is-invalid @enderror"
                                   id="nomor_telepon" name="nomor_telepon" value="{{ $user->nomor_telepon }}"
                                   pattern="[0-9]+" title="Nomor telepon hanya boleh berisi angka"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                            <small class="text-muted" style="font-size:12px;">Hanya berupa angka (tidak boleh huruf)</small>
                            @error('nomor_telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="alamat" class="form-label">
                                <i class="bi bi-geo-alt" style="color:#F5A623"></i> Alamat
                            </label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror"
                                      id="alamat" name="alamat" rows="4" minlength="30" maxlength="500" required>{{ $user->alamat }}</textarea>
                            <small class="text-muted" style="font-size:12px;">
                                Minimal harus diisi 30 karakter. (<span id="charCount" class="text-danger fw-bold">0</span> karakter)
                            </small>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn-submit">
                            <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
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
            updateCharCount();
        }
    });
</script>
@endpush
