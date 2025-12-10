<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['store', 'membership', 'cashierUser', 'details'])->get();
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'membership_id' => 'nullable|exists:memberships,id',
            'cashier_user_id' => 'required|exists:users,id',
            'order_code' => 'required|string|unique:orders',
            'order_date' => 'required|date',
            'subtotal' => 'required|integer',
            'total_amount' => 'required|integer',
            'payment_method' => 'required|in:cash,card,e-wallet,transfer',
            'payment_status' => 'required|in:pending,paid,declined',
            'is_membership_transaction' => 'boolean',
        ]);

        $order = Order::create($validated);
        return response()->json($order, 201);
    }

    public function show(Order $order)
    {
        return response()->json($order->load(['store', 'membership', 'cashierUser', 'details']));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'store_id' => 'exists:stores,id',
            'membership_id' => 'nullable|exists:memberships,id',
            'cashier_user_id' => 'exists:users,id',
            'order_code' => 'string|unique:orders,order_code,' . $order->id,
            'order_date' => 'date',
            'subtotal' => 'integer',
            'total_amount' => 'integer',
            'payment_method' => 'in:cash,card,e-wallet,transfer',
            'payment_status' => 'in:pending,paid,declined',
            'is_membership_transaction' => 'boolean',
        ]);

        $order->update($validated);
        return response()->json($order);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(null, 204);
    }
}
