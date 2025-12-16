<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WarehouseStock;
use App\Models\Warehouse;
use App\Models\StockRequest;
use App\Models\StockRequestDetail;
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:30',
            'actual_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
        ]);

        // Auto-generate product code
        $lastProduct = Product::orderBy('id', 'desc')->first();
        if ($lastProduct) {
            $lastNumber = intval(substr($lastProduct->product_code, 3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $productCode = 'PRD' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        Product::create([
            'product_code' => $productCode,
            'name' => $request->name,
            'description' => $request->description,
            'unit' => $request->unit,
            'actual_price' => $request->actual_price,
            'selling_price' => $request->selling_price,
        ]);

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
            'name' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        // Auto-generate supplier code
        $lastSupplier = Supplier::orderBy('id', 'desc')->first();
        if ($lastSupplier) {
            $lastNumber = intval(substr($lastSupplier->supplier_code, 3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $supplierCode = 'SUP' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        Supplier::create([
            'supplier_code' => $supplierCode,
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('storage.suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    /**
     * Display the specified supplier
     */
    public function supplierShow(Supplier $supplier)
    {
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
        $warehouse = Warehouse::where('store_id', $user->store_id)->first();
        $products = Product::orderBy('name')->get();

        // Get existing stocks for auto-fill
        $warehouseStocks = WarehouseStock::where('warehouse_id', $warehouse->id)
            ->get()
            ->keyBy('product_id');

        return view('storage.main-stocks.create', compact('warehouse', 'products', 'warehouseStocks'));
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
            'maximum_stock' => 'required|integer|min:0',
        ]);

        WarehouseStock::updateOrCreate(
            [
                'warehouse_id' => $request->warehouse_id,
                'product_id' => $request->product_id,
            ],
            [
                'current_stock' => $request->current_stock,
                'minimum_stock' => $request->minimum_stock,
                'maximum_stock' => $request->maximum_stock,
            ]
        );

        return redirect()->route('storage.main-stocks.index')
            ->with('success', 'Stok berhasil disimpan.');
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

        $stockRequests = StockRequest::with(['fromWarehouse', 'toWarehouse', 'requestedByUser', 'details.product'])
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
        $stockRequest->load(['fromWarehouse', 'toWarehouse', 'requestedByUser', 'details.product']);
        return view('storage.stock-requests.show', compact('stockRequest'));
    }

    /**
     * Approve a stock request
     */
    public function stockRequestApprove(Request $request, StockRequest $stockRequest)
    {
        // Update approved quantities for each detail
        if ($request->has('details')) {
            foreach ($request->details as $detailData) {
                $detail = StockRequestDetail::find($detailData['id']);
                if ($detail) {
                    $approvedQty = (int) $detailData['approved_quantity'];
                    
                    $detail->update([
                        'approved_quantity' => $approvedQty
                    ]);

                    // Skip jika approved quantity = 0
                    if ($approvedQty <= 0) {
                        continue;
                    }

                    // Update stock di gudang utama (to_warehouse) - kurangi stok terlebih dahulu
                    $mainWarehouseStock = WarehouseStock::where('warehouse_id', $stockRequest->to_warehouse_id)
                        ->where('product_id', $detail->product_id)
                        ->first();

                    if ($mainWarehouseStock && $mainWarehouseStock->current_stock >= $approvedQty) {
                        $mainWarehouseStock->decrement('current_stock', $approvedQty);
                    }

                    // Update stock di gudang cabang (from_warehouse) - tambah stok
                    $warehouseStock = WarehouseStock::where('warehouse_id', $stockRequest->from_warehouse_id)
                        ->where('product_id', $detail->product_id)
                        ->first();

                    if ($warehouseStock) {
                        $warehouseStock->increment('current_stock', $approvedQty);
                    } else {
                        // Jika belum ada record stok, buat baru
                        WarehouseStock::create([
                            'warehouse_id' => $stockRequest->from_warehouse_id,
                            'product_id' => $detail->product_id,
                            'current_stock' => $approvedQty,
                            'minimum_stock' => 0,
                            'maximum_stock' => 0,
                        ]);
                    }
                }
            }
        }

        // Update stock request status
        $stockRequest->update([
            'status' => 'approved',
            'approved_date' => now(),
            'approved_by_user_id' => Auth::user()->id,
        ]);

        return redirect()->route('storage.stock-requests.index')
            ->with('success', 'Permintaan stok berhasil disetujui.');
    }

    /**
     * Reject a stock request
     */
    public function stockRequestReject(Request $request, StockRequest $stockRequest)
    {
        // Set approved_quantity to 0 for all details
        foreach ($stockRequest->details as $detail) {
            $detail->update(['approved_quantity' => 0]);
        }

        $stockRequest->update([
            'status' => 'rejected',
            'approved_date' => now(),
            'approved_by_user_id' => Auth::user()->id,
        ]);

        return redirect()->route('storage.stock-requests.index')
            ->with('success', 'Permintaan stok ditolak.');
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

        // Get branch warehouse (from) - gudang milik user yang sedang login
        $userWarehouse = Warehouse::where('store_id', $user->store_id)->first();

        // Get main store warehouse (to)
        $mainStore = \App\Models\Store::where('is_main_store', true)->first();
        $toWarehouses = $mainStore ? Warehouse::where('store_id', $mainStore->id)->get() : collect();

        // Get products
        $products = Product::orderBy('name')->get();

        return view('storage.branch.request.create', compact('userWarehouse', 'toWarehouses', 'products'));
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
            'requested_by_user_id' => Auth::user()->id,
            'request_date' => now()->toDateString(),
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        // Create stock request details
        foreach ($request->items as $item) {
            \App\Models\StockRequestDetail::create([
                'stock_request_id' => $stockRequest->id,
                'product_id' => $item['product_id'],
                'requested_quantity' => $item['quantity'],
                'approved_quantity' => 0,
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
        $stockRequest->load(['fromWarehouse', 'toWarehouse', 'requestedByUser', 'details.product']);
        return view('storage.branch.request.show', compact('stockRequest'));
    }
}
