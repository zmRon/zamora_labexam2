<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['order.product', 'order.user'])->latest()->get();
        return view('payments', compact('payments'));
    }

    public function pay(Payment $payment)
    {
        $payment->update([
            'amount_paid' => $payment->order->total_amount,
            'status'      => 'Paid',
        ]);

        return redirect()->route('payments.index')->with('success', 'Payment marked as paid.');
    }

    public function unpay(Payment $payment)
    {
        $payment->update([
            'amount_paid' => 0,
            'status'      => 'Unpaid',
        ]);

        return redirect()->route('payments.index')->with('success', 'Payment marked as unpaid.');
    }
}
