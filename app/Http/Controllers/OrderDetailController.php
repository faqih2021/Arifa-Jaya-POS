<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    public function index()
    {
        $details = OrderDetail::with(['order', 'product'])->get();
        return response()->json($details);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'order_quantity' => 'required|integer',
            'unit_price' => 'required|integer',
            'total_price' => 'required|integer',
        ]);

        $detail = OrderDetail::create($validated);
        return response()->json($detail, 201);
    }

    public function show(OrderDetail $orderDetail)
    {
        return response()->json($orderDetail->load(['order', 'product']));
    }

    public function update(Request $request, OrderDetail $orderDetail)
    {
        $validated = $request->validate([
            'order_id' => 'exists:orders,id',
            'product_id' => 'exists:products,id',
            'order_quantity' => 'integer',
            'unit_price' => 'integer',
            'total_price' => 'integer',
        ]);

        $orderDetail->update($validated);
        return response()->json($orderDetail);
    }

    public function destroy(OrderDetail $orderDetail)
    {
        $orderDetail->delete();
        return response()->json(null, 204);
    }
}
