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
                            <label for="jenis_ikan_name" class="form-label fw-bold">Jenis Ikan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('jenis_ikan_name') is-invalid @enderror" 
                                   id="jenis_ikan_name" name="jenis_ikan_name" list="jenisIkanList"
                                   value="{{ old('jenis_ikan_name') }}" placeholder="-- Pilih atau Ketik Jenis Ikan --" required autocomplete="off">
                            <datalist id="jenisIkanList">
                                @foreach($jenisIkan as $jenis)
                                    <option value="{{ $jenis->nama_jenis }}">
                                @endforeach
                            </datalist>
                            @error('jenis_ikan_name')
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
                        <div class="mb-4">
                            <label class="form-label fw-bold">Foto Produk <span class="text-muted fw-normal">(Opsional, maks. 5 foto)</span></label>
                            
                            {{-- Drop Zone --}}
                            <div id="dropZone" class="border-2 border-dashed rounded-3 p-4 text-center position-relative"
                                 style="border: 2px dashed #0EA5E9; cursor: pointer; background: #f8fdff; transition: all .2s; border-radius: 12px;">
                                <input type="file" id="fotoInput" name="gambar[]" multiple accept="image/*"
                                       class="position-absolute top-0 start-0 w-100 h-100 opacity-0"
                                       style="cursor: pointer; z-index: 2;">
                                <div id="dropPlaceholder">
                                    <i class="bi bi-cloud-arrow-up-fill mb-2 d-block" style="font-size: 2.5rem; color: #0EA5E9;"></i>
                                    <p class="mb-1 fw-semibold" style="color: #0EA5E9;">Klik atau drag foto ke sini</p>
                                    <p class="text-muted small mb-0">Format: JPG, JPEG, PNG, GIF — Maks. 2MB per foto, maks. 5 foto</p>
                                </div>
                            </div>
                            @error('gambar')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            @error('gambar.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror

                            {{-- Preview Grid --}}
                            <div id="previewGrid" class="row g-2 mt-2" style="display:none!important"></div>

                            {{-- Counter --}}
                            <div id="fotoCounter" class="text-muted small mt-2" style="display:none">
                                <i class="bi bi-images me-1"></i><span id="counterText">0</span> foto dipilih
                                <button type="button" id="clearAll" class="btn btn-link btn-sm text-danger p-0 ms-2" style="text-decoration:none;">Hapus semua</button>
                            </div>
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

@section('extra-css')
<style>
    #dropZone.dragover {
        background: #e0f6ff !important;
        border-color: #0284C7 !important;
    }
    .preview-thumb {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        aspect-ratio: 1;
        background: #f0f0f0;
        border: 1px solid #E5E7EB;
    }
    .preview-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .preview-thumb .remove-btn {
        position: absolute;
        top: 4px;
        right: 4px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: rgba(220,53,69,.85);
        color: #fff;
        border: none;
        font-size: 12px;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        transition: background .15s;
    }
    .preview-thumb .remove-btn:hover { background: #dc3545; }
</style>
@endsection

@section('extra-js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input     = document.getElementById('fotoInput');
    const dropZone  = document.getElementById('dropZone');
    const grid      = document.getElementById('previewGrid');
    const counter   = document.getElementById('fotoCounter');
    const counterTxt= document.getElementById('counterText');
    const clearBtn  = document.getElementById('clearAll');
    const MAX_FILES = 5;

    let selectedFiles = [];

    dropZone.addEventListener('dragover',  e => { e.preventDefault(); dropZone.classList.add('dragover'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        addFiles([...e.dataTransfer.files]);
    });

    input.addEventListener('change', () => {
        const files = [...input.files];
        input.value = '';
        addFiles(files);
    });

    clearBtn.addEventListener('click', () => { selectedFiles = []; renderPreviews(); });

    function addFiles(newFiles) {
        const imageFiles = newFiles.filter(f => f.type.startsWith('image/'));
        const remaining  = MAX_FILES - selectedFiles.length;
        if (remaining <= 0) {
            alert('Maksimal ' + MAX_FILES + ' foto yang dapat diunggah.');
            return;
        }
        selectedFiles = selectedFiles.concat(imageFiles.slice(0, remaining));
        if (imageFiles.length > remaining) {
            alert('Hanya ' + remaining + ' foto lagi yang dapat ditambahkan (maks. ' + MAX_FILES + ').');
        }
        renderPreviews();
    }

    function renderPreviews() {
        grid.innerHTML = '';

        if (selectedFiles.length === 0) {
            grid.style.setProperty('display', 'none', 'important');
            counter.style.display = 'none';
            syncInput([]);
            return;
        }

        grid.style.removeProperty('display');
        counter.style.display = '';
        counterTxt.textContent = selectedFiles.length;

        selectedFiles.forEach((file, idx) => {
            const col   = document.createElement('div');
            col.className = 'col-4 col-sm-3 col-md-2';

            const thumb = document.createElement('div');
            thumb.className = 'preview-thumb';

            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.onload = () => URL.revokeObjectURL(img.src);

            const rmBtn = document.createElement('button');
            rmBtn.type = 'button';
            rmBtn.className = 'remove-btn';
            rmBtn.innerHTML = '<i class="bi bi-x"></i>';
            rmBtn.title = 'Hapus foto ini';
            rmBtn.addEventListener('click', () => {
                selectedFiles.splice(idx, 1);
                renderPreviews();
            });

            thumb.appendChild(img);
            thumb.appendChild(rmBtn);
            col.appendChild(thumb);
            grid.appendChild(col);
        });

        syncInput(selectedFiles);
    }

    function syncInput(files) {
        const dt = new DataTransfer();
        files.forEach(f => dt.items.add(f));
        input.files = dt.files;
    }
});
</script>
@endsection
