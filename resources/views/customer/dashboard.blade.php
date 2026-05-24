@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="bi bi-speedometer2"></i> Dashboard {{ auth()->user()->isCustomer() ? 'Customer' : 'Pemilik' }}</h2>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white" style="background: linear-gradient(135deg, #87CEEB 0%, #4A90A4 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total Pesanan</h6>
                            <h3>{{ $statistik['total_pesanan'] }}</h3>
                        </div>
                        <i class="bi bi-receipt" style="font-size: 2.5rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-white" style="background: linear-gradient(135deg, #FFB6C1 0%, #FF69B4 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Menunggu Konfirmasi</h6>
                            <h3>{{ $statistik['pesanan_pending'] }}</h3>
                        </div>
                        <i class="bi bi-hourglass-split" style="font-size: 2.5rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-white" style="background: linear-gradient(135deg, #90EE90 0%, #228B22 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Selesai</h6>
                            <h3>{{ $statistik['pesanan_selesai'] }}</h3>
                        </div>
                        <i class="bi bi-check-circle" style="font-size: 2.5rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-white" style="background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total Belanja</h6>
                            <h5>Rp {{ number_format($statistik['total_belanja'], 0, ',', '.') }}</h5>
                        </div>
                        <i class="bi bi-wallet" style="font-size: 2.5rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profil Pengguna -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div style="width: 100px; height: 100px; margin: 0 auto; border-radius: 50%; overflow: hidden; background-color: var(--light-blue); display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                        @if(auth()->user()->foto_profil)
                            <img src="{{ asset('uploads/profil/' . auth()->user()->foto_profil) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="bi bi-person-circle" style="font-size: 60px; color: var(--primary-blue);"></i>
                        @endif
                    </div>

                    <h5>{{ auth()->user()->name }}</h5>
                    <p class="text-muted">{{ auth()->user()->email }}</p>

                    <p class="small mb-0">
                        <strong><i class="bi bi-telephone"></i> {{ auth()->user()->nomor_telepon }}</strong>
                    </p>
                    <p class="small text-muted">
                        <i class="bi bi-geo-alt"></i> {{ auth()->user()->alamat }}
                    </p>

                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-primary mt-3">
                        <i class="bi bi-pencil"></i> Edit Profil
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-list-check"></i> Pesanan Terbaru</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No. Pesanan</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(isset($pesanan) ? $pesanan->take(5) : [] as $order)
                                <tr>
                                    <td><strong>{{ $order->nomor_pesanan }}</strong></td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td>Rp {{ number_format($order->total_pembayaran, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status === 'selesai' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('pesanan.show', $order) }}" class="btn btn-xs btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-muted">
                                        Belum ada pesanan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if(auth()->user()->isCustomer())
                <div class="text-end mt-3">
                    <a href="{{ route('pesanan.list') }}" class="btn btn-outline-primary">
                        <i class="bi bi-list"></i> Lihat Semua Pesanan
                    </a>
                </div>
            @endif
        </div>
    </div>

    @if(auth()->user()->isPemilik())
        <hr class="my-5">
        <div class="row">
            <div class="col-md-12">
                <h4 class="mb-4"><i class="bi bi-wrench"></i> Menu Operasional Pemilik</h4>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('operasional.kondisi-form') }}" class="card text-decoration-none text-dark" style="transition: transform 0.3s;">
                            <div class="card-body text-center">
                                <i class="bi bi-water" style="font-size: 2rem; color: var(--primary-blue);"></i>
                                <h6 class="mt-3">Input Kondisi Kolam</h6>
                                <p class="small text-muted">Catat kondisi harian kolam ikan</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('operasional.kematian-form') }}" class="card text-decoration-none text-dark" style="transition: transform 0.3s;">
                            <div class="card-body text-center">
                                <i class="bi bi-exclamation-triangle" style="font-size: 2rem; color: var(--primary-blue);"></i>
                                <h6 class="mt-3">Input Kematian Ikan</h6>
                                <p class="small text-muted">Catat jika ada ikan yang mati</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('operasional.laporan-list') }}" class="card text-decoration-none text-dark" style="transition: transform 0.3s;">
                            <div class="card-body text-center">
                                <i class="bi bi-file-earmark-pdf" style="font-size: 2rem; color: var(--primary-blue);"></i>
                                <h6 class="mt-3">Laporan</h6>
                                <p class="small text-muted">Lihat dan cetak laporan</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('pesanan.all-orders') }}" class="card text-decoration-none text-dark" style="transition: transform 0.3s;">
                            <div class="card-body text-center">
                                <i class="bi bi-inbox" style="font-size: 2rem; color: var(--primary-blue);"></i>
                                <h6 class="mt-3">Kelola Pesanan</h6>
                                <p class="small text-muted">Konfirmasi dan kelola pesanan</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
