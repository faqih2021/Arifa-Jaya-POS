<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WarehouseStock;
use App\Models\Warehouse;
use App\Models\StockRequest;
use App\Models\Product;
use App\Models\Supplier;
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

    // ==========================================
    // PRODUCTS METHODS
    // ==========================================

    /**
     * Display a listing of products
     */
    public function productIndex()
    {
        $products = Product::orderBy('name')->get();
        return view('storage.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product
     */
    public function productCreate()
    {
        return view('storage.products.create');
    }

    /**
     * Store a newly created product
     */
    public function productStore(Request $request)
    {
        $request->validate([
            'product_code' => 'required|string|max:50|unique:products,product_code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:30',
            'actual_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
        ]);

        Product::create($request->all());

        return redirect()->route('storage.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified product
     */
    public function productShow(Product $product)
    {
        return view('storage.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product
     */
    public function productEdit(Product $product)
    {
        return view('storage.products.edit', compact('product'));
    }

    /**
     * Update the specified product
     */
    public function productUpdate(Request $request, Product $product)
    {
        $request->validate([
            'product_code' => 'required|string|max:50|unique:products,product_code,' . $product->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:30',
            'actual_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
        ]);

        $product->update($request->all());

        return redirect()->route('storage.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified product
     */
    public function productDestroy(Product $product)
    {
        $product->delete();

        return redirect()->route('storage.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    // ==========================================
    // SUPPLIERS METHODS
    // ==========================================

    /**
     * Display a listing of suppliers
     */
    public function supplierIndex()
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('storage.suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new supplier
     */
    public function supplierCreate()
    {
        return view('storage.suppliers.create');
    }

    /**
     * Store a newly created supplier
     */
    public function supplierStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        Supplier::create($request->all());

        return redirect()->route('storage.suppliers.index')
            ->with('success', 'Supplier created successfully.');
    }

    /**
     * Display the specified supplier
     */
    public function supplierShow(Supplier $supplier)
    {
        $supplier->load('products');
        return view('storage.suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified supplier
     */
    public function supplierEdit(Supplier $supplier)
    {
        return view('storage.suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified supplier
     */
    public function supplierUpdate(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $supplier->update($request->all());

        return redirect()->route('storage.suppliers.index')
            ->with('success', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified supplier
     */
    public function supplierDestroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('storage.suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }

    // ==========================================
    // MAIN STOCKS METHODS
    // ==========================================

    /**
     * Display a listing of main warehouse stocks
     */
    public function mainStockIndex()
    {
        $user = Auth::user();
        $warehouseIds = Warehouse::where('store_id', $user->store_id)->pluck('id');

        $warehouseStocks = WarehouseStock::with(['product', 'warehouse'])
            ->whereIn('warehouse_id', $warehouseIds)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('storage.main-stocks.index', compact('warehouseStocks'));
    }

    /**
     * Show the form for creating a new warehouse stock
     */
    public function mainStockCreate()
    {
        $user = Auth::user();
        $warehouses = Warehouse::where('store_id', $user->store_id)->get();
        $products = Product::orderBy('name')->get();

        return view('storage.main-stocks.create', compact('warehouses', 'products'));
    }

    /**
     * Store a newly created warehouse stock
     */
    public function mainStockStore(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'product_id' => 'required|exists:products,id',
            'current_stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
        ]);

        WarehouseStock::create($request->all());

        return redirect()->route('storage.main-stocks.index')
            ->with('success', 'Warehouse stock created successfully.');
    }

    /**
     * Display the specified warehouse stock
     */
    public function mainStockShow(WarehouseStock $warehouseStock)
    {
        $warehouseStock->load(['product', 'warehouse']);
        return view('storage.main-stocks.show', compact('warehouseStock'));
    }

    /**
     * Show the form for editing the specified warehouse stock
     */
    public function mainStockEdit(WarehouseStock $warehouseStock)
    {
        $user = Auth::user();
        $warehouses = Warehouse::where('store_id', $user->store_id)->get();
        $products = Product::orderBy('name')->get();

        return view('storage.main-stocks.edit', compact('warehouseStock', 'warehouses', 'products'));
    }

    /**
     * Update the specified warehouse stock
     */
    public function mainStockUpdate(Request $request, WarehouseStock $warehouseStock)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'product_id' => 'required|exists:products,id',
            'current_stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
        ]);

        $warehouseStock->update($request->all());

        return redirect()->route('storage.main-stocks.index')
            ->with('success', 'Warehouse stock updated successfully.');
    }

    /**
     * Remove the specified warehouse stock
     */
    public function mainStockDestroy(WarehouseStock $warehouseStock)
    {
        $warehouseStock->delete();

        return redirect()->route('storage.main-stocks.index')
            ->with('success', 'Warehouse stock deleted successfully.');
    }

    // ==========================================
    // BRANCH STOCK REQUEST METHODS
    // ==========================================

    /**
     * Display a listing of stock requests
     */
    public function stockRequestIndex()
    {
        $user = Auth::user();
        $warehouseIds = Warehouse::where('store_id', $user->store_id)->pluck('id');

        $stockRequests = StockRequest::with(['fromWarehouse', 'toWarehouse', 'requestedBy'])
            ->where(function($q) use ($warehouseIds) {
                $q->whereIn('from_warehouse_id', $warehouseIds)
                  ->orWhereIn('to_warehouse_id', $warehouseIds);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('storage.stock-requests.index', compact('stockRequests'));
    }

    /**
     * Display the specified stock request
     */
    public function stockRequestShow(StockRequest $stockRequest)
    {
        $stockRequest->load(['fromWarehouse', 'toWarehouse', 'requestedBy', 'details.product']);
        return view('storage.stock-requests.show', compact('stockRequest'));
    }

    /**
     * Approve a stock request
     */
    public function stockRequestApprove(StockRequest $stockRequest)
    {
        $stockRequest->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        return redirect()->route('storage.stock-requests.index')
            ->with('success', 'Stock request approved successfully.');
    }

    /**
     * Reject a stock request
     */
    public function stockRequestReject(Request $request, StockRequest $stockRequest)
    {
        $stockRequest->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => Auth::id(),
            'rejection_reason' => $request->input('rejection_reason'),
        ]);

        return redirect()->route('storage.stock-requests.index')
            ->with('success', 'Stock request rejected.');
    }

    // ==========================================
    // BRANCH STORE METHODS
    // ==========================================

    /**
     * Branch Dashboard - Show overview for branch store
     */
    public function branchDashboard()
    {
        $user = Auth::user();
        $storeId = $user->store_id;

        // Get warehouse IDs for this branch store
        $warehouseIds = Warehouse::where('store_id', $storeId)->pluck('id');

        // Total Products
        $totalProducts = Product::count();

        // Get warehouse stocks for this branch
        $warehouseStocks = WarehouseStock::whereIn('warehouse_id', $warehouseIds)->get();

        // Low Stock Products (current_stock <= minimum_stock)
        $lowStockProducts = WarehouseStock::with('product')
            ->whereIn('warehouse_id', $warehouseIds)
            ->whereRaw('current_stock <= minimum_stock')
            ->count();

        // High Stock Products (current_stock > minimum_stock * 3)
        $highStockProducts = WarehouseStock::with('product')
            ->whereIn('warehouse_id', $warehouseIds)
            ->whereRaw('current_stock > minimum_stock * 3')
            ->count();

        // Total Stock quantity
        $totalStock = $warehouseStocks->sum('current_stock');

        // Recent stock requests from this branch
        $recentRequests = StockRequest::with(['fromWarehouse', 'toWarehouse'])
            ->whereIn('from_warehouse_id', $warehouseIds)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Low stock items list
        $lowStockItems = WarehouseStock::with('product')
            ->whereIn('warehouse_id', $warehouseIds)
            ->whereRaw('current_stock <= minimum_stock')
            ->orderBy('current_stock', 'asc')
            ->limit(10)
            ->get();

        return view('storage.branch.dashboard', compact(
            'totalProducts',
            'lowStockProducts',
            'highStockProducts',
            'totalStock',
            'recentRequests',
            'lowStockItems'
        ));
    }

    /**
     * Branch Stocks - Display list of stocks in this branch (Read Only)
     */
    public function branchStockIndex()
    {
        $user = Auth::user();
        $warehouseIds = Warehouse::where('store_id', $user->store_id)->pluck('id');

        $warehouseStocks = WarehouseStock::with(['product', 'warehouse'])
            ->whereIn('warehouse_id', $warehouseIds)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('storage.branch.stocks.index', compact('warehouseStocks'));
    }

    /**
     * Branch Stocks - Show detail of a stock item
     */
    public function branchStockShow(WarehouseStock $warehouseStock)
    {
        $warehouseStock->load(['product', 'warehouse']);
        return view('storage.branch.stocks.show', compact('warehouseStock'));
    }

    /**
     * Branch Request - Display list of stock requests (History)
     */
    public function branchRequestIndex()
    {
        $user = Auth::user();
        $warehouseIds = Warehouse::where('store_id', $user->store_id)->pluck('id');

        $stockRequests = StockRequest::with(['fromWarehouse', 'toWarehouse', 'details.product'])
            ->whereIn('from_warehouse_id', $warehouseIds)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('storage.branch.request.index', compact('stockRequests'));
    }

    /**
     * Branch Request - Show form to create new stock request
     */
    public function branchRequestCreate()
    {
        $user = Auth::user();

        // Get branch warehouse (from)
        $fromWarehouses = Warehouse::where('store_id', $user->store_id)->get();

        // Get main store warehouse (to)
        $mainStore = \App\Models\Store::where('is_main_store', true)->first();
        $toWarehouses = $mainStore ? Warehouse::where('store_id', $mainStore->id)->get() : collect();

        // Get products
        $products = Product::orderBy('name')->get();

        return view('storage.branch.request.create', compact('fromWarehouses', 'toWarehouses', 'products'));
    }

    /**
     * Branch Request - Store new stock request
     */
    public function branchRequestStore(Request $request)
    {
        $request->validate([
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Create stock request
        $stockRequest = StockRequest::create([
            'from_warehouse_id' => $request->from_warehouse_id,
            'to_warehouse_id' => $request->to_warehouse_id,
            'requested_by' => Auth::id(),
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        // Create stock request details
        foreach ($request->items as $item) {
            \App\Models\StockRequestDetail::create([
                'stock_request_id' => $stockRequest->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
            ]);
        }

        return redirect()->route('storage.branch.request.index')
            ->with('success', 'Permintaan stok berhasil dikirim ke gudang utama.');
    }

    /**
     * Branch Request - Show detail of a stock request
     */
    public function branchRequestShow(StockRequest $stockRequest)
    {
        $stockRequest->load(['fromWarehouse', 'toWarehouse', 'requestedBy', 'details.product']);
        return view('storage.branch.request.show', compact('stockRequest'));
    }
}
