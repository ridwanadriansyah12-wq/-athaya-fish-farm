@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="h4 font-weight-bold">Edit Jenis Ikan</h2>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('admin.ikan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Validasi Gagal!</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Form Card -->
    <div class="card">
        <div class="card-body p-4">
            <form action="{{ route('admin.ikan.update', $ikan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nama Ikan -->
                <div class="mb-3">
                    <label for="nama_ikan" class="form-label font-weight-bold">Nama Ikan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('Nama_Ikan') is-invalid @enderror" 
                           id="nama_ikan" name="Nama_Ikan" value="{{ old('Nama_Ikan', $ikan->Nama_Ikan) }}" 
                           placeholder="Contoh: Ikan Lele" required>
                    @error('Nama_Ikan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="mb-3">
                    <label for="deskripsi" class="form-label font-weight-bold">Deskripsi</label>
                    <textarea class="form-control @error('Deskripsi') is-invalid @enderror" 
                              id="deskripsi" name="Deskripsi" rows="3" 
                              placeholder="Masukan deskripsi jenis ikan...">{{ old('Deskripsi', $ikan->Deskripsi) }}</textarea>
                    @error('Deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Suhu Ideal Min -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="suhu_ideal_min" class="form-label font-weight-bold">Suhu Ideal Min (°C)</label>
                        <input type="number" class="form-control @error('Suhu_Ideal_Min') is-invalid @enderror" 
                               id="suhu_ideal_min" name="Suhu_Ideal_Min" value="{{ old('Suhu_Ideal_Min', $ikan->Suhu_Ideal_Min) }}" 
                               placeholder="Contoh: 24" step="0.1">
                        @error('Suhu_Ideal_Min')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Suhu Ideal Max -->
                    <div class="col-md-6">
                        <label for="suhu_ideal_max" class="form-label font-weight-bold">Suhu Ideal Max (°C)</label>
                        <input type="number" class="form-control @error('Suhu_Ideal_Max') is-invalid @enderror" 
                               id="suhu_ideal_max" name="Suhu_Ideal_Max" value="{{ old('Suhu_Ideal_Max', $ikan->Suhu_Ideal_Max) }}" 
                               placeholder="Contoh: 28" step="0.1">
                        @error('Suhu_Ideal_Max')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- pH Ideal Min -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="ph_ideal_min" class="form-label font-weight-bold">pH Ideal Min</label>
                        <input type="number" class="form-control @error('pH_Ideal_Min') is-invalid @enderror" 
                               id="ph_ideal_min" name="pH_Ideal_Min" value="{{ old('pH_Ideal_Min', $ikan->pH_Ideal_Min) }}" 
                               placeholder="Contoh: 6.5" step="0.1">
                        @error('pH_Ideal_Min')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- pH Ideal Max -->
                    <div class="col-md-6">
                        <label for="ph_ideal_max" class="form-label font-weight-bold">pH Ideal Max</label>
                        <input type="number" class="form-control @error('pH_Ideal_Max') is-invalid @enderror" 
                               id="ph_ideal_max" name="pH_Ideal_Max" value="{{ old('pH_Ideal_Max', $ikan->pH_Ideal_Max) }}" 
                               placeholder="Contoh: 7.5" step="0.1">
                        @error('pH_Ideal_Max')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-end">
                    <a href="{{ route('admin.ikan.index') }}" class="btn btn-light">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
