<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WarehouseStock;
use App\Models\Warehouse;
use App\Models\StockRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StorageController extends Controller
{
    /**
     * Storage Dashboard - Show dashboard overview
     */
    public function dashboard()
    {
        $user = Auth::user();
        $storeId = $user->store_id;

        // Get warehouse IDs for this store
        $warehouseIds = Warehouse::where('store_id', $storeId)->pluck('id');

        // Get warehouse stock for this store
        $warehouseStock = WarehouseStock::whereIn('warehouse_id', $warehouseIds)
            ->sum('current_stock');

        // Total products
        $totalProducts = Product::count();

        // Pending stock requests (from or to warehouses in this store)
        $pendingRequests = StockRequest::where(function($q) use ($warehouseIds) {
                $q->whereIn('from_warehouse_id', $warehouseIds)
                  ->orWhereIn('to_warehouse_id', $warehouseIds);
            })
            ->where('status', 'pending')
            ->count();

        // Total stock requests
        $totalRequests = StockRequest::where(function($q) use ($warehouseIds) {
                $q->whereIn('from_warehouse_id', $warehouseIds)
                  ->orWhereIn('to_warehouse_id', $warehouseIds);
            })->count();

        // Recent stock requests
        $recentRequests = StockRequest::with(['fromWarehouse', 'toWarehouse', 'details.product'])
            ->where(function($q) use ($warehouseIds) {
                $q->whereIn('from_warehouse_id', $warehouseIds)
                  ->orWhereIn('to_warehouse_id', $warehouseIds);
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Low stock products in this store's warehouses
        $lowStockProducts = Product::select('products.*')
            ->join('warehouse_stocks', 'products.id', '=', 'warehouse_stocks.product_id')
            ->whereIn('warehouse_stocks.warehouse_id', $warehouseIds)
            ->whereRaw('warehouse_stocks.current_stock < warehouse_stocks.minimum_stock')
            ->orderBy('warehouse_stocks.current_stock', 'asc')
            ->limit(5)
            ->get();

        return view('storage.dashboard', compact(
            'warehouseStock',
            'totalProducts',
            'pendingRequests',
            'totalRequests',
            'recentRequests',
            'lowStockProducts'
        ));
    }
}
