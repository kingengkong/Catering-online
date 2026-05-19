@extends('layouts.app')

@section('title', $food->name)

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-8">
            <!-- Image -->
            <div>
                @if($food->image)
                    <img src="{{ asset('storage/' . $food->image) }}" alt="{{ $food->name }}" class="w-full rounded-lg">
                @else
                    <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                        <span class="text-8xl">🍴</span>
                    </div>
                @endif
            </div>

            <!-- Details -->
            <div>
                <div class="mb-4">
                    <span class="bg-orange-100 text-orange-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                        {{ $food->category->name }}
                    </span>
                    @if($food->is_popular)
                        <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded ml-2">
                            Populer
                        </span>
                    @endif
                </div>

                <h1 class="text-3xl font-bold mb-4">{{ $food->name }}</h1>

                <p class="text-gray-600 mb-6">{{ $food->description }}</p>

                <div class="mb-6">
                    <span class="text-3xl font-bold text-orange-600">Rp {{ number_format($food->price, 0, ',', '.') }}</span>
                    <span class="text-gray-500 ml-2">/ porsi</span>
                </div>

                <div class="mb-6">
                    <span class="text-gray-600">Stok tersedia: </span>
                    <span class="font-semibold {{ $food->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $food->stock }} porsi
                    </span>
                </div>

                @if($food->is_available && $food->stock > 0)
                    <form action="{{ route('customer.cart.add') }}" method="POST" class="flex gap-4">
                        @csrf
                        <input type="hidden" name="food_id" value="{{ $food->id }}">

                        <div class="flex items-center border rounded-lg">
                            <button type="button" onclick="decrementQty()" class="px-4 py-2 text-gray-600 hover:bg-gray-100">-</button>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $food->stock }}"
                                   class="w-16 text-center border-x py-2" readonly>
                            <button type="button" onclick="incrementQty()" class="px-4 py-2 text-gray-600 hover:bg-gray-100">+</button>
                        </div>

                        <button type="submit" class="flex-1 bg-orange-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-orange-600 transition">
                            Tambah ke Keranjang
                        </button>
                    </form>
                @else
                    <button disabled class="w-full bg-gray-400 text-white px-6 py-3 rounded-lg font-semibold cursor-not-allowed">
                        Stok Habis
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Related Foods -->
    @if($relatedFoods->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-6">Menu Terkait</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @foreach($relatedFoods as $relatedFood)
                    <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-xl transition">
                        <a href="{{ route('customer.foods.detail', $relatedFood) }}">
                            @if($relatedFood->image)
                                <img src="{{ asset('storage/' . $relatedFood->image) }}" alt="{{ $relatedFood->name }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <span class="text-6xl">🍴</span>
                                </div>
                            @endif
                            <div class="p-4">
                                <h3 class="font-semibold">{{ $relatedFood->name }}</h3>
                                <p class="text-orange-600 font-bold mt-2">Rp {{ number_format($relatedFood->price, 0, ',', '.') }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
function incrementQty() {
    const input = document.getElementById('quantity');
    const max = {{ $food->stock }};
    if (parseInt(input.value) < max) {
        input.value = parseInt(input.value) + 1;
    }
}

function decrementQty() {
    const input = document.getElementById('quantity');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}
</script>
@endpush
@endsection
