<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $cart = Cart::where('user_id', auth()->id())
            ->with('items.food')
            ->first();

        if (!$cart || $cart->items->count() === 0) {
            return redirect()->route('customer.cart.index')
                ->with('error', 'Your cart is empty');
        }

        $voucher = null;
        $discount = 0;

        if ($request->has('voucher_code') && $request->voucher_code) {
            $voucher = Voucher::where('code', $request->voucher_code)->first();
            if ($voucher && $voucher->isValid()) {
                $discount = $voucher->calculateDiscount($cart->total);
            }
        }

        $total = $cart->total - $discount;

        return view('customer.orders.checkout', compact('cart', 'voucher', 'discount', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'delivery_address' => 'required|string',
            'delivery_phone' => 'required|string',
            'notes' => 'nullable|string',
            'voucher_code' => 'nullable|string|exists:vouchers,code',
            'payment_method' => 'required|in:midtrans,manual'
        ]);

        $cart = Cart::where('user_id', auth()->id())
            ->with('items.food')
            ->first();

        if (!$cart || $cart->items->count() === 0) {
            return back()->with('error', 'Your cart is empty');
        }

        DB::beginTransaction();
        try {
            $voucher = null;
            $discount = 0;

            if ($request->voucher_code) {
                $voucher = Voucher::where('code', $request->voucher_code)->first();
                if ($voucher && $voucher->isValid()) {
                    $discount = $voucher->calculateDiscount($cart->total);
                }
            }

            $subtotal = $cart->total;
            $total = $subtotal - $discount;

            // Create Order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => auth()->id(),
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'voucher_id' => $voucher ? $voucher->id : null,
                'status' => 'pending',
                'delivery_address' => $validated['delivery_address'],
                'delivery_phone' => $validated['delivery_phone'],
                'notes' => $validated['notes'],
                'ordered_at' => now()
            ]);

            // Create Order Items
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'food_id' => $item->food_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->price * $item->quantity
                ]);

                // Reduce stock
                $item->food->decrement('stock', $item->quantity);
            }

            // Create Payment
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $validated['payment_method'],
                'amount' => $total,
                'payment_status' => 'pending'
            ]);

            // Clear cart
            $cart->items()->delete();

            DB::commit();

            // Increment voucher usage
            if ($voucher) {
                $voucher->increment('used_count');
            }

            if ($validated['payment_method'] === 'midtrans') {
                return redirect()->route('customer.payment.process', $order);
            }

            return redirect()->route('customer.orders.show', $order)
                ->with('success', 'Order created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create order: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['items.food', 'payment'])
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['items.food', 'payment', 'voucher']);

        return view('customer.orders.show', compact('order'));
    }

    public function downloadInvoice(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['items.food', 'payment', 'user']);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('customer.orders.invoice', compact('order'));
        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }
}
