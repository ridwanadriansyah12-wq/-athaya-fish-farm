@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="h4 font-weight-bold">Manajemen Jenis Ikan</h2>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.ikan.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Jenis Ikan
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Sukses!</strong> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Table -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4">ID</th>
                        <th>Nama Ikan</th>
                        <th>Deskripsi</th>
                        <th>Suhu Ideal (°C)</th>
                        <th>pH Ideal</th>
                        <th>Dibuat</th>
                        <th class="text-end px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jenisIkans as $ikan)
                    <tr>
                        <td class="px-4">{{ $ikan->id }}</td>
                        <td>
                            <span class="font-weight-bold">{{ $ikan->Nama_Ikan }}</span>
                        </td>
                        <td>
                            <small>{{ Str::limit($ikan->Deskripsi, 50) }}</small>
                        </td>
                        <td>{{ $ikan->Suhu_Ideal_Min ?? '-' }} - {{ $ikan->Suhu_Ideal_Max ?? '-' }}</td>
                        <td>{{ $ikan->pH_Ideal_Min ?? '-' }} - {{ $ikan->pH_Ideal_Max ?? '-' }}</td>
                        <td>
                            <small class="text-muted">{{ $ikan->created_at->format('d M Y') }}</small>
                        </td>
                        <td class="text-end px-4">
                            <a href="{{ route('admin.ikan.edit', $ikan->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.ikan.destroy', $ikan->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <p>Belum ada data jenis ikan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="row mt-4">
        <div class="col-md-12">
            {{ $jenisIkans->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection
