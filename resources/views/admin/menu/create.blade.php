@extends('layouts.app')

@section('title', 'Tambah Produk Baru')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h3 class="mb-0"><i class="bi bi-box-seam text-primary"></i> Tambah Produk Baru</h3>
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

                    <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Jenis Ikan --}}
                        <div class="mb-3">
                            <label for="jenis_ikan_id" class="form-label fw-bold">Jenis Ikan <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis_ikan_id') is-invalid @enderror" id="jenis_ikan_id" name="jenis_ikan_id" required>
                                <option value="">-- Pilih Jenis Ikan --</option>
                                @foreach($jenisIkan as $jenis)
                                    <option value="{{ $jenis->id }}" {{ old('jenis_ikan_id') == $jenis->id ? 'selected' : '' }}>
                                        {{ $jenis->nama_jenis }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis_ikan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nama Produk --}}
                        <div class="mb-3">
                            <label for="nama_produk" class="form-label fw-bold">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_produk') is-invalid @enderror"
                                   id="nama_produk" name="nama_produk" value="{{ old('nama_produk') }}" required>
                            @error('nama_produk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Foto --}}
                        <div class="mb-3">
                            <label for="gambar" class="form-label fw-bold">Foto Produk <span class="text-muted fw-normal">(Opsional)</span></label>
                            <input class="form-control @error('gambar') is-invalid @enderror" type="file"
                                   id="gambar" name="gambar" accept="image/*">
                            <div class="form-text">Format: JPG, JPEG, PNG, GIF. Maks 2MB.</div>
                            @error('gambar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Harga & Stok --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="harga_satuan" class="form-label fw-bold">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('harga_satuan') is-invalid @enderror"
                                       id="harga_satuan" name="harga_satuan" value="{{ old('harga_satuan') }}" min="0" required>
                                @error('harga_satuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="stok" class="form-label fw-bold">Stok Awal <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('stok') is-invalid @enderror"
                                       id="stok" name="stok" value="{{ old('stok', 0) }}" min="0" required>
                                @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Berat --}}
                        <div class="mb-3">
                            <label for="berat_gram" class="form-label fw-bold">Berat per Satuan (gram) <span class="text-muted fw-normal">(Opsional)</span></label>
                            <input type="number" class="form-control @error('berat_gram') is-invalid @enderror"
                                   id="berat_gram" name="berat_gram" value="{{ old('berat_gram') }}" min="0" placeholder="cth: 500">
                            @error('berat_gram')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label fw-bold">Deskripsi Produk</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4">{{ old('deskripsi') }}</textarea>
                        </div>

                        {{-- Tersedia --}}
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       id="tersedia" name="tersedia" {{ old('tersedia', true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="tersedia">Produk Tersedia di Katalog</label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save"></i> Simpan Produk</button>
                            <a href="{{ route('admin.menu.index') }}" class="btn btn-light border px-4"><i class="bi bi-x-circle"></i> Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
