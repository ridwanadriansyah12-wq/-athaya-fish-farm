@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="h4 font-weight-bold">Ringkasan Pre-Order</h2>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('pemilik.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Status Overview -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary">
                <div class="card-body">
                    <div class="text-primary font-weight-bold text-uppercase mb-1">Total Pre-Order</div>
                    <div class="h3 mb-0">{{ $totalPreOrders }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning">
                <div class="card-body">
                    <div class="text-warning font-weight-bold text-uppercase mb-1">Pending</div>
                    <div class="h3 mb-0">{{ $pendingPreOrders }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info">
                <div class="card-body">
                    <div class="text-info font-weight-bold text-uppercase mb-1">Dikonfirmasi</div>
                    <div class="h3 mb-0">{{ $confirmedPreOrders }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success">
                <div class="card-body">
                    <div class="text-success font-weight-bold text-uppercase mb-1">Selesai</div>
                    <div class="h3 mb-0">{{ $completedPreOrders }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Distribution -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Distribusi Status Pre-Order</h5>
                </div>
                <div class="card-body">
                    <div style="display: flex; justify-content: space-around; align-items: flex-end; height: 200px; padding: 20px;">
                        @forelse($statusDistribution as $status => $count)
                            <div style="display: flex; flex-direction: column; align-items: center;">
                                <div style="width: 60px; height: {{ max(($count / max($statusDistribution) * 150), 10) }}px; background: 
                                    @if($status == 'pending') #ffc107
                                    @elseif($status == 'dikonfirmasi') #17a2b8
                                    @elseif($status == 'selesai') #28a745
                                    @elseif($status == 'dibatalkan') #dc3545
                                    @else #6c757d
                                    @endif; border-radius: 4px;"></div>
                                <small class="mt-2 text-muted font-weight-bold">{{ ucfirst($status) }}</small>
                                <small class="font-weight-bold">{{ $count }}</small>
                            </div>
                        @empty
                            <p class="text-muted">Tidak ada data</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pre-Orders List -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Daftar Pre-Order</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4">ID</th>
                                <th>Pelanggan</th>
                                <th>Items</th>
                                <th>Total Harga</th>
                                <th>Target</th>
                                <th>Status</th>
                                <th>Dibuat</th>
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
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Tidak ada pre-order</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
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
