@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-orange-400 to-orange-600 text-white">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl font-bold mb-4">Selamat Datang di CateringKu</h1>
            <p class="text-xl mb-8">Pesan makanan enak dan berkualitas untuk acara Anda</p>
            <a href="{{ route('customer.foods.index') }}" class="bg-white text-orange-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition inline-block">
                Lihat Menu
            </a>
        </div>
    </div>
</div>

<!-- Categories -->
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-bold mb-6">Kategori Menu</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($categories as $category)
            <a href="{{ route('customer.foods.index', ['category' => $category->id]) }}"
               class="bg-white p-4 rounded-lg shadow hover:shadow-lg transition text-center">
                @if($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-32 object-cover rounded mb-2">
                @else
                    <div class="w-full h-32 bg-gray-200 rounded mb-2 flex items-center justify-center">
                        <span class="text-4xl">🍽️</span>
                    </div>
                @endif
                <h3 class="font-semibold">{{ $category->name }}</h3>
            </a>
        @endforeach
    </div>
</div>

<!-- Popular Foods -->
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold mb-6">Menu Populer</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($popularFoods as $food)
                <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-xl transition">
                    <a href="{{ route('customer.foods.detail', $food) }}">
                        @if($food->image)
                            <img src="{{ asset('storage/' . $food->image) }}" alt="{{ $food->name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-6xl">🍴</span>
                            </div>
                        @endif
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-2">{{ $food->name }}</h3>
                            <p class="text-gray-600 text-sm mb-2">{{ Str::limit($food->description, 80) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-orange-600 font-bold">Rp {{ number_format($food->price, 0, ',', '.') }}</span>
                                <span class="text-xs text-gray-500">Stok: {{ $food->stock }}</span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- New Foods -->
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-bold mb-6">Menu Terbaru</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($newFoods as $food)
            <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-xl transition">
                <a href="{{ route('customer.foods.detail', $food) }}">
                    @if($food->image)
                        <img src="{{ asset('storage/' . $food->image) }}" alt="{{ $food->name }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <span class="text-6xl">🍴</span>
                        </div>
                    @endif
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2">{{ $food->name }}</h3>
                        <p class="text-gray-600 text-sm mb-2">{{ Str::limit($food->description, 80) }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-orange-600 font-bold">Rp {{ number_format($food->price, 0, ',', '.') }}</span>
                            <span class="text-xs text-gray-500">Stok: {{ $food->stock }}</span>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
