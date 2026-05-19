@extends('layouts.app')
@section('title', 'Detail Pesanan')
@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('customer.orders.index') }}" class="text-gray-500 hover:text-gray-700">← Kembali ke Riwayat</a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <!-- Header Status -->
        <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold">{{ $order->order_number }}</h2>
                <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-sm font-bold
                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                @elseif($order->status == 'completed') bg-green-100 text-green-800
                @else bg-red-100 text-red-800
                @endif">
                {{ ucfirst($order->status) }}
            </span>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Items -->
            <div>
                <h3 class="font-bold text-lg mb-4">Item Pesanan</h3>
                <ul class="space-y-3">
                    @foreach($order->items as $item)
                        <li class="flex justify-between border-b pb-2">
                            <div>
                                <span class="font-semibold">{{ $item->food->name }}</span>
                                <span class="text-gray-500 text-sm"> x{{ $item->quantity }}</span>
                            </div>
                            <span class="font-medium">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Info Pengiriman & Pembayaran -->
            <div class="space-y-6">
                <div>
                    <h3 class="font-bold text-lg mb-2">Informasi Pengiriman</h3>
                    <p class="text-gray-600">{{ $order->delivery_address }}</p>
                    <p class="text-gray-600">{{ $order->delivery_phone }}</p>
                    @if($order->notes)
                        <p class="text-gray-500 text-sm mt-2 italic">Catatan: {{ $order->notes }}</p>
                    @endif
                </div>

                <div>
                    <h3 class="font-bold text-lg mb-2">Ringkasan Pembayaran</h3>
                    <div class="flex justify-between text-sm"><span>Subtotal</span><span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
                    @if($order->discount > 0)
                        <div class="flex justify-between text-sm text-green-600"><span>Diskon</span><span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span></div>
                    @endif
                    <div class="flex justify-between font-bold text-lg mt-2 border-t pt-2">
                        <span>Total</span>
                        <span class="text-orange-600">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Tombol Aksi Berdasarkan Status -->
                @if($order->status == 'pending' && $order->payment->payment_status != 'paid')
                     @if($order->payment->payment_method == 'manual')
                        <form action="{{ route('customer.payment.upload', $order) }}" method="POST" enctype="multipart/form-data" class="mt-4 border p-4 rounded bg-gray-50">
                            @csrf
                            <label class="block text-sm font-medium text-gray-700 mb-1">Upload Bukti Transfer</label>
                            <input type="file" name="payment_proof" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100"/>
                            <button type="submit" class="mt-2 w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Upload Bukti</button>
                        </form>
                     @endif
                @endif

                @if($order->status == 'completed')
                    <a href="{{ route('customer.orders.invoice', $order) }}" class="block w-full text-center bg-gray-800 text-white py-2 rounded hover:bg-gray-900 mt-4">Download Invoice PDF</a>
                @endif
            </div>
        </div>
    </div>

    <!-- Form Review (Sesuai request sebelumnya) -->
    @include('customer.orders.partials.review-form', ['order' => $order])
    <!-- Catatan: Buat file partial ini jika ingin fitur review, atau copy paste kode review dari jawaban sebelumnya ke sini -->
</div>
@endsection
