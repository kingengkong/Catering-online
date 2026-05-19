<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items.food', 'voucher'])
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.food', 'payment', 'voucher']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order->update($validated);

        // Send notification to customer
        $order->user->notify(new \App\Notifications\OrderStatusChanged($order));

        return back()->with('success', 'Order status updated');
    }

    public function verifyPayment(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $payment = $order->payment;

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payments', 'public');
            $payment->update([
                'payment_proof' => $path,
                'payment_status' => 'pending'
            ]);
        }

        return back()->with('success', 'Payment proof uploaded');
    }

    public function approvePayment(Order $order)
    {
        $payment = $order->payment;
        $payment->update([
            'payment_status' => 'paid',
            'paid_at' => now()
        ]);

        $order->update(['status' => 'processing']);

        // Increment voucher usage
        if ($order->voucher) {
            $order->voucher->increment('used_count');
        }

        $order->user->notify(new \App\Notifications\PaymentApproved($order));

        return back()->with('success', 'Payment approved');
    }

    public function exportPDF(Order $order)
    {
        $order->load(['user', 'items.food', 'payment']);
        $pdf = Pdf::loadView('admin.orders.invoice', compact('order'));
        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }
}
