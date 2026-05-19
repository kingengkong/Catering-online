@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold mb-8">Keranjang Belanja</h1>

    @if($cart && $cart->items->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        @foreach($cart->items as $item)
                            <div class="flex items-center gap-4 py-4 border-b last:border-b-0">
                                <div class="w-24 h-24 flex-shrink-0">
                                    @if($item->food->image)
                                        <img src="{{ asset('storage/' . $item->food->image) }}" alt="{{ $item->food->name }}" class="w-full h-full object-cover rounded">
                                    @else
                                        <div class="w-full h-full bg-gray-200 rounded flex items-center justify-center">
                                            <span class="text-2xl">🍴</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <h3 class="font-semibold">{{ $item->food->name }}</h3>
                                    <p class="text-gray-600 text-sm">Rp {{ number_format($item->price, 0, ',', '.') }} / porsi</p>
                                </div>

                                <div class="flex items-center gap-2">
                                    <form action="{{ route('customer.cart.update', $item) }}" method="POST" class="flex items-center border rounded">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" onclick="this.form.querySelector('input').value = Math.max(1, this.form.querySelector('input').value - 1); this.form.submit();"
                                                class="px-3 py-1 text-gray-600 hover:bg-gray-100">-</button>
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->food->stock }}"
                                               class="w-12 text-center border-x py-1" onchange="this.form.submit()">
                                        <button type="button" onclick="this.form.querySelector('input').value = Math.min({{ $item->food->stock }}, parseInt(this.form.querySelector('input').value) + 1); this.form.submit();"
                                                class="px-3 py-1 text-gray-600 hover:bg-gray-100">+</button>
                                    </form>

                                    <form action="{{ route('customer.cart.remove', $item) }}" method="POST" class="ml-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>

                                <div class="text-right">
                                    <p class="font-bold text-orange-600">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="p-6 bg-gray-50">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-lg font-semibold">Total:</span>
                            <span class="text-2xl font-bold text-orange-600">Rp {{ number_format($cart->total, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex gap-4">
                            <a href="{{ route('customer.foods.index') }}" class="flex-1 bg-gray-200 text-gray-800 px-6 py-3 rounded-lg font-semibold text-center hover:bg-gray-300 transition">
                                Lanjut Belanja
                            </a>
                            <a href="{{ route('customer.orders.checkout') }}" class="flex-1 bg-orange-500 text-white px-6 py-3 rounded-lg font-semibold text-center hover:bg-orange-600 transition">
                                Checkout
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div>
                <div class="bg-white rounded-lg shadow p-6 sticky top-24">
                    <h2 class="text-xl font-bold mb-4">Ringkasan Pesanan</h2>

                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Item</span>
                            <span class="font-semibold">{{ $cart->items->sum('quantity') }} porsi</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-semibold">Rp {{ number_format($cart->total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold">Total Pembayaran</span>
                            <span class="text-xl font-bold text-orange-600">Rp {{ number_format($cart->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <div class="text-6xl mb-4">🛒</div>
            <h2 class="text-2xl font-bold mb-4">Keranjang Anda Kosong</h2>
            <p class="text-gray-600 mb-6">Yuk, tambahkan menu favorit Anda!</p>
            <a href="{{ route('customer.foods.index') }}" class="bg-orange-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-orange-600 transition inline-block">
                Lihat Menu
            </a>
        </div>
    @endif
</div>
@endsection
