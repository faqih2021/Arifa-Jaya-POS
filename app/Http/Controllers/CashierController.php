<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Membership;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CashierController extends Controller
{
    /**
     * Cashier Dashboard - Show dashboard overview
     */
    public function dashboard()
    {
        $user = Auth::user();
        $storeId = $user->store_id;

        // Today's orders for this store
        $todayOrders = Order::where('store_id', $storeId)
            ->whereDate('order_date', Carbon::today())
            ->count();

        // Today's income for this store
        $todayIncome = Order::where('store_id', $storeId)
            ->where('payment_status', 'paid')
            ->whereDate('order_date', Carbon::today())
            ->sum('total_amount');

        // Total products available
        $totalProducts = Product::count();

        // Total memberships
        $totalMemberships = Membership::count();

        // Recent orders (last 10)
        $recentOrders = Order::with('membership')
            ->where('store_id', $storeId)
            ->orderBy('order_date', 'desc')
            ->limit(10)
            ->get();

        return view('cashier.dashboard', compact(
            'todayOrders',
            'todayIncome',
            'totalProducts',
            'totalMemberships',
            'recentOrders'
        ));
    }
}
