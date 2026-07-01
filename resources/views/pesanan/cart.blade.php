@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4"><i class="bi bi-cart"></i> Keranjang Belanja</h2>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(count($items) > 0)
                <div class="row">
                    {{-- Kiri: Daftar Produk --}}
                    <div class="col-md-8">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0 fw-bold"><i class="bi bi-bag-check text-primary me-2"></i>Produk Dipilih</h5>
                            </div>
                            
                            {{-- Tampilan Desktop --}}
                            <div class="table-responsive d-none d-md-block">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Produk</th>
                                            <th class="text-center">Harga</th>
                                            <th class="text-center">Jumlah</th>
                                            <th class="text-end">Subtotal</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $item)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('katalog.show', $item['katalog']) }}"
                                                        class="text-decoration-none fw-semibold text-dark">
                                                        {{ $item['katalog']->nama_produk }}
                                                    </a><br>
                                                    <small
                                                        class="text-muted">{{ $item['katalog']->jenisIkan->nama_jenis ?? '-' }}</small>
                                                </td>
                                                <td class="text-center">
                                                    Rp {{ number_format($item['katalog']->harga_satuan, 0, ',', '.') }}
                                                </td>
                                                <td class="text-center">{{ $item['qty'] }} unit</td>
                                                <td class="text-end">
                                                    <strong class="text-success">Rp
                                                        {{ number_format($item['subtotal'], 0, ',', '.') }}</strong>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center align-items-center gap-1">
                                                        <form action="{{ route('cart.update', $item['katalog']->id) }}" method="POST"
                                                            style="margin: 0;">
                                                            @csrf
                                                            <input type="hidden" name="action" value="decrease">
                                                            <button type="submit" class="btn btn-sm btn-warning text-white"
                                                                title="Kurangi">
                                                                <i class="bi bi-dash-lg"></i>
                                                            </button>
                                                        </form>
 
                                                        <span class="mx-1 fw-bold" style="min-width: 20px;">{{ $item['qty'] }}</span>
 
                                                        <form action="{{ route('cart.update', $item['katalog']->id) }}" method="POST"
                                                            style="margin: 0;">
                                                            @csrf
                                                            <input type="hidden" name="action" value="increase">
                                                            <button type="submit" class="btn btn-sm btn-success" title="Tambah">
                                                                <i class="bi bi-plus-lg"></i>
                                                            </button>
                                                        </form>
 
                                                        <form action="{{ route('cart.remove', $item['katalog']->id) }}" method="POST"
                                                            style="margin: 0;" class="ms-2">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Tampilan Mobile --}}
                            <div class="d-md-none">
                                @foreach($items as $item)
                                    <div class="p-3 border-bottom">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <a href="{{ route('katalog.show', $item['katalog']) }}" class="text-decoration-none fw-semibold text-dark d-block">
                                                    {{ $item['katalog']->nama_produk }}
                                                </a>
                                                <small class="text-muted">{{ $item['katalog']->jenisIkan->nama_jenis ?? '-' }}</small>
                                            </div>
                                            <form action="{{ route('cart.remove', $item['katalog']->id) }}" method="POST" style="margin: 0;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Hapus">
                                                    <i class="bi bi-trash fs-5"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div class="text-muted small">
                                                Rp {{ number_format($item['katalog']->harga_satuan, 0, ',', '.') }} / unit
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <form action="{{ route('cart.update', $item['katalog']->id) }}" method="POST" style="margin: 0;">
                                                    @csrf
                                                    <input type="hidden" name="action" value="decrease">
                                                    <button type="submit" class="btn btn-xs btn-outline-secondary px-2 py-1" style="font-size: 0.75rem; padding: 2px 8px !important;" title="Kurangi">
                                                        <i class="bi bi-dash"></i>
                                                    </button>
                                                </form>
                                                <span class="fw-bold px-1" style="font-size: 0.9rem;">{{ $item['qty'] }}</span>
                                                <form action="{{ route('cart.update', $item['katalog']->id) }}" method="POST" style="margin: 0;">
                                                    @csrf
                                                    <input type="hidden" name="action" value="increase">
                                                    <button type="submit" class="btn btn-xs btn-outline-secondary px-2 py-1" style="font-size: 0.75rem; padding: 2px 8px !important;" title="Tambah">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-2 pt-2" style="border-top: 1px dashed #dee2e6;">
                                            <span class="text-muted small">Subtotal:</span>
                                            <strong class="text-success">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</strong>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-3 text-start">
                            <a href="{{ route('katalog.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-left"></i> Lanjut Belanja
                            </a>
                        </div>
                    </div>

                    {{-- Kanan: Ringkasan & Form Pembayaran Midtrans --}}
                    <div class="col-md-4">
                        {{-- Form langsung submit ke pesanan.store --}}
                        <form action="{{ route('pesanan.store') }}" method="POST" id="form-bayar">
                            @csrf

                            <div class="card shadow-sm sticky-top" style="top: 80px;">
                                <div class="card-header bg-primary text-white py-3">
                                    <h5 class="mb-0 fw-bold"><i class="bi bi-receipt me-2"></i>Ringkasan Pesanan</h5>
                                </div>
                                <div class="card-body">

                                    {{-- Rincian Harga --}}
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Subtotal Produk:</span>
                                        <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Biaya Jasa:</span>
                                        <strong>Rp 0</strong>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between mb-3">
                                        <h6 class="fw-bold mb-0">Total Pembayaran:</h6>
                                        <h6 class="fw-bold text-success mb-0">Rp {{ number_format($total, 0, ',', '.') }}</h6>
                                    </div>

                                    {{-- Alamat Pengiriman --}}
                                    <div class="mb-3">
                                        <label for="alamat_pengiriman" class="form-label small fw-semibold">
                                            <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                            Alamat Pengiriman <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control form-control-sm @error('alamat_pengiriman') is-invalid @enderror"
                                            id="alamat_pengiriman" name="alamat_pengiriman" rows="3"
                                            placeholder="Masukkan alamat lengkap pengiriman...">{{ old('alamat_pengiriman', $user->alamat) }}</textarea>
                                        @error('alamat_pengiriman')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @if($user->alamat)
                                            <div class="form-text text-success">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Diisi otomatis dari profil Anda. Bisa diubah untuk pesanan ini.
                                            </div>
                                        @else
                                            <div class="form-text text-muted">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Belum ada alamat di profil.
                                                <a href="{{ route('profile.edit') }}" class="text-primary" target="_blank">Isi profil</a>
                                                agar otomatis terisi.
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Catatan Opsional --}}
                                    <div class="mb-3">
                                        <label for="catatan_pesanan" class="form-label small fw-semibold text-muted">
                                            <i class="bi bi-chat-left-text me-1"></i>Catatan (opsional)
                                        </label>
                                        <textarea class="form-control form-control-sm" id="catatan_pesanan" name="catatan_pesanan"
                                            rows="2"
                                            placeholder="Catatan khusus untuk pesanan...">{{ old('catatan_pesanan') }}</textarea>
                                    </div>

                                    <button type="submit" class="btn btn-success btn-lg w-100 fw-bold"
                                        id="btn-bayar-midtrans">Lanjut ke Pembayaran
                                    </button>
                                </div>

                            </div>
                    </div>
                    </form>
                </div>
            </div>
        @else
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-cart-x" style="font-size: 3rem; color: var(--primary-blue);"></i>
                <h5 class="mt-3">Keranjang Anda Kosong</h5>
                <p class="text-muted">Mulai belanja sekarang dan tambahkan produk pilihan Anda</p>
                <a href="{{ route('katalog.index') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-shop"></i> Mulai Belanja
                </a>
            </div>
        </div>
    @endif
    </div>

    <style>
        .payment-method-card {
            border: 2px solid #0d6efd;
            border-radius: 10px;
            padding: 12px 14px;
            background: #f0f6ff;
        }

        .payment-gateway-section {
            margin-top: 4px;
        }

        #btn-bayar-midtrans {
            transition: all 0.2s ease;
        }

        #btn-bayar-midtrans:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(25, 135, 84, 0.35);
        }
    </style>
@endsection