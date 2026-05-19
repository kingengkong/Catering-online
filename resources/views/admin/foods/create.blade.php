@extends('layouts.app')
@section('title', 'Tambah Menu')
@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-6">Tambah Menu Baru</h1>

    <form action="{{ route('admin.foods.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow rounded-lg p-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Menu</label>
                <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Kategori</label>
                <select name="category_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Harga</label>
                <input type="number" name="price" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Stok</label>
                <input type="number" name="stock" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700">Gambar</label>
                <input type="file" name="image" required class="mt-1 block w-full">
            </div>
            <div class="flex items-center">
                <input type="checkbox" name="is_popular" value="1" class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                <label class="ml-2 block text-sm text-gray-900">Tandai sebagai Populer</label>
            </div>
        </div>
        <div class="mt-6 flex justify-end">
            <a href="{{ route('admin.foods.index') }}" class="mr-3 px-4 py-2 border rounded text-gray-700 hover:bg-gray-50">Batal</a>
            <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-700">Simpan</button>
        </div>
    </form>
</div>
@endsection
