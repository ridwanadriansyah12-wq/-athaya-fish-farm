@extends('layouts.app')

@section('title', 'Keranjang Belanja — Athaya Fish Farm')

@section('extra-css')
<style>
    .cart-section { padding: 2.5rem 0 4rem; background: #F8F9FA; min-height: 60vh; }

    /* Page header */
    .cart-page-header {
        display: flex; align-items: center; gap: 12px;
        margin-bottom: 1.75rem;
    }
    .cart-page-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(22px, 3vw, 28px);
        font-weight: 800; color: #111827; margin: 0;
    }
    .cart-count-badge {
        background: #0EA5E9; color: #fff;
        font-size: 12px; font-weight: 700;
        min-width: 24px; height: 24px;
        border-radius: 99px;
        display: flex; align-items: center; justify-content: center;
        padding: 0 6px;
    }

    /* Progress steps */
    .checkout-steps {
        display: flex; align-items: center; gap: 0;
        margin-bottom: 2rem;
    }
    .step {
        display: flex; align-items: center; gap: 8px;
        font-size: 13px; font-weight: 600;
    }
    .step-num {
        width: 28px; height: 28px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; font-weight: 700;
        flex-shrink: 0;
    }
    .step.active .step-num { background: #0EA5E9; color: #fff; }
    .step.done .step-num { background: #10B981; color: #fff; }
    .step.idle .step-num { background: #E5E7EB; color: #9CA3AF; }
    .step.active .step-label { color: #0EA5E9; }
    .step.done .step-label { color: #059669; }
    .step.idle .step-label { color: #9CA3AF; }
    .step-line { height: 2px; flex: 1; margin: 0 8px; }
    .step-line.done { background: #10B981; }
    .step-line.idle { background: #E5E7EB; }

    /* Cart items card */
    .cart-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #E5E7EB;
        box-shadow: 0 1px 6px rgba(0,0,0,.05);
        overflow: hidden;
        margin-bottom: 1.25rem;
    }
    .cart-card-header {
        padding: .9rem 1.25rem;
        border-bottom: 1px solid #F0F0F0;
        display: flex; align-items: center; gap: 8px;
    }
    .cart-card-header h5 {
        font-size: 15px; font-weight: 700;
        color: #111827; margin: 0;
    }

    /* Cart row (item) */
    .cart-item-row {
        display: grid;
        grid-template-columns: 64px 1fr auto auto auto;
        align-items: center;
        gap: 12px;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #F8F9FA;
        transition: background 150ms ease;
    }
    .cart-item-row:last-child { border-bottom: none; }
    .cart-item-row:hover { background: #FAFAFA; }

    .cart-item-img {
        width: 64px; height: 64px;
        border-radius: 10px; object-fit: cover;
        border: 1px solid #E5E7EB;
    }
    .cart-item-img-placeholder {
        width: 64px; height: 64px;
        border-radius: 10px; background: #F3F4F6;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; color: #D1D5DB;
        border: 1px solid #E5E7EB;
    }
    .cart-item-name {
        font-size: 14px; font-weight: 700; color: #111827;
        text-decoration: none; line-height: 1.3;
    }
    .cart-item-name:hover { color: #0EA5E9; }
    .cart-item-jenis { font-size: 12px; color: #9CA3AF; margin: 2px 0 4px; }
    .cart-item-price { font-size: 13px; color: #6B7280; }

    /* Qty stepper (inline) */
    .qty-stepper {
        display: inline-flex; align-items: center;
        border: 1.5px solid #E5E7EB; border-radius: 8px;
        overflow: hidden; background: #fff;
    }
    .qty-stepper-btn {
        width: 32px; height: 32px;
        background: #F8F9FA; border: none;
        font-size: 16px; font-weight: 700;
        color: #374151; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: background 150ms ease;
    }
    .qty-stepper-btn:hover { background: #E5E7EB; }
    .qty-stepper-val {
        min-width: 36px; text-align: center;
        font-weight: 700; font-size: 14px; color: #111827;
    }

    .cart-item-subtotal {
        font-size: 15px; font-weight: 800;
        color: #0EA5E9; text-align: right;
        white-space: nowrap;
    }
    .btn-remove-item {
        background: none; border: none;
        color: #D1D5DB; font-size: 18px; cursor: pointer;
        padding: 4px; border-radius: 6px;
        transition: color 150ms ease, background 150ms ease;
        display: flex; align-items: center;
    }
    .btn-remove-item:hover { color: #EF4444; background: #FEF2F2; }

    /* Mobile cart item */
    .cart-item-mobile {
        padding: 1rem 1.1rem;
        border-bottom: 1px solid #F0F0F0;
    }
    .cart-item-mobile:last-child { border-bottom: none; }

    /* Summary card */
    .summary-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #E5E7EB;
        box-shadow: 0 1px 6px rgba(0,0,0,.05);
        position: sticky; top: 80px;
    }
    .summary-header {
        background: linear-gradient(135deg, #0D1117, #161B22);
        border-radius: 16px 16px 0 0;
        padding: 1rem 1.25rem;
        display: flex; align-items: center; gap: 8px;
    }
    .summary-header h5 { color: #F0F6FC; font-size: 15px; font-weight: 700; margin: 0; }
    .summary-body { padding: 1.25rem; }

    .summary-row {
        display: flex; justify-content: space-between;
        align-items: center; margin-bottom: .6rem;
        font-size: 14px;
    }
    .summary-row-label { color: #6B7280; }
    .summary-row-val { font-weight: 600; color: #111827; }
    .summary-divider { border: none; border-top: 1px dashed #E5E7EB; margin: .75rem 0; }
    .summary-total-row {
        display: flex; justify-content: space-between;
        align-items: center; margin-bottom: 1.1rem;
    }
    .summary-total-label { font-size: 15px; font-weight: 700; color: #111827; }
    .summary-total-val { font-size: 20px; font-weight: 800; color: #0EA5E9; }

    .form-label-checkout { font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 5px; }
    .btn-checkout {
        width: 100%; padding: 14px;
        background: linear-gradient(135deg, #0EA5E9, #0284C7);
        color: #fff; border: none; border-radius: 12px;
        font-size: 15px; font-weight: 800;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        cursor: pointer;
        transition: all 200ms ease;
        box-shadow: 0 4px 14px rgba(14,165,233,.35);
    }
    .btn-checkout:hover {
        background: linear-gradient(135deg, #0284C7, #0369A1);
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(14,165,233,.45);
    }

    /* Empty cart */
    .empty-cart {
        background: #fff; border-radius: 20px;
        border: 1.5px dashed #D1D5DB;
        padding: 5rem 2rem; text-align: center;
    }
    .empty-cart-icon { font-size: 5rem; color: #D1D5DB; margin-bottom: 1.25rem; }
    .empty-cart h4 { font-family: 'Playfair Display', serif; color: #111827; margin-bottom: .5rem; }
    .empty-cart p { font-size: 14px; color: #6B7280; }
</style>
@endsection

@section('content')
<div class="cart-section">
    <div class="container">

        {{-- Header --}}
        <div class="cart-page-header animate-in">
            <h1 class="cart-page-title"><i class="bi bi-cart3" style="color:#0EA5E9;"></i> Keranjang Belanja</h1>
            @if(count($items) > 0)
                <span class="cart-count-badge">{{ array_sum(array_column($items, 'qty')) }}</span>
            @endif
        </div>

        {{-- Progress steps --}}
        <div class="checkout-steps animate-in" style="transition-delay:40ms;">
            <div class="step active">
                <div class="step-num">1</div>
                <span class="step-label d-none d-sm-inline">Keranjang</span>
            </div>
            <div class="step-line idle"></div>
            <div class="step idle">
                <div class="step-num">2</div>
                <span class="step-label d-none d-sm-inline">Pengiriman</span>
            </div>
            <div class="step-line idle"></div>
            <div class="step idle">
                <div class="step-num">3</div>
                <span class="step-label d-none d-sm-inline">Pembayaran</span>
            </div>
            <div class="step-line idle"></div>
            <div class="step idle">
                <div class="step-num">4</div>
                <span class="step-label d-none d-sm-inline">Selesai</span>
            </div>
        </div>

        @if(count($items) > 0)
            <div class="row g-4">

                {{-- Kiri: Item Keranjang --}}
                <div class="col-lg-8">
                    <div class="cart-card animate-in" style="transition-delay:60ms;">
                        <div class="cart-card-header">
                            <i class="bi bi-bag-check" style="color:#0EA5E9;font-size:1.1rem;"></i>
                            <h5>Produk Dipilih ({{ count($items) }} item)</h5>
                        </div>

                        {{-- Desktop table --}}
                        <div class="d-none d-md-block">
                            @foreach($items as $item)
                                <div class="cart-item-row">
                                    {{-- Image --}}
                                    @if($item['katalog']->first_image)
                                        <img src="{{ asset('storage/' . $item['katalog']->first_image) }}"
                                             alt="{{ $item['katalog']->nama_produk }}"
                                             class="cart-item-img">
                                    @else
                                        <div class="cart-item-img-placeholder"><i class="bi bi-fish"></i></div>
                                    @endif

                                    {{-- Info --}}
                                    <div>
                                        <a href="{{ route('katalog.show', $item['katalog']) }}" class="cart-item-name">
                                            {{ $item['katalog']->nama_produk }}
                                        </a>
                                        <p class="cart-item-jenis">{{ $item['katalog']->jenisIkan?->nama_jenis ?? '-' }}</p>
                                        <p class="cart-item-price">Rp {{ number_format($item['katalog']->harga_satuan, 0, ',', '.') }} / ekor</p>
                                    </div>

                                    {{-- Qty stepper --}}
                                    <div class="qty-stepper">
                                        <form action="{{ route('cart.update', $item['katalog']->id) }}" method="POST" style="margin:0;">
                                            @csrf
                                            <input type="hidden" name="action" value="decrease">
                                            <button type="submit" class="qty-stepper-btn">−</button>
                                        </form>
                                        <span class="qty-stepper-val">{{ $item['qty'] }}</span>
                                        <form action="{{ route('cart.update', $item['katalog']->id) }}" method="POST" style="margin:0;">
                                            @csrf
                                            <input type="hidden" name="action" value="increase">
                                            <button type="submit" class="qty-stepper-btn">+</button>
                                        </form>
                                    </div>

                                    {{-- Subtotal --}}
                                    <div class="cart-item-subtotal">
                                        Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                    </div>

                                    {{-- Remove --}}
                                    <form action="{{ route('cart.remove', $item['katalog']->id) }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="submit" class="btn-remove-item" title="Hapus">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>

                        {{-- Mobile list --}}
                        <div class="d-md-none">
                            @foreach($items as $item)
                                <div class="cart-item-mobile">
                                    <div class="d-flex gap-3 align-items-start">
                                        @if($item['katalog']->first_image)
                                            <img src="{{ asset('storage/' . $item['katalog']->first_image) }}"
                                                 alt="{{ $item['katalog']->nama_produk }}"
                                                 class="cart-item-img" style="width:56px;height:56px;">
                                        @else
                                            <div class="cart-item-img-placeholder" style="width:56px;height:56px;font-size:1.2rem;"></div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <a href="{{ route('katalog.show', $item['katalog']) }}" class="cart-item-name d-block">
                                                {{ $item['katalog']->nama_produk }}
                                            </a>
                                            <p class="cart-item-jenis">{{ $item['katalog']->jenisIkan?->nama_jenis ?? '-' }}</p>
                                            <p class="cart-item-price">Rp {{ number_format($item['katalog']->harga_satuan, 0, ',', '.') }}/ekor</p>
                                        </div>
                                        <form action="{{ route('cart.remove', $item['katalog']->id) }}" method="POST" style="margin:0;">
                                            @csrf
                                            <button type="submit" class="btn-remove-item"><i class="bi bi-trash3"></i></button>
                                        </form>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <div class="qty-stepper">
                                            <form action="{{ route('cart.update', $item['katalog']->id) }}" method="POST" style="margin:0;">
                                                @csrf
                                                <input type="hidden" name="action" value="decrease">
                                                <button type="submit" class="qty-stepper-btn">−</button>
                                            </form>
                                            <span class="qty-stepper-val">{{ $item['qty'] }}</span>
                                            <form action="{{ route('cart.update', $item['katalog']->id) }}" method="POST" style="margin:0;">
                                                @csrf
                                                <input type="hidden" name="action" value="increase">
                                                <button type="submit" class="qty-stepper-btn">+</button>
                                            </form>
                                        </div>
                                        <div class="cart-item-subtotal">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-2">
                        <a href="{{ route('katalog.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Lanjut Belanja
                        </a>
                    </div>
                </div>

                {{-- Kanan: Order Summary --}}
                <div class="col-lg-4">
                    <form action="{{ route('pesanan.store') }}" method="POST" id="form-bayar">
                        @csrf
                        <div class="summary-card animate-in" style="transition-delay:80ms;">
                            <div class="summary-header">
                                <i class="bi bi-receipt-cutoff" style="color:#0EA5E9;font-size:1.1rem;"></i>
                                <h5>Ringkasan Pesanan</h5>
                            </div>
                            <div class="summary-body">

                                {{-- Price breakdown --}}
                                <div class="summary-row">
                                    <span class="summary-row-label">Subtotal ({{ count($items) }} produk)</span>
                                    <span class="summary-row-val">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                                <div class="summary-row">
                                    <span class="summary-row-label">Biaya Layanan</span>
                                    <span class="summary-row-val" style="color:#059669;">Gratis</span>
                                </div>
                                <div class="summary-row">
                                    <span class="summary-row-label">Ongkos Kirim</span>
                                    <span class="summary-row-val" style="color:#9CA3AF;">Dihitung penjual</span>
                                </div>
                                <hr class="summary-divider">
                                <div class="summary-total-row">
                                    <span class="summary-total-label">Total Pembayaran</span>
                                    <span class="summary-total-val">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </div>

                                {{-- Alamat Pengiriman --}}
                                <div class="mb-3">
                                    <label for="alamat_pengiriman" class="form-label-checkout">
                                        <i class="bi bi-geo-alt-fill" style="color:#EF4444;"></i>
                                        Alamat Pengiriman <span style="color:#EF4444;">*</span>
                                    </label>
                                    <textarea class="form-control form-control-sm @error('alamat_pengiriman') is-invalid @enderror"
                                              id="alamat_pengiriman" name="alamat_pengiriman" rows="3"
                                              placeholder="Masukkan alamat lengkap pengiriman..."
                                              style="border-radius:10px;font-size:13px;">{{ old('alamat_pengiriman', $user->alamat) }}</textarea>
                                    @error('alamat_pengiriman')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($user->alamat)
                                        <div style="font-size:11px;color:#059669;margin-top:4px;">
                                            <i class="bi bi-check-circle"></i> Diisi otomatis dari profil. Bisa diubah.
                                        </div>
                                    @else
                                        <div style="font-size:11px;color:#6B7280;margin-top:4px;">
                                            <i class="bi bi-info-circle"></i>
                                            Belum ada alamat? <a href="{{ route('profile.edit') }}" target="_blank" style="color:#0EA5E9;">Isi di profil</a>
                                        </div>
                                    @endif
                                </div>

                                {{-- Catatan --}}
                                <div class="mb-3">
                                    <label for="catatan_pesanan" class="form-label-checkout">
                                        <i class="bi bi-chat-left-text" style="color:#9CA3AF;"></i>
                                        Catatan (opsional)
                                    </label>
                                    <textarea class="form-control form-control-sm" id="catatan_pesanan" name="catatan_pesanan"
                                              rows="2" placeholder="Permintaan khusus untuk penjual..."
                                              style="border-radius:10px;font-size:13px;">{{ old('catatan_pesanan') }}</textarea>
                                </div>

                                {{-- Payment logos --}}
                                <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:1rem;">
                                    <span style="font-size:10px;color:#9CA3AF;background:#F3F4F6;border-radius:4px;padding:3px 8px;">Midtrans</span>
                                    <span style="font-size:10px;color:#9CA3AF;background:#F3F4F6;border-radius:4px;padding:3px 8px;">GoPay</span>
                                    <span style="font-size:10px;color:#9CA3AF;background:#F3F4F6;border-radius:4px;padding:3px 8px;">OVO</span>
                                    <span style="font-size:10px;color:#9CA3AF;background:#F3F4F6;border-radius:4px;padding:3px 8px;">Bank Transfer</span>
                                    <span style="font-size:10px;color:#9CA3AF;background:#F3F4F6;border-radius:4px;padding:3px 8px;">QRIS</span>
                                </div>

                                <button type="submit" class="btn-checkout" id="btn-bayar-midtrans">
                                    <i class="bi bi-lock-fill"></i>
                                    Lanjut ke Pembayaran
                                </button>

                                <p style="font-size:11px;color:#9CA3AF;text-align:center;margin-top:.75rem;margin-bottom:0;">
                                    <i class="bi bi-shield-lock-fill" style="color:#10B981;"></i>
                                    Pembayaran aman via Midtrans
                                </p>

                            </div>
                        </div>
                    </form>
                </div>
            </div>

        @else
            {{-- Empty cart state --}}
            <div class="empty-cart animate-in">
                <div class="empty-cart-icon"><i class="bi bi-cart-x"></i></div>
                <h4>Keranjangmu masih kosong</h4>
                <p>Temukan ikan segar pilihan dari kolam budidaya kami dan mulai belanja sekarang.</p>
                <a href="{{ route('katalog.index') }}" class="btn btn-primary mt-3 px-4">
                    <i class="bi bi-fish me-2"></i> Lihat Katalog Produk
                </a>
            </div>
        @endif

    </div>
</div>
@endsection