@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-3xl font-bold mb-8">Edit Profil</h1>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
        <ul>
            @foreach($errors->all() as $error)
            <li>• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('customer.update-profile') }}" method="POST" class="bg-white rounded-lg shadow p-8">
        @csrf
        @method('PATCH')

        <div class="mb-6">
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
            <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500 @error('name') border-red-500 @enderror">
            @error('name')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500 @error('email') border-red-500 @enderror">
            @error('email')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="nomor_telepon" class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
            <input type="tel" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon', Auth::user()->nomor_telepon) }}" placeholder="+62 812..." class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500">
        </div>

        <div class="mb-6">
            <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
            <textarea id="alamat" name="alamat" rows="4" placeholder="Jln. Contoh No. 123..." class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500">{{ old('alamat', Auth::user()->alamat) }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded font-semibold hover:bg-blue-700">
                Simpan Perubahan
            </button>
            <a href="{{ route('customer.profile') }}" class="flex-1 bg-gray-200 text-gray-800 py-3 rounded font-semibold hover:bg-gray-300 text-center">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
