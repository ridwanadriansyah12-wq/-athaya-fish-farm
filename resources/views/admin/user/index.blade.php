@extends('layouts.app')

@section('title', 'Kelola User')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-people"></i> Kelola User</h2>
        <a href="{{ route('admin.user.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-lg"></i> Tambah User
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Nama</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th>Role</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->nomor_telepon ?? '-' }}</td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge bg-danger px-2 py-1"><i class="bi bi-shield-lock"></i> {{ ucfirst($user->role) }}</span>
                                @elseif($user->role === 'pemilik')
                                    <span class="badge bg-primary px-2 py-1"><i class="bi bi-person-badge"></i> {{ ucfirst($user->role) }}</span>
                                @else
                                    <span class="badge bg-success px-2 py-1"><i class="bi bi-person"></i> {{ ucfirst($user->role) }}</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Tidak ada data user
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div class="mt-4">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
