@extends('layouts.app')

@section('title', 'Form Penawaran Budidaya')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row mb-4">
        <div class="col-md-8 offset-md-2 text-center">
            <h2 class="fw-bold" style="color: var(--dark-blue);"><i class="bi bi-droplet-half"></i> Form Penawaran Budidaya</h2>
            <p class="text-muted mt-2">Platform kolaborasi budidaya ikan mandiri Athaya Fish Farm. Masukkan detail penawaran teknis Anda di bawah ini untuk memulai proses validasi sistem.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> Terdapat kesalahan pada input Anda:
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header p-4 text-center" style="background: linear-gradient(135deg, var(--light-blue) 0%, white 100%); border-bottom: 1px solid rgba(135, 206, 235, 0.3);">
                    <h5 class="mb-0" style="color: var(--dark-blue); font-weight: 700;">Detail Penawaran</h5>
                </div>
                <div class="card-body p-5">
                    <form action="{{ route('budidaya.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Jenis Ikan --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: var(--text-dark);">Jenis Ikan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0" style="border-color: #ced4da;"><i class="bi bi-water text-muted"></i></span>
                                <input type="text" name="jenis_ikan" class="form-control border-start-0 ps-0 @error('jenis_ikan') is-invalid @enderror"
                                       placeholder="Contoh: Nila, Lele, Gurame" value="{{ old('jenis_ikan') }}" required
                                       style="box-shadow: none; border-color: #ced4da;">
                            </div>
                            @error('jenis_ikan')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        {{-- Nomor WhatsApp --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: var(--text-dark);">Nomor WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0" style="border-color: #ced4da;"><i class="bi bi-whatsapp text-muted"></i></span>
                                <input type="text" name="nomor_whatsapp" class="form-control border-start-0 ps-0 @error('nomor_whatsapp') is-invalid @enderror"
                                       placeholder="+62 8XX XXXX XXXX" value="{{ old('nomor_whatsapp') }}" required
                                       style="box-shadow: none; border-color: #ced4da;">
                            </div>
                            <div class="form-text mt-2"><i class="bi bi-info-circle"></i> Pastikan nomor WhatsApp aktif untuk verifikasi lanjutan.</div>
                            @error('nomor_whatsapp')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        {{-- Jumlah Ikan --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: var(--text-dark);">Jumlah Ikan (Ekor)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0" style="border-color: #ced4da;"><i class="bi bi-layers text-muted"></i></span>
                                <input type="number" name="jumlah_ikan" class="form-control border-start-0 ps-0 @error('jumlah_ikan') is-invalid @enderror"
                                       placeholder="Minimal 1000 ekor" min="1000" value="{{ old('jumlah_ikan') }}" required
                                       style="box-shadow: none; border-color: #ced4da;">
                            </div>
                            @error('jumlah_ikan')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: var(--text-dark);">Deskripsi Tambahan <span class="text-muted fw-normal">(Opsional)</span></label>
                            <textarea name="deskripsi" rows="3"
                                      class="form-control @error('deskripsi') is-invalid @enderror"
                                      placeholder="Ceritakan kondisi kolam, pengalaman budidaya, atau informasi tambahan lainnya..."
                                      style="border-color: #ced4da; box-shadow: none; resize: none;">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        {{-- Upload Foto --}}
                        <div class="mb-5">
                            <label class="form-label fw-bold" style="color: var(--text-dark);">
                                Foto Kolam / Kondisi Ikan
                                <span class="text-muted fw-normal">(Opsional, maks. 5 foto)</span>
                            </label>

                            {{-- Drop Zone --}}
                            <div id="dropZone" class="border-2 border-dashed rounded-3 p-4 text-center position-relative"
                                 style="border: 2px dashed #87CEEB; cursor: pointer; background: #f8fdff; transition: all .2s;">
                                <input type="file" id="fotoInput" name="foto[]" multiple accept="image/*"
                                       class="position-absolute top-0 start-0 w-100 h-100 opacity-0"
                                       style="cursor: pointer; z-index: 2;">
                                <div id="dropPlaceholder">
                                    <i class="bi bi-cloud-arrow-up-fill mb-2 d-block" style="font-size: 2.5rem; color: #87CEEB;"></i>
                                    <p class="mb-1 fw-semibold" style="color: var(--dark-blue);">Klik atau drag foto ke sini</p>
                                    <p class="text-muted small mb-0">Format: JPG, PNG, WEBP — Maks. 3MB per foto, maks. 5 foto</p>
                                </div>
                            </div>
                            @error('foto')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            @error('foto.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror

                            {{-- Preview Grid --}}
                            <div id="previewGrid" class="row g-2 mt-2" style="display:none!important"></div>

                            {{-- Counter --}}
                            <div id="fotoCounter" class="text-muted small mt-2" style="display:none">
                                <i class="bi bi-images me-1"></i><span id="counterText">0</span> foto dipilih
                                <button type="button" id="clearAll" class="btn btn-link btn-sm text-danger p-0 ms-2">Hapus semua</button>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm" style="font-weight: 600; padding: 12px 0;">
                                <i class="bi bi-send-fill me-2"></i> Kirim Penawaran
                            </button>
                            <a href="{{ route('budidaya.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill shadow-sm mt-2" style="font-weight: 600; padding: 12px 0;">
                                <i class="bi bi-clock-history me-2"></i> Lihat Riwayat Penawaran Saya
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row mt-4 g-4">
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm border-0" style="border-radius: 12px; background: linear-gradient(145deg, #ffffff, var(--light-blue)); border: 1px solid rgba(135, 206, 235, 0.4) !important;">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-patch-check-fill mb-3 d-block" style="font-size: 2.5rem; color: var(--dark-blue);"></i>
                            <h6 class="fw-bold" style="color: var(--dark-blue);">Verifikasi Cepat</h6>
                            <p class="text-muted small mb-0">Tim kami akan memproses penawaran Anda dalam waktu kurang dari 12 jam kerja.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm border-0" style="border-radius: 12px; background: linear-gradient(145deg, #ffffff, var(--light-blue)); border: 1px solid rgba(135, 206, 235, 0.4) !important;">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-shield-check mb-3 d-block" style="font-size: 2.5rem; color: var(--dark-blue);"></i>
                            <h6 class="fw-bold" style="color: var(--dark-blue);">Proses Aman</h6>
                            <p class="text-muted small mb-0">Seluruh data yang dikirimkan akan melalui proses validasi parameter secara otomatis.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus, textarea:focus {
        border-color: var(--primary-blue) !important;
        box-shadow: none !important;
    }
    .input-group:focus-within .input-group-text,
    .input-group:focus-within .form-control {
        border-color: var(--primary-blue) !important;
    }
    #dropZone.dragover {
        background: #e0f6ff !important;
        border-color: var(--dark-blue) !important;
    }
    .preview-thumb {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        aspect-ratio: 1;
        background: #f0f0f0;
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input     = document.getElementById('fotoInput');
    const dropZone  = document.getElementById('dropZone');
    const grid      = document.getElementById('previewGrid');
    const counter   = document.getElementById('fotoCounter');
    const counterTxt= document.getElementById('counterText');
    const clearBtn  = document.getElementById('clearAll');
    const MAX_FILES = 5;

    let selectedFiles = []; // Array of File objects

    // Drag & drop style
    dropZone.addEventListener('dragover',  e => { e.preventDefault(); dropZone.classList.add('dragover'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        addFiles([...e.dataTransfer.files]);
    });

    input.addEventListener('change', () => {
        const files = [...input.files];
        input.value = ''; // Reset agar bisa pilih file yang sama lagi
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

    // Sync File objects ke input[file] via DataTransfer
    function syncInput(files) {
        const dt = new DataTransfer();
        files.forEach(f => dt.items.add(f));
        input.files = dt.files;
    }
});
</script>
@endsection
