@extends('layouts.app')

@section('title', 'Kelola Penawaran Budidaya')

@section('extra-css')
<style>
    .gallery-img-container:hover .gallery-img {
        transform: scale(1.05);
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="page-header d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4><i class="bi bi-droplet-half me-2"></i>Kelola Penawaran Budidaya</h4>
            <p class="mb-0 text-muted">Daftar penawaran budidaya dari customer</p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Tanggal</th>
                            <th>Customer</th>
                            <th>No. WhatsApp</th>
                            <th>Jenis Ikan</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-center">Foto</th>
                            <th class="text-center">Status</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penawaran as $item)
                        <tr>
                            <td class="ps-4">
                                <small class="text-muted">{{ $item->created_at->format('d M Y') }}</small><br>
                                <small class="text-muted">{{ $item->created_at->format('H:i') }}</small>
                            </td>
                            <td class="fw-semibold">{{ optional($item->user)->name ?? '-' }}</td>
                            <td>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->nomor_whatsapp) }}"
                                   target="_blank" class="text-decoration-none text-success">
                                    <i class="bi bi-whatsapp"></i> {{ $item->nomor_whatsapp }}
                                </a>
                            </td>
                            <td>{{ $item->jenis_ikan }}</td>
                            <td class="text-center">{{ number_format($item->jumlah_ikan, 0, ',', '.') }}</td>

                            {{-- Kolom Foto --}}
                            <td class="text-center">
                                @if($item->foto && count($item->foto) > 0)
                                    <button type="button"
                                             class="btn btn-sm btn-outline-primary"
                                             data-bs-toggle="modal"
                                             data-bs-target="#detailModal{{ $item->id }}"
                                             title="Lihat {{ count($item->foto) }} foto">
                                        <i class="bi bi-images me-1"></i>{{ count($item->foto) }} foto
                                    </button>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="text-center">
                                @if($item->status == 'pending')
                                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Pending</span>
                                @elseif($item->status == 'diterima')
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Diterima</span>
                                @else
                                    <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Ditolak</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="text-center pe-4">
                                {{-- Tombol Detail Terpadu --}}
                                <button type="button" class="btn btn-sm btn-outline-info mb-1"
                                        data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id }}"
                                        title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </button>

                                @if($item->status == 'pending')
                                <form action="{{ route('admin.budidaya.status', $item->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="diterima">
                                    <button type="submit" class="btn btn-sm btn-success mb-1"
                                            onclick="return confirm('Terima penawaran budidaya ini?')">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.budidaya.status', $item->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="ditolak">
                                    <button type="submit" class="btn btn-sm btn-danger mb-1"
                                            onclick="return confirm('Tolak penawaran budidaya ini?')">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </form>
                                @else
                                    <span class="text-muted small">Selesai</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                                Belum ada penawaran budidaya
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    @if($penawaran->hasPages())
    <div class="mt-3 d-flex justify-content-end align-items-center gap-2">
        @if($penawaran->onFirstPage())
            <button class="btn btn-sm btn-outline-secondary" disabled><i class="bi bi-chevron-left"></i></button>
        @else
            <a href="{{ $penawaran->previousPageUrl() }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-chevron-left"></i></a>
        @endif
        @foreach($penawaran->getUrlRange(1, $penawaran->lastPage()) as $page => $url)
            @if($page == $penawaran->currentPage())
                <button class="btn btn-sm btn-primary" style="min-width:34px">{{ $page }}</button>
            @else
                <a href="{{ $url }}" class="btn btn-sm btn-outline-primary" style="min-width:34px">{{ $page }}</a>
            @endif
        @endforeach
        @if($penawaran->hasMorePages())
            <a href="{{ $penawaran->nextPageUrl() }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-chevron-right"></i></a>
        @else
            <button class="btn btn-sm btn-outline-secondary" disabled><i class="bi bi-chevron-right"></i></button>
        @endif
    </div>
    @endif

</div>

