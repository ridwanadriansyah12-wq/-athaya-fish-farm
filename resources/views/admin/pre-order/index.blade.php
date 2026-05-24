@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="h4 font-weight-bold">Manajemen Pre-Order</h2>
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
                        <th>Pelanggan</th>
                        <th>Jumlah Items</th>
                        <th>Total Harga</th>
                        <th>Tgl Target</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th class="text-end px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($preOrders as $preOrder)
                    <tr>
                        <td class="px-4">#{{ $preOrder->id }}</td>
                        <td>
                            <span class="font-weight-bold">{{ $preOrder->user->name ?? 'Anonim' }}</span>
                            <br>
                            <small class="text-muted">{{ $preOrder->user->email ?? '-' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $preOrder->detailPreOrder->count() }} items</span>
                        </td>
                        <td>
                            <span class="font-weight-bold">Rp {{ number_format($preOrder->Total_Harga, 0, ',', '.') }}</span>
                        </td>
                        <td>
                            <small class="text-muted">{{ $preOrder->Tanggal_Target ? \Carbon\Carbon::parse($preOrder->Tanggal_Target)->format('d M Y') : '-' }}</small>
                        </td>
                        <td>
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
                        </td>
                        <td>
                            <small class="text-muted">{{ $preOrder->created_at->format('d M Y') }}</small>
                        </td>
                        <td class="text-end px-4">
                            <a href="{{ route('admin.pre-order.show', $preOrder->id) }}" class="btn btn-sm btn-info" title="Lihat">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <p>Belum ada pre-order</p>
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
            {{ $preOrders->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection
