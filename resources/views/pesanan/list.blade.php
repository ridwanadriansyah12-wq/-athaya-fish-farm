@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4"><i class="bi bi-receipt"></i> Pesanan Saya</h2>

    @if($pesanan->count() > 0)
        <div class="card">
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
