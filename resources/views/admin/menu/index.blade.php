@extends('layouts.app')

@section('title', 'Kelola Produk')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-box-seam"></i> Kelola Produk</h2>
        <div class="d-flex gap-2">
            {{-- Hapus Dipilih --}}
            <button type="button" id="btn-hapus-dipilih"
                class="btn btn-outline-danger shadow-sm d-none"
                onclick="konfirmasiHapusDipilih()">
                <i class="bi bi-trash"></i> Hapus Dipilih (<span id="jumlah-dipilih">0</span>)
            </button>

            {{-- Hapus Semua --}}
            @if($menu->total() > 0)
            <button type="button" class="btn btn-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#modalHapusSemua">
                <i class="bi bi-trash3-fill"></i> Hapus Semua
            </button>
            @endif

            <a href="{{ route('admin.menu.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-lg"></i> Tambah Produk
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-x-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Form Hapus Dipilih (Bulk) --}}
    <form id="form-bulk-delete" action="{{ route('admin.menu.bulk-destroy') }}" method="POST">
        @csrf
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 48px;">
                                    <input type="checkbox" id="check-all" class="form-check-input"
                                        title="Pilih semua" onchange="toggleCheckAll(this)">
                                </th>
                                <th>Foto</th>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th class="text-center">Stok</th>
                                <th class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($menu as $item)
                            <tr class="produk-row">
                                <td class="ps-4">
                                    <input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                        class="form-check-input row-checkbox" onchange="updatePilihan()">
                                </td>
                                <td>
                                    @if($item->gambar)
                                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_produk }}"
                                            class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center text-secondary border rounded"
                                            style="width: 50px; height: 50px;">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $item->nama_produk }}</td>
                                <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    @if($item->stok > 0)
                                        <span class="badge bg-success px-2 py-1">{{ $item->stok }}</span>
                                    @else
                                        <span class="badge bg-danger px-2 py-1">Habis</span>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    <a href="{{ route('admin.menu.edit', $item->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.menu.destroy', $item->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    Tidak ada produk
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>{{-- End form bulk delete --}}

    <!-- Pagination -->
    @if($menu->hasPages())
    <div class="mt-3 d-flex justify-content-end align-items-center gap-2">
        {{-- Previous --}}
        @if($menu->onFirstPage())
            <button class="btn btn-sm btn-outline-secondary" disabled>
                <i class="bi bi-chevron-left"></i>
            </button>
        @else
            <a href="{{ $menu->previousPageUrl() }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-chevron-left"></i>
            </a>
        @endif

        {{-- Page numbers --}}
        @foreach($menu->getUrlRange(1, $menu->lastPage()) as $page => $url)
            @if($page == $menu->currentPage())
                <button class="btn btn-sm btn-primary" style="min-width:34px">{{ $page }}</button>
            @else
                <a href="{{ $url }}" class="btn btn-sm btn-outline-primary" style="min-width:34px">{{ $page }}</a>
            @endif
        @endforeach

        {{-- Next --}}
        @if($menu->hasMorePages())
            <a href="{{ $menu->nextPageUrl() }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-chevron-right"></i>
            </a>
        @else
            <button class="btn btn-sm btn-outline-secondary" disabled>
                <i class="bi bi-chevron-right"></i>
            </button>
        @endif
        <small class="text-muted">Halaman {{ $menu->currentPage() }} dari {{ $menu->lastPage() }}</small>
    </div>
    @endif

</div>

{{-- ===== MODAL: Konfirmasi Hapus Semua ===== --}}
<div class="modal fade" id="modalHapusSemua" tabindex="-1" aria-labelledby="modalHapusSemuaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-danger fw-bold" id="modalHapusSemuaLabel">
                    <i class="bi bi-exclamation-triangle-fill"></i> Hapus Semua Produk
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus <strong>semua produk ({{ $menu->total() }} produk)</strong>?</p>
                <p class="text-danger mb-0"><i class="bi bi-exclamation-circle"></i> Tindakan ini <strong>tidak dapat dibatalkan</strong> dan semua data produk beserta gambarnya akan hilang permanen.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.menu.destroy-all') }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash3-fill"></i> Ya, Hapus Semua
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL: Konfirmasi Hapus Dipilih ===== --}}
<div class="modal fade" id="modalHapusDipilih" tabindex="-1" aria-labelledby="modalHapusDipilihLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-danger fw-bold" id="modalHapusDipilihLabel">
                    <i class="bi bi-exclamation-triangle-fill"></i> Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Anda akan menghapus <strong id="modal-jumlah">0</strong> produk yang dipilih.</p>
                <p class="text-danger mb-0"><i class="bi bi-exclamation-circle"></i> Tindakan ini <strong>tidak dapat dibatalkan</strong>.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" onclick="submitBulkDelete()">
                    <i class="bi bi-trash"></i> Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>

@endsection {{-- end content --}}

@section('extra-js')
<script>
    // Pilih/batal semua checkbox
    function toggleCheckAll(masterCheckbox) {
        document.querySelectorAll('.row-checkbox').forEach(cb => {
            cb.checked = masterCheckbox.checked;
        });
        updatePilihan();
    }

    // Update counter & tampil/sembunyikan tombol hapus dipilih
    function updatePilihan() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        const total = checked.length;
        const all = document.querySelectorAll('.row-checkbox');

        document.getElementById('jumlah-dipilih').textContent = total;

        const btnHapus = document.getElementById('btn-hapus-dipilih');
        if (total > 0) {
            btnHapus.classList.remove('d-none');
        } else {
            btnHapus.classList.add('d-none');
        }

        // Sinkronkan check-all
        const checkAll = document.getElementById('check-all');
        if (total === 0) {
            checkAll.indeterminate = false;
            checkAll.checked = false;
        } else if (total === all.length) {
            checkAll.indeterminate = false;
            checkAll.checked = true;
        } else {
            checkAll.indeterminate = true;
        }
    }

    // Buka modal konfirmasi hapus dipilih
    function konfirmasiHapusDipilih() {
        const jumlah = document.querySelectorAll('.row-checkbox:checked').length;
        document.getElementById('modal-jumlah').textContent = jumlah;
        const modal = new bootstrap.Modal(document.getElementById('modalHapusDipilih'));
        modal.show();
    }

    // Submit form bulk delete
    function submitBulkDelete() {
        document.getElementById('form-bulk-delete').submit();
    }
</script>
@endsection
