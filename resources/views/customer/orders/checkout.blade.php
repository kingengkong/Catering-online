@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold mb-8">Checkout</h1>

    <form action="{{ route('customer.orders.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Order Details -->
            <div>
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4">Informasi Pengiriman</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Pengiriman</label>
                            <textarea name="delivery_address" rows="3" required
                                      class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500">{{ old('delivery_address', auth()->user()->address) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                            <input type="text" name="delivery_phone" required
                                   value="{{ old('delivery_phone', auth()->user()->phone) }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Pesanan (Opsional)</label>
                            <textarea name="notes" rows="2"
                                      class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4">Voucher Diskon</h2>

                    <div class="flex gap-2">
                        <input type="text" name="voucher_code" placeholder="Kode Voucher"
                               value="{{ old('voucher_code') }}"
                               class="flex-1 border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500">
                        <button type="button" onclick="applyVoucher()"
                                class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                            Terapkan
                        </button>
                    </div>
                    <div id="voucher-message" class="mt-2 text-sm"></div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-4">Metode Pembayaran</h2>

                    <div class="space-y-3">
                        <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="midtrans" checked class="text-orange-500 focus:ring-orange-500">
                            <div class="ml-3">
                                <span class="block font-semibold">Midtrans Payment Gateway</span>
                                <span class="block text-sm text-gray-600">Credit Card, Bank Transfer, E-Wallet</span>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="manual" class="text-orange-500 focus:ring-orange-500">
                            <div class="ml-3">
                                <span class="block font-semibold">Transfer Manual</span>
                                <span class="block text-sm text-gray-600">Upload bukti transfer</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div>
                <div class="bg-white rounded-lg shadow p-6 sticky top-24">
                    <h2 class="text-xl font-bold mb-4">Ringkasan Pesanan</h2>

                    <div class="space-y-3 mb-4">
                        @foreach($cart->items as $item)
                            <div class="flex justify-between text-sm">
                                <span>{{ $item->food->name }} ({{ $item->quantity }}x)</span>
                                <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t pt-4 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span>Rp {{ number_format($cart->total, 0, ',', '.') }}</span>
                        </div>

                        @if($discount > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Diskon</span>
                                <span>- Rp {{ number_format($discount, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between text-lg font-bold pt-2 border-t">
                            <span>Total</span>
                            <span class="text-orange-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-6 bg-orange-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-orange-600 transition">
                        Buat Pesanan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function applyVoucher() {
    const code = document.querySelector('input[name="voucher_code"]').value;
    const messageDiv = document.getElementById('voucher-message');

    if (!code) {
        messageDiv.innerHTML = '<span class="text-red-600">Masukkan kode voucher</span>';
        return;
    }

    // AJAX call to validate voucher
    fetch('/api/vouchers/validate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ code: code })
    })
    .then(response => response.json())
    .then(data => {
        if (data.valid) {
            messageDiv.innerHTML = '<span class="text-green-600">Voucher berhasil diterapkan!</span>';
            location.reload();
        } else {
            messageDiv.innerHTML = '<span class="text-red-600">' + data.message + '</span>';
        }
    });
}
</script>
@endpush
@endsection
