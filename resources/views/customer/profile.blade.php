@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-3xl font-bold mb-8">Profil Saya</h1>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow p-8">
        <div class="space-y-6">
            <div class="flex items-center gap-6 pb-6 border-b">
                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center text-4xl">
                    👤
                </div>
                <div>
                    <h2 class="text-2xl font-bold">{{ Auth::user()->name }}</h2>
                    <p class="text-gray-600">{{ Auth::user()->email }}</p>
                    <span class="inline-block mt-2 text-sm bg-blue-100 text-blue-700 px-3 py-1 rounded capitalize">
                        {{ Auth::user()->role }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama</label>
                    <div class="text-gray-800">{{ Auth::user()->name }}</div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <div class="text-gray-800">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                    <div class="text-gray-800">{{ Auth::user()->nomor_telepon ?? '-' }}</div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                    <div class="text-gray-800">{{ Auth::user()->alamat ?? '-' }}</div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Bergabung Sejak</label>
                <div class="text-gray-800">{{ Auth::user()->created_at->format('d M Y') }}</div>
            </div>

            <div class="border-t pt-6 flex gap-3">
                <a href="{{ route('customer.edit-profile') }}" class="flex-1 bg-blue-600 text-white py-3 rounded font-semibold hover:bg-blue-700 text-center">
                    Edit Profil
                </a>
                <a href="{{ route('customer.dashboard') }}" class="flex-1 bg-gray-200 text-gray-800 py-3 rounded font-semibold hover:bg-gray-300 text-center">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
