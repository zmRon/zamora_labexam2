<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['product', 'payment', 'user'])->latest()->get();
        return view('orders', compact('orders'))->with('mode', 'index');
    }

    public function create()
    {
        $products = Product::where('stock_quantity', '>', 0)->get();
        return view('orders', compact('products'))->with('mode', 'create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($request->quantity > $product->stock_quantity) {
            return back()->withErrors(['quantity' => 'Quantity exceeds available stock (' . $product->stock_quantity . ' kg).'])->withInput();
        }

        $total_amount = $request->quantity * $product->price_per_kg;

        $order = Order::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'price_per_kg' => $product->price_per_kg,
            'total_amount' => $total_amount,
        ]);

        $product->decrement('stock_quantity', $request->quantity);

        Payment::create([
            'order_id' => $order->id,
            'amount_paid' => 0,
            'status' => 'Unpaid',
        ]);

        return redirect()->route('orders.index')
            ->with('success', 'Order created successfully. Total: ₱' . number_format($total_amount, 2));
    }

    public function show(Order $order)
    {
        $order->load(['product', 'payment', 'user']);
        return view('orders', compact('order'))->with('mode', 'show');
    }

    public function edit(Order $order)
    {
        $products = Product::all();
        return view('orders', compact('order', 'products'))->with('mode', 'edit');
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.1',
        ]);

        $oldProduct = Product::findOrFail($order->product_id);
        $newProduct = Product::findOrFail($request->product_id);

        $oldProduct->increment('stock_quantity', $order->quantity);

        if ($request->quantity > $newProduct->stock_quantity) {
            $oldProduct->decrement('stock_quantity', $order->quantity);
            return back()->withErrors(['quantity' => 'Quantity exceeds available stock (' . $newProduct->stock_quantity . ' kg).'])->withInput();
        }

        $total_amount = $request->quantity * $newProduct->price_per_kg;

        $newProduct->decrement('stock_quantity', $request->quantity);

        $order->update([
            'product_id' => $newProduct->id,
            'quantity' => $request->quantity,
            'price_per_kg' => $newProduct->price_per_kg,
            'total_amount' => $total_amount,
        ]);

        if ($order->payment && $order->payment->status === 'Unpaid') {
            $order->payment->update(['amount_paid' => 0]);
        }

        return redirect()->route('orders.index')
            ->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        $product = Product::find($order->product_id);
        if ($product) {
            $product->increment('stock_quantity', $order->quantity);
        }

        if ($order->payment) {
            $order->payment->delete();
        }

        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Order deleted successfully.');
    }
}
