@extends('layouts.app')

@section('title', 'Daftar Menu')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header & Filter -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <h1 class="text-3xl font-bold text-gray-900">Semua Menu</h1>

        <form action="{{ route('customer.foods.index') }}" method="GET" class="flex gap-2 w-full md:w-auto">
            <select name="category" class="border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            <input type="text" name="search" placeholder="Cari makanan..." value="{{ request('search') }}"
                   class="border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500">
            <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600">Cari</button>
        </form>
    </div>

    <!-- Grid Menu -->
    @if($foods->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($foods as $food)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300 group">
                    <a href="{{ route('customer.foods.detail', $food) }}" class="block">
                        <div class="relative h-48 overflow-hidden">
                            @if($food->image)
                                <img src="{{ asset('storage/' . $food->image) }}" alt="{{ $food->name }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif

                            @if(!$food->is_available)
                                <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                    <span class="text-white font-bold border-2 border-white px-4 py-1">STOK HABIS</span>
                                </div>
                            @endif
                        </div>

                        <div class="p-4">
                            <div class="text-xs text-orange-600 font-semibold mb-1">{{ $food->category->name }}</div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2 truncate">{{ $food->name }}</h3>
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ Str::limit($food->description, 60) }}</p>

                            <div class="flex justify-between items-center">
                                <span class="text-xl font-bold text-orange-600">Rp {{ number_format($food->price, 0, ',', '.') }}</span>
                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">Stok: {{ $food->stock }}</span>
                            </div>
                        </div>
                    </a>

                    @if($food->is_available && $food->stock > 0)
                        <div class="px-4 pb-4">
                            <form action="{{ route('customer.cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="food_id" value="{{ $food->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600 transition flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Tambah
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $foods->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-gray-500 text-lg">Tidak ada menu ditemukan.</p>
            <a href="{{ route('customer.foods.index') }}" class="text-orange-600 hover:underline mt-2 inline-block">Reset Filter</a>
        </div>
    @endif
</div>
@endsection
