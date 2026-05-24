@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="h4 font-weight-bold">Detail Pre-Order #{{ $preOrder->id }}</h2>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('admin.pre-order.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
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

    <!-- Info Card -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Informasi Pre-Order</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <p class="text-muted mb-1">Nama Pelanggan</p>
                    <p class="font-weight-bold">{{ $preOrder->user->name ?? 'Anonim' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="text-muted mb-1">Email</p>
                    <p class="font-weight-bold">{{ $preOrder->user->email ?? '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="text-muted mb-1">Nomor Telepon</p>
                    <p class="font-weight-bold">{{ $preOrder->user->nomor_telepon ?? '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="text-muted mb-1">Alamat</p>
                    <p class="font-weight-bold">{{ Str::limit($preOrder->user->alamat ?? '-', 30) }}</p>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-3">
                    <p class="text-muted mb-1">Tanggal Target</p>
                    <p class="font-weight-bold">{{ $preOrder->Tanggal_Target ? \Carbon\Carbon::parse($preOrder->Tanggal_Target)->format('d M Y') : '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="text-muted mb-1">Total Harga</p>
                    <p class="font-weight-bold text-primary">Rp {{ number_format($preOrder->Total_Harga, 0, ',', '.') }}</p>
                </div>
                <div class="col-md-3">
                    <p class="text-muted mb-1">Status</p>
                    <p class="mb-0">
                        @if($preOrder->Status == 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @elseif($preOrder->Status == 'dikonfirmasi')
                            <span class="badge bg-info">Dikonfirmasi</span>
                        @elseif($preOrder->Status == 'selesai')
                            <span class="badge bg-success">Selesai</span>
                        @elseif($preOrder->Status == 'dibatalkan')
                            <span class="badge bg-danger">Dibatalkan</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($preOrder->Status) }}</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-3">
                    <p class="text-muted mb-1">Dibuat</p>
                    <p class="font-weight-bold">{{ $preOrder->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Daftar Item Pre-Order</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4">Produk</th>
                        <th>Kuantitas</th>
                        <th>Harga Satuan</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($preOrder->detailPreOrder as $item)
                    <tr>
                        <td class="px-4">
                            <span class="font-weight-bold">{{ $item->menu->Nama_Produk ?? 'Produk Tidak Ditemukan' }}</span>
                        </td>
                        <td>{{ $item->Kuantitas }} unit</td>
                        <td>Rp {{ number_format($item->Harga_Satuan, 0, ',', '.') }}</td>
                        <td class="font-weight-bold">Rp {{ number_format($item->Kuantitas * $item->Harga_Satuan, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-3 text-muted">Tidak ada item</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Status Update Form -->
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Update Status Pre-Order</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pre-order.status', $preOrder->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="row">
                    <div class="col-md-6">
                        <label for="status" class="form-label font-weight-bold">Status Baru <span class="text-danger">*</span></label>
                        <select class="form-select" id="status" name="Status" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="pending" {{ $preOrder->Status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="dikonfirmasi" {{ $preOrder->Status == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                            <option value="selesai" {{ $preOrder->Status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ $preOrder->Status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-check"></i> Update Status
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
