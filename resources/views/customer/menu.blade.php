@extends('layouts.app')

@section('title', 'Menu Produk')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-2">Menu Produk</h1>
    <p class="text-gray-600 mb-8">Jelajahi semua produk premium kami</p>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <!-- Filter/Search -->
    <div class="mb-8 flex gap-4">
        <form action="{{ route('customer.menu') }}" method="GET" class="flex gap-2">
            <input type="text" name="search" placeholder="Cari produk..." class="flex-1 border rounded px-4 py-2" value="{{ request('search') }}">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Cari</button>
        </form>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @forelse($menu as $item)
        <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
            <!-- Product Image Area -->
            <div class="bg-gradient-to-br from-blue-100 to-blue-50 h-48 flex items-center justify-center">
                <div class="text-6xl">🐟</div>
            </div>

            <div class="p-4">
                <h3 class="text-lg font-bold mb-2">{{ $item->Nama_Produk }}</h3>
                
                <div class="flex justify-between items-center mb-3">
                    <span class="text-sm bg-blue-100 text-blue-700 px-3 py-1 rounded">{{ $item->Kategori }}</span>
                    <span class="text-sm text-gray-600">
                        @if($item->Stok > 0)
                            <span class="text-green-600 font-semibold">{{ $item->Stok }} Stok</span>
                        @else
                            <span class="text-red-600 font-semibold">Habis</span>
                        @endif
                    </span>
                </div>

                <div class="mb-4 pb-4 border-b">
                    <div class="text-2xl font-bold text-green-600">Rp {{ number_format($item->Harga, 0, ',', '.') }}</div>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('customer.menu.detail', $item->id) }}" class="flex-1 bg-blue-100 text-blue-600 py-2 rounded font-semibold hover:bg-blue-200 text-center">
                        Detail
                    </a>
                    @if($item->Stok > 0)
                    <form action="{{ route('customer.cart.add', $item->id) }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="jumlah" value="1">
                        <button type="submit" class="w-full bg-green-600 text-white py-2 rounded font-semibold hover:bg-green-700">
                            + Keranjang
                        </button>
                    </form>
                    @else
                    <button disabled class="flex-1 bg-gray-300 text-gray-600 py-2 rounded font-semibold cursor-not-allowed">
                        Habis
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <p class="text-gray-600 text-lg">Tidak ada produk ditemukan</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($menu->hasPages())
    <div class="flex justify-center">
        {{ $menu->links() }}
    </div>
    @endif
</div>
@endsection
