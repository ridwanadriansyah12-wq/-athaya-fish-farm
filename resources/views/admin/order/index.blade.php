@extends('layouts.app')

@section('title', 'Manage Orders')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Manage Orders</h1>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="text-left px-6 py-3">Order ID</th>
                        <th class="text-left px-6 py-3">Customer</th>
                        <th class="text-left px-6 py-3">Alamat</th>
                        <th class="text-right px-6 py-3">Total</th>
                        <th class="text-center px-6 py-3">Status</th>
                        <th class="text-center px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-semibold">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4">{{ $order->user->name }}</td>
                        <td class="px-6 py-4 text-sm">{{ Str::limit($order->Alamat, 30) }}</td>
                        <td class="px-6 py-4 text-right font-semibold">Rp {{ number_format($order->Total_Harga, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded text-sm font-semibold
                                @if($order->Status === 'pending') bg-yellow-100 text-yellow-700
                                @elseif($order->Status === 'confirmed') bg-blue-100 text-blue-700
                                @elseif($order->Status === 'completed') bg-green-100 text-green-700
                                @else bg-red-100 text-red-700
                                @endif">
                                {{ ucfirst($order->Status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.order.show', $order->id) }}" class="text-blue-600 hover:underline text-sm font-semibold">
                                View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-600">
                            Tidak ada pesanan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
