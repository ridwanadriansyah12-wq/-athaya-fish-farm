@extends('layouts.app')

@section('title', 'Riwayat Penawaran Budidaya')

@section('extra-css')
<style>
    .gallery-img-container:hover .gallery-img {
        transform: scale(1.05);
    }
</style>
@endsection

@section('content')
<div class="container py-5">

    <div class="page-header d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <h4><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Penawaran Budidaya</h4>
            <p class="mb-0 text-muted">Pantau status validasi dan verifikasi pengajuan budidaya mandiri Anda</p>
        </div>
        <div>
            <a href="{{ route('budidaya.create') }}" class="btn btn-primary rounded-pill shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Ajukan Penawaran Baru
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle me-2"></i>{!! session('success') !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 15%">Tanggal</th>
                            <th style="width: 25%">Jenis Ikan</th>
                            <th class="text-center" style="width: 15%">Jumlah</th>
                            <th class="text-center" style="width: 15%">Status</th>
                            <th class="text-center pe-4" style="width: 30%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penawaran as $item)
                        <tr>
                            <td class="ps-4">
                                <small class="text-muted fw-semibold">{{ $item->created_at->format('d M Y') }}</small><br>
                                <small class="text-muted" style="font-size: 0.8rem;">{{ $item->created_at->format('H:i') }}</small>
                            </td>
                            <td class="fw-semibold text-dark">{{ $item->jenis_ikan }}</td>
                            <td class="text-center fw-semibold">{{ number_format($item->jumlah_ikan, 0, ',', '.') }} ekor</td>
                            <td class="text-center">
                                @if($item->status == 'pending')
                                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i> Pending</span>
                                @elseif($item->status == 'diterima')
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Diterima</span>
                                @else
                                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> Ditolak</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-3"
                                        data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id }}"
                                        title="Lihat Detail">
                                    <i class="bi bi-eye me-1"></i> Detail
                                </button>
                                
                                <a href="https://wa.me/6282221299765?text=Halo,%20saya%20ingin%20bertanya%20mengenai%20status%20penawaran%20budidaya%20saya%20dengan%20ID%20{{ $item->id }}"
                                   target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3 ms-1">
                                    <i class="bi bi-whatsapp"></i> Tanya CS
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50 text-primary"></i>
                                Anda belum pernah mengajukan penawaran budidaya.
                                <div class="mt-3">
                                    <a href="{{ route('budidaya.create') }}" class="btn btn-sm btn-primary rounded-pill px-4">
                                        Mulai Ajukan Sekarang
                                    </a>
                                </div>
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
    <div class="mt-4 d-flex justify-content-end">
        {{ $penawaran->links() }}
    </div>
    @endif

</div>

{{-- Modal Detail Penawaran Budidaya --}}
@foreach($penawaran as $item)
<div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-pale) 0%, #fff 100%); border-bottom: 1px solid var(--border-color);">
                <h5 class="modal-title fw-bold text-dark-50">
                    <i class="bi bi-droplet-half me-2 text-primary"></i>Detail Pengajuan Penawaran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                {{-- Informasi Utama --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3">
                            <small class="text-muted d-block uppercase fw-bold mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">JENIS IKAN</small>
                            <span class="fw-semibold text-dark fs-6">{{ $item->jenis_ikan }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3">
                            <small class="text-muted d-block uppercase fw-bold mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">TANGGAL PENGAJUAN</small>
                            <span class="fw-semibold text-dark fs-6">{{ $item->created_at->format('d M Y, H:i') }} WIB</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3">
                            <small class="text-muted d-block uppercase fw-bold mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">JUMLAH PENAWARAN</small>
                            <span class="fw-semibold text-dark fs-6">{{ number_format($item->jumlah_ikan, 0, ',', '.') }} ekor</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3">
                            <small class="text-muted d-block uppercase fw-bold mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">STATUS VALIDASI</small>
                            @if($item->status == 'pending')
                                <span class="badge bg-warning text-dark fs-7 mt-1"><i class="bi bi-hourglass-split me-1"></i> Menunggu Verifikasi</span>
                            @elseif($item->status == 'diterima')
                                <span class="badge bg-success fs-7 mt-1"><i class="bi bi-check-circle me-1"></i> Penawaran Diterima</span>
                            @else
                                <span class="badge bg-danger fs-7 mt-1"><i class="bi bi-x-circle me-1"></i> Penawaran Ditolak</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="mb-4">
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-2">
                        <i class="bi bi-chat-left-text me-2 text-primary"></i>Deskripsi Tambahan
                    </h6>
                    @if($item->deskripsi)
                        <div class="p-3 bg-white border rounded-3 text-secondary" style="white-space: pre-wrap; font-size: 0.9rem;">{{ $item->deskripsi }}</div>
                    @else
                        <p class="text-muted italic small mb-0">Tidak ada deskripsi tambahan yang diberikan.</p>
                    @endif
                </div>

                {{-- Foto-foto --}}
                <div>
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-2">
                        <i class="bi bi-images me-2 text-primary"></i>Foto Pendukung
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
            
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection
