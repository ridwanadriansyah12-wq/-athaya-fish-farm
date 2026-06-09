@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('extra-css')
<style>
    .cart-page { padding: 3rem 0; }
    .cart-title { font-family: 'Playfair Display', serif; font-size: 32px; font-weight: 700; color: #111827; margin-bottom: 2rem; }

    .cart-card {
        background: #fff;
        border-radius: 16px;
        border: 0.5px solid #E5E7EB;
        box-shadow: 0 4px 16px rgba(0,0,0,0.04);
        overflow: hidden;
    }

    .cart-table th {
        background: #F8F9FA;
        color: #6B7280;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #E5E7EB;
        padding: 1rem 1.5rem;
    }
    .cart-table td {
        padding: 1.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #F3F4F6;
    }

    .cart-product-title { font-size: 15px; font-weight: 600; color: #111827; margin-bottom: 4px; }
    .cart-product-cat { font-size: 13px; color: #6B7280; }

    .qty-form { display: flex; align-items: center; justify-content: center; gap: 8px; }
    .qty-input { width: 60px; text-align: center; border-radius: 8px; border: 1px solid #E5E7EB; padding: 6px; font-size: 14px; font-weight: 600; }
    .btn-update { font-size: 12px; font-weight: 600; color: #F5A623; background: transparent; border: 1px solid #F5A623; border-radius: 6px; padding: 4px 10px; transition: all 200ms ease; }
    .btn-update:hover { background: #F5A623; color: #111827; }

    .btn-remove { color: #EF4444; background: rgba(239,68,68,0.1); border: none; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 600; transition: all 200ms ease; }
    .btn-remove:hover { background: #EF4444; color: #fff; }

    .summary-card {
        background: #161B22;
        border-radius: 16px;
        padding: 2rem;
        color: #F0F6FC;
        position: sticky;
        top: 100px;
        border: 1px solid rgba(255,255,255,0.08);
    }
    .summary-title { font-family: 'Playfair Display', serif; font-size: 20px; font-weight: 700; color: #F5A623; margin-bottom: 1.5rem; }
    .summary-row { display: flex; justify-content: space-between; margin-bottom: 1rem; font-size: 14px; color: #8B949E; }
    .summary-row.total { border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1rem; margin-top: 1rem; font-size: 18px; color: #fff; font-weight: 700; }
    .summary-total-val { color: #F5A623; }

    .btn-checkout {
        display: block; width: 100%;
        background: #F5A623; color: #111827;
        border: none; border-radius: 10px;
        padding: 13px; font-size: 15px; font-weight: 700;
        text-align: center; text-decoration: none;
        transition: background 200ms ease, transform 200ms ease;
    }
    .btn-checkout:hover { background: #D4890F; color: #111827; transform: translateY(-1px); }

    .btn-continue {
        display: block; width: 100%;
        background: rgba(255,255,255,0.05); color: #8B949E;
        border: 1px solid rgba(255,255,255,0.1); border-radius: 10px;
        padding: 12px; font-size: 14px; font-weight: 600;
        text-align: center; text-decoration: none;
        transition: all 200ms ease;
        margin-top: 12px;
    }
    .btn-continue:hover { background: rgba(255,255,255,0.1); color: #fff; }

    .empty-cart { background: #fff; border-radius: 16px; padding: 4rem 2rem; text-align: center; border: 0.5px solid #E5E7EB; }
    .empty-cart-icon { font-size: 4rem; color: #D1D5DB; margin-bottom: 1rem; }
</style>
@endsection

@section('content')
<div class="container cart-page">
    <h1 class="cart-title animate-in"><i class="bi bi-cart3 me-2" style="color:#F5A623"></i>Keranjang Belanja</h1>

    @if($keranjang->count() > 0)
    <div class="row g-5">
        <!-- Cart Items -->
        <div class="col-lg-8 animate-in" style="transition-delay:60ms">
            <div class="cart-card">
                <div class="table-responsive">
                    <table class="table cart-table mb-0">
                        <thead>
                            <tr>
                                <th class="text-start">Produk</th>
                                <th class="text-end">Harga</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Subtotal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($keranjang as $item)
                            <tr>
                                <td class="text-start">
                                    <div class="cart-product-title">{{ $item->menu->nama_produk ?? $item->menu->Nama_Produk }}</div>
                                    <div class="cart-product-cat">Kategori: {{ $item->menu->jenisIkan->nama_jenis ?? $item->menu->Kategori ?? 'Umum' }}</div>
                                </td>
                                <td class="text-end fw-semibold">Rp {{ number_format($item->menu->harga_satuan ?? $item->menu->Harga, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <form action="{{ route('customer.cart.update', $item->id) }}" method="POST" class="qty-form">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="jumlah" value="{{ $item->jumlah }}" min="1" class="qty-input">
                                        <button type="submit" class="btn-update">Update</button>
                                    </form>
                                </td>
                                <td class="text-end fw-bold" style="color:#111827">
                                    Rp {{ number_format($item->jumlah * ($item->menu->harga_satuan ?? $item->menu->Harga), 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('customer.cart.remove', $item->id) }}" method="POST" class="confirm-delete" data-title="Hapus dari keranjang?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-remove"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Cart Summary -->
        <div class="col-lg-4 animate-in" style="transition-delay:120ms">
            <div class="summary-card">
                <h2 class="summary-title">Ringkasan</h2>

                @php
                    $totalHarga = $keranjang->sum(function($item) {
                        return $item->jumlah * ($item->menu->harga_satuan ?? $item->menu->Harga);
                    });
                @endphp

                <div class="summary-row">
                    <span>Subtotal</span>
                    <span class="fw-semibold text-white">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row">
                    <span>Pajak (0%)</span>
                    <span class="fw-semibold text-white">Rp 0</span>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span class="summary-total-val">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                </div>

                <div class="mt-4">
                    <a href="{{ route('customer.create-pemesanan') ?? '#' }}" class="btn-checkout">
                        <i class="bi bi-shield-check me-1"></i> Lanjutkan Checkout
                    </a>
                    <a href="{{ route('katalog.index') }}" class="btn-continue">
                        Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="empty-cart animate-in">
        <div class="empty-cart-icon"><i class="bi bi-cart-x"></i></div>
        <p style="font-size:18px;color:#6B7280;margin-bottom:1.5rem;font-weight:500;">Keranjang Anda masih kosong</p>
        <a href="{{ route('katalog.index') }}" class="btn btn-primary btn-lg px-4" style="border-radius:99px;">
            Mulai Belanja Sekarang
        </a>
    </div>
    @endif
</div>
@endsection
