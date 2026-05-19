<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');
    }

    public function process(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $payment = $order->payment;

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => $order->total,
            ],
            'customer_details' => [
                'first_name' => $order->user->name,
                'email' => $order->user->email,
                'phone' => $order->delivery_phone,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        $payment->update([
            'transaction_id' => $snapToken
        ]);

        return view('customer.payment.process', compact('order', 'snapToken'));
    }

    public function callback(Request $request)
    {
        $serverKey = config('services.midtrans.serverKey');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            $order = Order::where('order_number', $request->order_id)->first();

            if ($order) {
                $payment = $order->payment;

                if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                    $payment->update([
                        'payment_status' => 'paid',
                        'paid_at' => now()
                    ]);

                    $order->update(['status' => 'processing']);

                    if ($order->voucher) {
                        $order->voucher->increment('used_count');
                    }
                } elseif ($request->transaction_status == 'cancel' || $request->transaction_status == 'deny' || $request->transaction_status == 'expire') {
                    $payment->update(['payment_status' => 'failed']);
                    $order->update(['status' => 'cancelled']);
                }
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function uploadProof(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

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

        return back()->with('success', 'Payment proof uploaded successfully');
    }
}
