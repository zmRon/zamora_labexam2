<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('products', compact('products'))->with('mode', 'index');
    }

    public function create()
    {
        return view('products')->with('mode', 'create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'price_per_kg'   => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'description'    => 'nullable|string',
        ]);

        Product::create($request->only('name', 'price_per_kg', 'stock_quantity', 'description'));

        return redirect()->route('products.index')->with('success', 'Rice product added.');
    }

    public function edit(Product $product)
    {
        return view('products', compact('product'))->with('mode', 'edit');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'price_per_kg'   => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'description'    => 'nullable|string',
        ]);

        $product->update($request->only('name', 'price_per_kg', 'stock_quantity', 'description'));

        return redirect()->route('products.index')->with('success', 'Rice product updated.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Rice product deleted.');
    }
}
