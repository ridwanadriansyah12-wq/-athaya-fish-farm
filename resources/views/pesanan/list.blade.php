@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4"><i class="bi bi-receipt"></i> Pesanan Saya</h2>

    @if($pesanan->count() > 0)
        {{-- Tampilan Desktop --}}
        <div class="card d-none d-md-block">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Tanggal</th>
                            <th>Item</th>
                            <th class="text-right">Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pesanan as $order)
                            <tr>
                                <td><strong>{{ $order->nomor_pesanan }}</strong></td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                <td>{{ $order->detailPesanan->count() }} item</td>
                                <td class="text-right">Rp {{ number_format($order->total_pembayaran, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $order->status === 'selesai' ? 'success' : 
                                        ($order->status === 'pending' ? 'warning' : 
                                        ($order->status === 'ditolak' ? 'danger' : 'info'))
                                    }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('pesanan.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tampilan Mobile --}}
        <div class="d-md-none">
            @foreach($pesanan as $order)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold text-primary">#{{ $order->nomor_pesanan }}</span>
                            <span class="badge bg-{{ 
                                $order->status === 'selesai' ? 'success' : 
                                ($order->status === 'pending' ? 'warning' : 
                                ($order->status === 'ditolak' ? 'danger' : 'info'))
                            }}">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </div>
                        <div class="text-muted small mb-3">
                            <i class="bi bi-calendar3 me-1"></i> {{ $order->created_at->format('d M Y') }}
                            <span class="mx-2">•</span>
                            <i class="bi bi-bag me-1"></i> {{ $order->detailPesanan->count() }} Item
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <div>
                                <div class="text-muted small" style="font-size: 0.75rem;">Total Pembayaran</div>
                                <strong class="text-success">Rp {{ number_format($order->total_pembayaran, 0, ',', '.') }}</strong>
                            </div>
                            <a href="{{ route('pesanan.show', $order) }}" class="btn btn-sm btn-primary px-3">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $pesanan->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem; color: var(--primary-blue);"></i>
                <h5 class="mt-3">Belum Ada Pesanan</h5>
                <p class="text-muted">Anda belum membuat pesanan. Mulai belanja sekarang!</p>
                <a href="{{ route('katalog.index') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-shop"></i> Mulai Belanja
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
