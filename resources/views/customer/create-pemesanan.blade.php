@extends('layouts.app')

@section('title', 'Checkout - Buat Pesanan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Checkout Pesanan</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form -->
        <div class="lg:col-span-2">
            <form action="{{ route('customer.store-pemesanan') }}" method="POST" class="bg-white rounded-lg shadow p-8">
                @csrf

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
                    <ul>
                        @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <h2 class="text-xl font-bold mb-6">Informasi Pemesan</h2>

                <div class="mb-6">
                    <label for="Nama_Pemesan" class="block text-sm font-semibold text-gray-700 mb-2">Nama Pemesan <span class="text-red-600">*</span></label>
                    <input type="text" id="Nama_Pemesan" name="Nama_Pemesan" value="{{ old('Nama_Pemesan', Auth::user()->name) }}" required class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500 @error('Nama_Pemesan') border-red-500 @enderror">
                    @error('Nama_Pemesan')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="Alamat" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Pengiriman <span class="text-red-600">*</span></label>
                    <textarea id="Alamat" name="Alamat" rows="4" required placeholder="Jln. Contoh No. 123..." class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500 @error('Alamat') border-red-500 @enderror">{{ old('Alamat', Auth::user()->alamat) }}</textarea>
                    @error('Alamat')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-8">
                    <button type="submit" class="w-full bg-green-600 text-white py-3 rounded font-bold hover:bg-green-700 text-lg">
                        Konfirmasi Pesanan
                    </button>
                </div>
            </form>
        </div>

        <!-- Summary -->
        <div>
            <div class="bg-white rounded-lg shadow p-6 sticky top-8">
                <h2 class="text-xl font-bold mb-6">Ringkasan Pesanan</h2>

                <div class="space-y-3 mb-6 max-h-96 overflow-y-auto">
                    @php
                        $totalHarga = 0;
                    @endphp

                    @foreach($keranjang as $item)
                    @php
                        $subtotal = $item->jumlah * $item->menu->Harga;
                        $totalHarga += $subtotal;
                    @endphp
                    <div class="flex justify-between border-b pb-3">
                        <div>
                            <div class="font-semibold text-sm">{{ $item->menu->Nama_Produk }}</div>
                            <div class="text-xs text-gray-600">{{ $item->jumlah }}x Rp {{ number_format($item->menu->Harga, 0, ',', '.') }}</div>
                        </div>
                        <div class="font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                    </div>
                    @endforeach
                </div>

                <div class="space-y-3 border-t pt-6">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span class="font-semibold">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Pajak</span>
                        <span class="font-semibold">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-lg border-t pt-3">
                        <span class="font-bold">Total</span>
                        <span class="font-bold text-green-600">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                    </div>
                </div>

                <a href="{{ route('customer.cart') }}" class="block text-center text-blue-600 hover:underline mt-6 font-semibold">
                    ← Kembali ke Keranjang
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
