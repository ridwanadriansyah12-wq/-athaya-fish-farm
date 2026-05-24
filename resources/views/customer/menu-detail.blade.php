@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')
<div class="container mx-auto px-4 py-8">
    <button onclick="history.back()" class="text-blue-600 hover:underline mb-4">&larr; Kembali</button>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Product Image -->
        <div>
            <div class="bg-gradient-to-br from-blue-100 to-blue-50 rounded-lg h-96 flex items-center justify-center">
                <div class="text-9xl">🐟</div>
            </div>
        </div>

        <!-- Product Details -->
        <div>
            <h1 class="text-3xl font-bold mb-4">{{ $menu->Nama_Produk }}</h1>

            <div class="mb-6">
                <span class="inline-block bg-blue-100 text-blue-700 px-4 py-2 rounded font-semibold">
                    {{ $menu->Kategori }}
                </span>
            </div>

            <div class="mb-8 pb-8 border-b">
                <div class="text-4xl font-bold text-green-600 mb-2">
                    Rp {{ number_format($menu->Harga, 0, ',', '.') }}
                </div>
                <div class="text-lg mb-4">
                    @if($menu->Stok > 0)
                        <span class="text-green-600 font-semibold">{{ $menu->Stok }} Stok Tersedia</span>
                    @else
                        <span class="text-red-600 font-semibold">Habis</span>
                    @endif
                </div>
            </div>

            @if($menu->Stok > 0)
            <form action="{{ route('customer.cart.add', $menu->id) }}" method="POST" class="mb-6">
                @csrf
                <div class="flex items-center gap-4 mb-6">
                    <label class="font-semibold">Jumlah:</label>
                    <input type="number" name="jumlah" value="1" min="1" max="{{ $menu->Stok }}" class="w-24 border rounded px-3 py-2 text-center">
                </div>

                <div class="space-y-2">
                    <button type="submit" class="w-full bg-green-600 text-white py-3 rounded font-bold hover:bg-green-700 text-lg">
                        + Tambah ke Keranjang
                    </button>
                    <a href="{{ route('customer.cart') }}" class="w-full bg-blue-100 text-blue-600 py-3 rounded font-semibold hover:bg-blue-200 text-center block">
                        Lihat Keranjang
                    </a>
                </div>
            </form>
            @else
            <div class="bg-gray-100 text-gray-600 py-3 rounded font-semibold text-center mb-6">
                Produk Habis
            </div>
            @endif

            <a href="{{ route('customer.menu') }}" class="text-blue-600 hover:underline font-semibold">
                ← Kembali ke Menu
            </a>

            <!-- Product Description -->
            <div class="mt-8 pt-8 border-t">
                <h3 class="text-xl font-bold mb-4">Informasi Produk</h3>
                <div class="space-y-3 text-gray-600">
                    <p><strong>Kategori:</strong> {{ $menu->Kategori }}</p>
                    <p><strong>Harga:</strong> Rp {{ number_format($menu->Harga, 0, ',', '.') }}</p>
                    <p><strong>Stok:</strong> {{ $menu->Stok }} unit</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
