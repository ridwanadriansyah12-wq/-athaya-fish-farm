@extends('layouts.app')

@section('title', 'Katalog Ikan')

@section('content')
<div class="container mt-5" bis_size="{&quot;x&quot;: 0, &quot;y&quot;: 0, &quot;w&quot;: 1536, &quot;h&quot;: 4321}"  bis_frame="0x1c8f200">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-4" style="color: var(--dark-blue); font-weight: 700;"><i class="bi bi-shop"></i> Katalog Ikan</h2>
            
            <form action="{{ route('katalog.index') }}" method="GET" class="row g-3 mb-4">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="jenis" class="form-select">
                        <option value="">-- Semua Jenis --</option>
                        @foreach($jenisIkan as $jenis)
                            <option value="{{ $jenis->id }}" {{ request('jenis') == $jenis->id ? 'selected' : '' }}>
                                {{ $jenis->nama_jenis }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($katalog->count() > 0)
        <div class="row">
            @foreach($katalog as $produk)
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card product-card h-100">
                        <div class="product-image">
                            @if($produk->gambar)
                                <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <i class="bi bi-fish"></i>
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title fw-bold" style="color: var(--text-dark);">{{ $produk->nama_produk }}</h6>
                            <p class="card-text text-muted small" style="font-weight: 500;">{{ $produk->jenisIkan?->nama_jenis ?? '-' }}</p>
                            
                            @if($produk->berat_gram)
                                <p class="card-text small text-muted">
                                    <i class="bi bi-weight"></i> {{ $produk->berat_gram }} gr
                                </p>
                            @endif

                            <p class="card-text">
                                @if($produk->harga_satuan)
                                    <strong style="color: var(--dark-blue); font-size: 1.1rem;">Rp {{ number_format($produk->harga_satuan, 0, ',', '.') }}</strong>
                                @endif
                            </p>

                            @if($produk->stok > 0)
                                <small class="text-success mb-3">
                                    <i class="bi bi-check-circle"></i> Tersedia ({{ $produk->stok }} unit)
                                </small>
                                <a href="{{ route('katalog.show', $produk) }}" class="btn btn-primary btn-sm mt-auto">
                                    <i class="bi bi-eye"></i> Lihat Detail
                                </a>
                            @else
                                <small class="text-danger mb-3">
                                    <i class="bi bi-x-circle"></i> Stok Habis
                                </small>
                                <button class="btn btn-secondary btn-sm mt-auto" disabled>Stok Habis</button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center">
            {{ $katalog->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle"></i> Tidak ada produk yang ditemukan
        </div>
    @endif
</div>
@endsection
