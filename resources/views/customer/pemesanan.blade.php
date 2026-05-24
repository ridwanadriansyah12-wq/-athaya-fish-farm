@extends('layouts.app')

@section('title', 'Pemesanan Saya')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Pemesanan Saya</h1>

    @if($pemesanan->count() > 0)
    <div class="space-y-4">
        @foreach($pemesanan as $item)
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <div class="text-xl font-bold mb-2">#{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</div>
                    <div class="text-gray-600">{{ $item->created_at->format('d M Y H:i') }}</div>
                </div>
                <span class="px-4 py-2 rounded font-semibold
                    @if($item->Status === 'pending') bg-yellow-100 text-yellow-700
                    @elseif($item->Status === 'confirmed') bg-blue-100 text-blue-700
                    @elseif($item->Status === 'completed') bg-green-100 text-green-700
                    @else bg-red-100 text-red-700
                    @endif">
                    {{ ucfirst($item->Status) }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4 pb-4 border-b">
                <div>
                    <div class="text-sm text-gray-600">Nama Pemesan</div>
                    <div class="font-semibold">{{ $item->Nama_Pemesan }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Alamat Pengiriman</div>
                    <div class="font-semibold">{{ $item->Alamat }}</div>
                </div>
            </div>

            <table class="w-full text-sm mb-4">
                <thead class="border-b">
                    <tr>
                        <th class="text-left py-2">Produk</th>
                        <th class="text-right py-2">Harga</th>
                        <th class="text-right py-2">Jumlah</th>
                        <th class="text-right py-2">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($item->detailPemesanan as $detail)
                    <tr class="border-b">
                        <td class="py-2">{{ $detail->menu->Nama_Produk ?? 'N/A' }}</td>
                        <td class="text-right">Rp {{ number_format($detail->Harga_partai, 0, ',', '.') }}</td>
                        <td class="text-right">{{ $detail->Jumlah }}</td>
                        <td class="text-right">Rp {{ number_format($detail->Subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="flex justify-between items-center">
                <div class="text-lg font-bold">
                    Total: <span class="text-green-600">Rp {{ number_format($item->Total_Harga, 0, ',', '.') }}</span>
                </div>
                <button class="text-blue-600 hover:underline font-semibold">Lihat Detail</button>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-gray-50 rounded-lg p-16 text-center">
        <p class="text-gray-600 text-lg mb-6">Anda belum memiliki pesanan</p>
        <a href="{{ route('customer.menu') }}" class="bg-blue-600 text-white px-8 py-3 rounded font-semibold hover:bg-blue-700 inline-block">
            Mulai Pesan Sekarang
        </a>
    </div>
    @endif
</div>
@endsection