{{-- Modal Detail Penawaran Budidaya --}}
@foreach($penawaran as $item)
<div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background:var(--primary-pale)">
                <h5 class="modal-title" style="color:var(--primary-dark)">
                    <i class="bi bi-droplet-half me-2"></i>Detail Penawaran — {{ optional($item->user)->name ?? '-' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                {{-- Informasi Utama --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3">
                            <small class="text-muted d-block uppercase fw-bold mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">CUSTOMER</small>
                            <span class="fw-semibold text-dark fs-6">{{ optional($item->user)->name ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3">
                            <small class="text-muted d-block uppercase fw-bold mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">NO. WHATSAPP</small>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->nomor_whatsapp) }}"
                               target="_blank" class="text-decoration-none text-success fw-semibold fs-6">
                                <i class="bi bi-whatsapp me-1"></i>{{ $item->nomor_whatsapp }}
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3">
                            <small class="text-muted d-block uppercase fw-bold mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">JENIS IKAN</small>
                            <span class="fw-semibold text-dark fs-6">{{ $item->jenis_ikan }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3">
                            <small class="text-muted d-block uppercase fw-bold mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">JUMLAH IKAN</small>
                            <span class="fw-semibold text-dark fs-6">{{ number_format($item->jumlah_ikan, 0, ',', '.') }} ekor</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3">
                            <small class="text-muted d-block uppercase fw-bold mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">TANGGAL PENGAJUAN</small>
                            <span class="fw-semibold text-dark fs-6">{{ $item->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="mb-4">
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-2">
                        <i class="bi bi-chat-left-text me-1 text-primary"></i> Deskripsi Tambahan
                    </h6>
                    @if($item->deskripsi)
                        <div class="p-3 bg-white border rounded-3 text-dark-50" style="white-space: pre-wrap;">{{ $item->deskripsi }}</div>
                    @else
                        <p class="text-muted italic small mb-0">Tidak ada deskripsi tambahan dari customer.</p>
                    @endif
                </div>

                {{-- Foto-foto --}}
                <div>
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-2">
                        <i class="bi bi-images me-1 text-primary"></i> Foto Pendukung
                    </h6>
                    @if($item->foto && count($item->foto) > 0)
                        <div class="row g-2">
                            @foreach($item->foto as $fotoPath)
                            <div class="col-6 col-md-4">
                                <a href="{{ asset('storage/' . $fotoPath) }}" target="_blank" class="d-block overflow-hidden rounded-3 border position-relative gallery-img-container">
                                    <img src="{{ asset('storage/' . $fotoPath) }}"
                                         class="img-fluid w-100 gallery-img"
                                         style="aspect-ratio:1;object-fit:cover;transition: transform 0.3s ease;"
                                         alt="Foto budidaya">
                                    <div class="position-absolute bottom-0 end-0 bg-dark bg-opacity-75 text-white p-1 rounded-start small" style="font-size: 0.75rem;">
                                        <i class="bi bi-zoom-in"></i>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted small mb-0">Tidak ada foto dilampirkan.</p>
                    @endif
                </div>
            </div>
            
            {{-- Footer Modal --}}
            @if($item->status == 'pending')
            <div class="modal-footer bg-light justify-content-between">
                <div>
                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Status: Pending</span>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    
                    <form action="{{ route('admin.budidaya.status', $item->id) }}" method="POST" class="d-inline">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="diterima">
                        <button type="submit" class="btn btn-success" onclick="return confirm('Terima penawaran budidaya ini?')">
                            <i class="bi bi-check-lg me-1"></i> Terima
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.budidaya.status', $item->id) }}" method="POST" class="d-inline">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="ditolak">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Tolak penawaran budidaya ini?')">
                            <i class="bi bi-x-lg me-1"></i> Tolak
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div class="modal-footer bg-light justify-content-between">
                <div>
                    @if($item->status == 'diterima')
                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Status: Diterima</span>
                    @else
                        <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Status: Ditolak</span>
                    @endif
                </div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
            @endif
        </div>
    </div>
</div>
@endforeach

@endsection
