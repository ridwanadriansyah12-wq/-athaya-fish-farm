@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Keranjang Belanja</h1>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
    @endif

    @if($keranjang->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="text-left px-6 py-3">Produk</th>
                            <th class="text-right px-6 py-3">Harga</th>
                            <th class="text-center px-6 py-3">Jumlah</th>
                            <th class="text-right px-6 py-3">Subtotal</th>
                            <th class="text-center px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($keranjang as $item)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="font-semibold">{{ $item->menu->Nama_Produk }}</div>
                                <div class="text-sm text-gray-600">Kategori: {{ $item->menu->Kategori }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">Rp {{ number_format($item->menu->Harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('customer.cart.update', $item->id) }}" method="POST" class="flex items-center justify-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="jumlah" value="{{ $item->jumlah }}" min="1" class="w-16 border rounded px-2 py-1 text-center">
                                    <button type="submit" class="text-blue-600 hover:underline text-sm">Update</button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right font-semibold">
                                Rp {{ number_format($item->jumlah * $item->menu->Harga, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('customer.cart.remove', $item->id) }}" method="POST" onsubmit="return confirm('Hapus item ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-semibold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Cart Summary -->
        <div class="bg-white rounded-lg shadow p-6 h-fit">
            <h2 class="text-xl font-bold mb-4">Ringkasan</h2>
            
            @php
                $totalHarga = $keranjang->sum(function($item) {
                    return $item->jumlah * $item->menu->Harga;
                });
            @endphp

            <div class="space-y-3 mb-6">
                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span class="font-semibold">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Pajak (0%)</span>
                    <span class="font-semibold">Rp 0</span>
                </div>
                <div class="border-t pt-3 flex justify-between text-lg">
                    <span class="font-bold">Total</span>
                    <span class="font-bold text-green-600">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                </div>
            </div>

            <a href="{{ route('customer.create-pemesanan') }}" class="w-full bg-green-600 text-white py-3 rounded font-bold hover:bg-green-700 block text-center mb-3">
                Lanjutkan Checkout
            </a>

            <a href="{{ route('customer.menu') }}" class="w-full bg-gray-200 text-gray-800 py-2 rounded font-semibold hover:bg-gray-300 block text-center">
                Lanjut Belanja
            </a>
        </div>
    </div>
    @else
    <div class="bg-gray-50 rounded-lg p-16 text-center">
        <div class="text-gray-400 text-6xl mb-4">🛒</div>
        <p class="text-gray-600 text-lg mb-6">Keranjang Anda masih kosong</p>
        <a href="{{ route('customer.menu') }}" class="bg-blue-600 text-white px-8 py-3 rounded font-semibold hover:bg-blue-700 inline-block">
            Mulai Belanja
        </a>
    </div>
    @endif
</div>
@endsection
