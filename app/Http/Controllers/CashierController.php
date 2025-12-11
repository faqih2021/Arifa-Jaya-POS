<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Membership;
use App\Models\WarehouseStock;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        // Total transactions (all orders) for this store
        $totalTransactions = Order::where('store_id', $storeId)->count();

        // Total income (all orders) for this store
        $totalIncome = Order::where('store_id', $storeId)
            ->sum('total_amount');

        // Today's income for this store
        $todayIncome = Order::where('store_id', $storeId)
            ->whereDate('order_date', Carbon::today())
            ->sum('total_amount');

        // Total memberships for this store
        $totalMemberships = Membership::where('registered_at_store_id', $storeId)->count();

        // Today's orders for this store
        $todayOrders = Order::where('store_id', $storeId)
            ->whereDate('order_date', Carbon::today())
            ->count();

        // Recent orders (last 10)
        $recentOrders = Order::with('membership')
            ->where('store_id', $storeId)
            ->orderBy('order_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('cashier.dashboard', compact(
            'totalTransactions',
            'totalIncome',
            'todayIncome',
            'totalMemberships',
            'todayOrders',
            'recentOrders'
        ));
    }

    /**
     * Cart - Show cart page for creating orders
     */
    public function cart()
    {
        $user = Auth::user();
        $storeId = $user->store_id;

        // Get warehouse for this store
        $warehouse = Warehouse::where('store_id', $storeId)->first();

        // Get products with stock from this store's warehouse
        $products = collect();
        if ($warehouse) {
            $products = Product::select('products.*')
                ->join('warehouse_stocks', 'products.id', '=', 'warehouse_stocks.product_id')
                ->where('warehouse_stocks.warehouse_id', $warehouse->id)
                ->where('warehouse_stocks.current_stock', '>', 0)
                ->with(['warehouseStocks' => function($query) use ($warehouse) {
                    $query->where('warehouse_id', $warehouse->id);
                }])
                ->get();
        }

        // Get all memberships
        $memberships = Membership::orderBy('name')->get();

        // Get cart from session
        $cart = session()->get('cart', []);

        return view('cashier.cart', compact('products', 'memberships', 'cart'));
    }

    /**
     * Add product to cart
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = Auth::user();
        $product = Product::findOrFail($request->product_id);

        // Get warehouse stock for this store
        $warehouse = Warehouse::where('store_id', $user->store_id)->first();
        $stock = 0;
        if ($warehouse) {
            $warehouseStock = WarehouseStock::where('warehouse_id', $warehouse->id)
                ->where('product_id', $request->product_id)
                ->first();
            $stock = $warehouseStock ? $warehouseStock->current_stock : 0;
        }

        $cart = session()->get('cart', []);
        $currentQty = isset($cart[$request->product_id]) ? $cart[$request->product_id]['quantity'] : 0;
        $newQty = $currentQty + $request->quantity;

        // Validate stock
        if ($newQty > $stock) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi! Stok tersedia: ' . $stock
            ], 400);
        }

        if (isset($cart[$request->product_id])) {
            $cart[$request->product_id]['quantity'] = $newQty;
        } else {
            $cart[$request->product_id] = [
                'name' => $product->name,
                'price' => $product->selling_price,
                'quantity' => $request->quantity,
                'stock' => $stock,
                'product_code' => $product->product_code
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Produk ditambahkan ke keranjang',
            'cart' => $cart
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function updateCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|integer|min:0'
        ]);

        $user = Auth::user();
        $cart = session()->get('cart', []);

        if ($request->quantity == 0) {
            unset($cart[$request->product_id]);
        } else {
            // Validate stock
            $warehouse = Warehouse::where('store_id', $user->store_id)->first();
            if ($warehouse) {
                $warehouseStock = WarehouseStock::where('warehouse_id', $warehouse->id)
                    ->where('product_id', $request->product_id)
                    ->first();
                $stock = $warehouseStock ? $warehouseStock->current_stock : 0;

                if ($request->quantity > $stock) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak mencukupi! Stok tersedia: ' . $stock
                    ], 400);
                }
            }

            if (isset($cart[$request->product_id])) {
                $cart[$request->product_id]['quantity'] = $request->quantity;
            }
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Keranjang diperbarui',
            'cart' => $cart
        ]);
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required'
        ]);

        $cart = session()->get('cart', []);
        unset($cart[$request->product_id]);
        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Produk dihapus dari keranjang',
            'cart' => $cart
        ]);
    }

    /**
     * Checkout - Process the order
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,qris,transfer',
            'membership_id' => 'nullable|exists:memberships,id'
        ]);

        $user = Auth::user();
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang kosong!'
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Calculate totals
            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            // Check membership discount (fixed 5% for all members)
            $totalAmount = $subtotal;
            $isMembershipTransaction = false;
            if ($request->membership_id) {
                $membership = Membership::find($request->membership_id);
                if ($membership) {
                    // Fixed 5% discount for all members
                    $discount = ($subtotal * 5) / 100;
                    $totalAmount = $subtotal - $discount;
                    $isMembershipTransaction = true;
                }
            }

            // Generate order code (ORD001, ORD002, ... ORD999)
            $lastOrder = Order::orderBy('id', 'desc')->first();
            if ($lastOrder && preg_match('/ORD(\d+)/', $lastOrder->order_code, $matches)) {
                $nextNumber = intval($matches[1]) + 1;
            } else {
                $nextNumber = 1;
            }
            $orderCode = 'ORD' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            // Create order
            $order = Order::create([
                'store_id' => $user->store_id,
                'membership_id' => $request->membership_id,
                'cashier_user_id' => $user->id,
                'order_code' => $orderCode,
                'order_date' => Carbon::today(),
                'subtotal' => $subtotal,
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'is_membership_transaction' => $isMembershipTransaction
            ]);

            // Create order details and update stock
            $warehouse = Warehouse::where('store_id', $user->store_id)->first();

            foreach ($cart as $productId => $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'order_quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['price'] * $item['quantity']
                ]);

                // Update warehouse stock
                if ($warehouse) {
                    WarehouseStock::where('warehouse_id', $warehouse->id)
                        ->where('product_id', $productId)
                        ->decrement('current_stock', $item['quantity']);
                }
            }

            // Clear cart
            session()->forget('cart');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order berhasil dibuat!',
                'order_code' => $orderCode,
                'total' => $totalAmount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Membership - List all memberships from all stores
     */
    public function membershipIndex()
    {
        $user = Auth::user();
        $memberships = Membership::with('registeredAtStore')
            ->orderBy('created_at', 'asc')
            ->get();
        return view('cashier.membership.index', compact('memberships'));
    }

    /**
     * Membership - Show create form
     */
    public function membershipCreate()
    {
        return view('cashier.membership.create');
    }

    /**
     * Membership - Store new membership
     */
    public function membershipStore(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:20|regex:/^[A-Za-z\s]+$/',
            'phone' => 'required|string|max:20|regex:/^[0-9]+$/|unique:memberships,phone',
            'address' => 'nullable|string'
        ], [
            'name.required' => 'Nama member wajib diisi.',
            'name.max' => 'Nama maksimal 20 karakter.',
            'name.regex' => 'Nama hanya boleh berisi huruf.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'phone.regex' => 'Nomor telepon hanya boleh berisi angka.',
            'phone.unique' => 'Nomor telepon sudah terdaftar.'
        ]);

        // Generate membership code
        $lastMember = Membership::orderBy('id', 'desc')->first();
        $nextNumber = $lastMember ? intval(substr($lastMember->membership_code, 3)) + 1 : 1;
        $membershipCode = 'MBR' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        Membership::create([
            'membership_code' => $membershipCode,
            'name' => ucwords(strtolower($request->name)),
            'phone' => $request->phone,
            'address' => $request->address,
            'registered_by_user_id' => $user->id,
            'registered_at_store_id' => $user->store_id,
            'joined_at' => Carbon::today()
        ]);

        return redirect()->route('cashier.membership.index')
            ->with('success', 'Member baru berhasil ditambahkan.');
    }

    /**
     * Membership - Show edit form
     */
    public function membershipEdit(Membership $membership)
    {
        $user = Auth::user();

        // Pastikan membership milik store yang sama
        if ($membership->registered_at_store_id !== $user->store_id) {
            abort(403, 'Anda tidak memiliki akses ke member ini.');
        }

        return view('cashier.membership.edit', compact('membership'));
    }

    /**
     * Membership - Update membership
     */
    public function membershipUpdate(Request $request, Membership $membership)
    {
        $user = Auth::user();

        // Pastikan membership milik store yang sama
        if ($membership->registered_at_store_id !== $user->store_id) {
            abort(403, 'Anda tidak memiliki akses ke member ini.');
        }

        $request->validate([
            'name' => 'required|string|max:20|regex:/^[A-Za-z\s]+$/',
            'phone' => 'required|string|max:20|regex:/^[0-9]+$/|unique:memberships,phone,' . $membership->id,
            'address' => 'nullable|string'
        ], [
            'name.required' => 'Nama member wajib diisi.',
            'name.max' => 'Nama maksimal 20 karakter.',
            'name.regex' => 'Nama hanya boleh berisi huruf.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'phone.regex' => 'Nomor telepon hanya boleh berisi angka.',
            'phone.unique' => 'Nomor telepon sudah terdaftar.'
        ]);

        $membership->update([
            'name' => ucwords(strtolower($request->name)),
            'phone' => $request->phone,
            'address' => $request->address
        ]);

        return redirect()->route('cashier.membership.index')
            ->with('success', 'Data member berhasil diperbarui.');
    }

    /**
     * Membership - Delete membership
     */
    public function membershipDestroy(Membership $membership)
    {
        $user = Auth::user();

        // Pastikan membership milik store yang sama
        if ($membership->registered_at_store_id !== $user->store_id) {
            abort(403, 'Anda tidak memiliki akses ke member ini.');
        }

        // Check if membership has orders
        if ($membership->orders()->exists()) {
            return redirect()->route('cashier.membership.index')
                ->with('error', 'Member tidak dapat dihapus karena memiliki riwayat transaksi.');
        }

        $membership->delete();

        return redirect()->route('cashier.membership.index')
            ->with('success', 'Member berhasil dihapus.');
    }

    /**
     * Membership - Toggle active status
     */
    public function membershipToggle(Membership $membership)
    {
        $user = Auth::user();

        // Pastikan membership milik store yang sama
        if ($membership->registered_at_store_id !== $user->store_id) {
            abort(403, 'Anda tidak memiliki akses ke member ini.');
        }

        $membership->update(['is_active' => !$membership->is_active]);

        $status = $membership->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('cashier.membership.index')
            ->with('success', "Member berhasil {$status}.");
    }

    /**
     * Membership - Lookup by code (for cart)
     */
    public function membershipLookup(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:6'
        ]);

        $code = strtoupper($request->code);
        $membership = Membership::where('membership_code', $code)
            ->where('is_active', true)
            ->first();

        if (!$membership) {
            return response()->json([
                'success' => false,
                'message' => 'Kode member tidak ditemukan atau tidak aktif.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'member' => [
                'id' => $membership->id,
                'code' => $membership->membership_code,
                'name' => $membership->name,
            ]
        ]);
    }

    /**
     * History - Show order history
     */
    public function history()
    {
        $user = Auth::user();
        $orders = Order::with(['membership', 'cashierUser', 'details'])
            ->where('store_id', $user->store_id)
            ->orderBy('order_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cashier.history', compact('orders'));
    }

    /**
     * History - Show order detail
     */
    public function historyDetail(Order $order)
    {
        $user = Auth::user();

        // Make sure cashier can only see orders from their store
        if ($order->store_id !== $user->store_id) {
            abort(403, 'Anda tidak memiliki akses ke order ini.');
        }

        $order->load(['membership', 'cashierUser', 'details.product']);

        return view('cashier.history-detail', compact('order'));
    }
}
