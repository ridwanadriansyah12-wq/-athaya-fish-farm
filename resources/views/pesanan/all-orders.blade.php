@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 style="color: #4A90A4;"><i class="bi bi-bag-check"></i> Kelola Semua Pesanan</h2>
            <p class="text-muted">Daftar lengkap pesanan dari pelanggan</p>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('pesanan.all-orders') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Cari pesanan atau nama pelanggan..." 
                       value="{{ request('search') }}">
                <select name="status" class="form-select" style="max-width: 200px;">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="dikonfirmasi" {{ request('status') == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                    <option value="pembayaran" {{ request('status') == 'pembayaran' ? 'selected' : '' }}>Pembayaran</option>
                    <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="persiapan" {{ request('status') == 'persiapan' ? 'selected' : '' }}>Persiapan</option>
                    <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                <button type="submit" class="btn" style="background-color: #4A90A4; color: white; border: none;">
                    <i class="bi bi-search"></i> Cari
                </button>
            </form>
        </div>
    </div>

    @if($pesanan->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead style="background-color: #E0F6FF;">
                    <tr>
                        <th style="color: #4A90A4;"><i class="bi bi-hash"></i> No. Pesanan</th>
                        <th style="color: #4A90A4;">Pelanggan</th>
                        <th style="color: #4A90A4;">Tanggal</th>
                        <th style="color: #4A90A4;">Jumlah Item</th>
                        <th style="color: #4A90A4;">Total</th>
                        <th style="color: #4A90A4;">Status</th>
                        <th style="color: #4A90A4;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pesanan as $order)
                        <tr>
                            <td>
                                <strong style="color: #2C5985;">{{ $order->nomor_pesanan }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ optional($order->customer)->name ?? '-' }}</strong>
                                    <br>
                                    <small class="text-muted">{{ optional($order->customer)->email ?? '' }}</small>
                                </div>
                            </td>
                            <td>
                                {{ $order->created_at->format('d M Y') }}
                            </td>
                            <td>
                                <span class="badge" style="background-color: #87CEEB; color: #2C5985;">
                                    {{ $order->detailPesanan ? $order->detailPesanan->count() : 0 }} item
                                </span>
                            </td>
                            <td>
                                <strong style="color: #4A90A4;">
                                    Rp {{ number_format($order->detailPesanan ? $order->detailPesanan->sum('subtotal') : 0, 0, ',', '.') }}
                                </strong>
                            </td>
                            <td>
                                @php
                                    $statusBadgeColor = match($order->status) {
                                        'pending' => '#FFC107',
                                        'dikonfirmasi' => '#17A2B8',
                                        'pembayaran' => '#6C757D',
                                        'lunas' => '#28A745',
                                        'persiapan' => '#E83E8C',
                                        'dikirim' => '#007BFF',
                                        'selesai' => '#20C997',
                                        default => '#6C757D'
                                    };
                                    $statusTextColor = in_array($order->status, ['pending', 'pembayaran']) ? '#000' : '#fff';
                                @endphp
                                <span class="badge" style="background-color: {{ $statusBadgeColor }}; color: {{ $statusTextColor }};">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.order.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>

                                    @if($order->status === 'pending')
                                        <form action="{{ route('pesanan.konfirmasi', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="aksi" value="terima">
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Konfirmasi pesanan ini?')">
                                                <i class="bi bi-check-circle"></i> Konfirmasi
                                            </button>
                                        </form>
                                        <form action="{{ route('pesanan.konfirmasi', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="aksi" value="tolak">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tolak pesanan ini?')">
                                                <i class="bi bi-x-circle"></i> Tolak
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($pesanan->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
            <small class="text-muted">
                Menampilkan {{ $pesanan->firstItem() }}–{{ $pesanan->lastItem() }} dari {{ $pesanan->total() }} pesanan
            </small>
            <nav aria-label="Navigasi halaman">
                <ul class="pagination pagination-sm mb-0">
                    {{-- Previous --}}
                    <li class="page-item {{ $pesanan->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $pesanan->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}" aria-label="Previous">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>

                    {{-- Page Numbers --}}
                    @foreach($pesanan->getUrlRange(1, $pesanan->lastPage()) as $page => $url)
                        @if(abs($page - $pesanan->currentPage()) <= 2 || $page === 1 || $page === $pesanan->lastPage())
                            @if($page == $pesanan->currentPage())
                                <li class="page-item active">
                                    <span class="page-link" style="background-color:#4A90A4; border-color:#4A90A4;">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}&{{ http_build_query(request()->except('page')) }}">{{ $page }}</a>
                                </li>
                            @endif
                        @elseif(abs($page - $pesanan->currentPage()) === 3)
                            <li class="page-item disabled"><span class="page-link">…</span></li>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    <li class="page-item {{ !$pesanan->hasMorePages() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $pesanan->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}" aria-label="Next">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        @endif
    @else
        <div class="alert alert-info border-0" style="background-color: #E0F6FF; color: #4A90A4;">
            <div class="d-flex">
                <i class="bi bi-info-circle me-3" style="font-size: 1.5rem;"></i>
                <div>
                    <h5 class="mb-1">Tidak Ada Pesanan</h5>
                    <p class="mb-0">Belum ada pesanan yang sesuai dengan filter Anda. Pesanan baru akan muncul di sini.</p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
