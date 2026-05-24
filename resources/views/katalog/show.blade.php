@extends('layouts.app')

@section('title', $katalog->nama_produk)

@section('content')
<div class="container mt-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('katalog.index') }}">Katalog</a></li>
            <li class="breadcrumb-item active">{{ $katalog->nama_produk }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-5">
            <div class="product-image" style="height: 400px; border-radius: 8px; overflow: hidden;">
                @if($katalog->gambar)
                    <img src="{{ asset('storage/' . $katalog->gambar) }}" alt="{{ $katalog->nama_produk }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <div style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; background-color: var(--light-blue); font-size: 80px; color: var(--primary-blue);">
                        <i class="bi bi-fish"></i>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-md-7">
            <h2 class="fw-bold" style="color: var(--dark-blue);">{{ $katalog->nama_produk }}</h2>
            <p class="text-muted mb-4" style="font-weight: 500;">
                <i class="bi bi-tag"></i> {{ $katalog->jenisIkan->nama_jenis }}
            </p>

            <div class="price-box mb-4 p-4 rounded-3" style="background: linear-gradient(145deg, #ffffff, var(--light-blue)); border: 1px solid var(--primary-blue); box-shadow: 0 4px 15px rgba(135, 206, 235, 0.2); position: relative; overflow: hidden;">
                <div style="position: absolute; top: -10px; right: -10px; opacity: 0.1;">
                    <i class="bi bi-tag-fill" style="font-size: 5rem; color: var(--primary-blue);"></i>
                </div>
                <span class="badge badge-primary mb-2 px-3 py-2 rounded-pill shadow-sm"><i class="bi bi-star-fill text-warning"></i> Penawaran Terbaik</span>
                <div class="d-flex align-items-baseline mt-1">
                    <span class="h4 mb-0 text-secondary me-1">Rp</span>
                    <span class="display-5 fw-bold mb-0" style="color: var(--dark-blue); letter-spacing: -1px;">{{ number_format($katalog->harga_satuan, 0, ',', '.') }}</span>
                    <span class="text-muted ms-2 fw-normal">/ unit</span>
                </div>
            </div>

            @if($katalog->berat_gram)
                <p><strong><i class="bi bi-weight"></i> Berat:</strong> {{ $katalog->berat_gram }} gram</p>
            @endif

            @if($katalog->jenisIkan->waktu_panen_hari)
                <p><strong><i class="bi bi-calendar"></i> Estimasi Panen:</strong> {{ $katalog->jenisIkan->waktu_panen_hari }} hari</p>
            @endif

            <p><strong><i class="bi bi-box"></i> Stok Tersedia:</strong> 
                @if($katalog->stok > 0)
                    <span class="badge bg-success">{{ $katalog->stok }} unit</span>
                @else
                    <span class="badge bg-danger">Habis</span>
                @endif
            </p>

            @if($katalog->deskripsi)
                <div class="mt-4 mb-4">
                    <h5 style="color: var(--dark-blue); font-weight: 600;">Deskripsi</h5>
                    <p style="color: var(--text-dark);">{{ $katalog->deskripsi }}</p>
                </div>
            @endif

            @auth
                @if(auth()->user()->isCustomer())
                    {{-- Hanya customer yang bisa beli --}}
                    @if($katalog->stok > 0)
                        <form action="{{ route('cart.add', $katalog) }}" method="POST" class="mb-4">
                            @csrf
                            <div class="input-group mb-3">
                                <button class="btn btn-outline-secondary" type="button" id="decreaseQty">-</button>
                                <input type="number" name="qty" id="qty" class="form-control text-center" value="1" min="1" max="{{ $katalog->stok }}">
                                <button class="btn btn-outline-secondary" type="button" id="increaseQty">+</button>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" name="action" value="add_to_cart" class="btn btn-outline-primary btn-lg w-50" style="border-width: 2px; font-weight: 600;">
                                    <i class="bi bi-cart-plus"></i> Tambah Keranjang
                                </button>
                                <button type="submit" name="action" value="buy_now" class="btn btn-primary btn-lg w-50" style="font-weight: 600; box-shadow: 0 4px 10px rgba(135, 206, 235, 0.4);">
                                    <i class="bi bi-bag-check"></i> Beli Sekarang
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle"></i> Produk ini sedang tidak tersedia
                        </div>
                    @endif
                @else
                    {{-- Admin / Pemilik: hanya lihat, tidak bisa beli --}}
                    <div class="alert alert-info d-flex align-items-center gap-2 mt-3" role="alert">
                        <i class="bi bi-info-circle-fill fs-5"></i>
                        <div>
                            <strong>Mode Pratinjau</strong> — Anda login sebagai
                            <span class="badge bg-dark">{{ ucfirst(auth()->user()->role) }}</span>.
                            Fitur pembelian hanya tersedia untuk pelanggan.
                        </div>
                    </div>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100 shadow-sm" style="font-weight: 600;">
                    <i class="bi bi-box-arrow-in-right"></i> Login untuk Membeli
                </a>
            @endauth
        </div>
    </div>

    @if($produkSerupa->count() > 0)
        <hr class="my-5" style="border-color: var(--primary-blue); opacity: 0.2;">
        <h4 class="mb-4" style="color: var(--dark-blue); font-weight: 700;">Produk Serupa</h4>
        
        <div class="row">
            @foreach($produkSerupa as $produk)
                <div class="col-md-3 mb-4">
                    <div class="card product-card h-100">
                        <div class="product-image">
                            @if($produk->gambar)
                                <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <i class="bi bi-fish"></i>
                            @endif
                        </div>
                        <div class="card-body">
                            <h6 class="card-title fw-bold" style="color: var(--text-dark);">{{ $produk->nama_produk }}</h6>
                            <p class="card-text small"><strong style="color: var(--dark-blue);">Rp {{ number_format($produk->harga_satuan, 0, ',', '.') }}</strong></p>
                            <a href="{{ route('katalog.show', $produk) }}" class="btn btn-sm btn-outline-primary w-100">
                                <i class="bi bi-eye"></i> Lihat
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
    const decreaseBtn = document.getElementById('decreaseQty');
    const increaseBtn = document.getElementById('increaseQty');

    if (decreaseBtn) {
        decreaseBtn.addEventListener('click', function() {
            let qty = document.getElementById('qty');
            if (qty.value > 1) {
                qty.value = parseInt(qty.value) - 1;
            }
        });
    }

    if (increaseBtn) {
        increaseBtn.addEventListener('click', function() {
            let qty = document.getElementById('qty');
            let max = qty.getAttribute('max');
            if (qty.value < max) {
                qty.value = parseInt(qty.value) + 1;
            }
        });
    }
</script>
@endsection
