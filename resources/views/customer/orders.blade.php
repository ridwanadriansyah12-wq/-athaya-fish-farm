@extends('layouts.app')

@section('title', 'Riwayat Pembelian')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Riwayat Pembelian</h1>

    @if($penjualan->count() > 0)
    <div class="space-y-4">
        @foreach($penjualan as $item)
        <div class="bg-white rounded-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <div class="text-xs text-gray-600 uppercase">Tanggal</div>
                    <div class="font-semibold">{{ $item->Tanggal_Penjualan->format('d M Y H:i') }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-600 uppercase">Jumlah Item</div>
                    <div class="font-semibold">{{ $item->detailPenjualan->count() }} Item</div>
                </div>
                <div>
                    <div class="text-xs text-gray-600 uppercase">Total</div>
                    <div class="font-semibold text-lg text-green-600">Rp {{ number_format($item->Total_Pendapatan, 0, ',', '.') }}</div>
                </div>
                <div>
                    <a href="#detail-{{ $item->id }}" class="text-blue-600 hover:underline font-semibold">Lihat Detail →</a>
                </div>
            </div>

            <div id="detail-{{ $item->id }}" class="mt-4 border-t pt-4 hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Produk</th>
                            <th class="text-right py-2">Harga</th>
                            <th class="text-right py-2">Qty</th>
                            <th class="text-right py-2">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($item->detailPenjualan as $detail)
                        <tr class="border-b">
                            <td class="py-2">{{ $detail->menu->Nama_Produk ?? 'N/A' }}</td>
                            <td class="text-right">Rp {{ number_format($detail->Harga, 0, ',', '.') }}</td>
                            <td class="text-right">{{ $detail->Qty }}</td>
                            <td class="text-right">Rp {{ number_format($detail->Harga * $detail->Qty, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-gray-50 rounded-lg p-16 text-center">
        <p class="text-gray-600 text-lg mb-6">Anda belum melakukan pembelian</p>
        <a href="{{ route('customer.menu') }}" class="bg-blue-600 text-white px-8 py-3 rounded font-semibold hover:bg-blue-700 inline-block">
            Mulai Belanja
        </a>
    </div>
    @endif
</div>
@endsection
