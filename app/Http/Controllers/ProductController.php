<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_code' => 'required|string|unique:products',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:30',
            'actual_price' => 'required|integer',
            'selling_price' => 'required|integer',
            'membership_price' => 'required|integer',
        ]);

        $product = Product::create($validated);
        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        return response()->json($product);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_code' => 'string|unique:products,product_code,' . $product->id,
            'name' => 'string',
            'description' => 'nullable|string',
            'unit' => 'string|max:30',
            'actual_price' => 'integer',
            'selling_price' => 'integer',
            'membership_price' => 'integer',
        ]);

        $product->update($validated);
        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
}
