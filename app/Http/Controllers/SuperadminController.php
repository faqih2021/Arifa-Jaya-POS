<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Store;
use App\Models\Order;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\WarehouseStock;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SuperadminController extends Controller
{
    /**
     * Dashboard Income - Show income overview
     */
    public function dashboard()
    {
        // Get all stores
        $stores = Store::all();

        // Total income per store (with store object for link)
        $storeIncomes = [];
        foreach ($stores as $store) {
            $storeIncomes[] = [
                'store' => $store,
                'income' => Order::where('store_id', $store->id)
                    ->sum('total_amount')
            ];
        }

        // Total income all stores
        $totalIncome = Order::sum('total_amount');

        // Today's income
        $todayIncome = Order::whereDate('order_date', Carbon::today())
            ->sum('total_amount');

        // This month income
        $monthIncome = Order::whereMonth('order_date', Carbon::now()->month)
            ->whereYear('order_date', Carbon::now()->year)
            ->sum('total_amount');

        // Income per day (last 7 days)
        $dailyIncomes = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dailyIncomes[$date->format('d M')] = Order::whereDate('order_date', $date)
                ->sum('total_amount');
        }

        // Total orders
        $totalOrders = Order::count();
        $todayOrders = Order::whereDate('order_date', Carbon::today())->count();

        return view('superadmin.dashboard', compact(
            'stores',
            'storeIncomes',
            'totalIncome',
            'todayIncome',
            'monthIncome',
            'dailyIncomes',
            'totalOrders',
            'todayOrders'
        ));
    }

    /**
     * Sales History - Show sales history per store
     */
    public function salesHistory(Store $store)
    {
        $orders = Order::with(['membership', 'cashierUser'])
            ->where('store_id', $store->id)
            ->orderBy('order_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate store statistics
        $totalIncome = $orders->sum('total_amount');
        $totalOrders = $orders->count();

        return view('superadmin.sales-history', compact(
            'store',
            'orders',
            'totalIncome',
            'totalOrders'
        ));
    }

    /**
     * Dashboard Stock - Show stock overview
     */
    public function stock()
    {
        // Total products
        $totalProducts = Product::count();

        // Get all products with total stock
        $products = Product::withSum('warehouseStocks as total_stock', 'current_stock')->get();

        // Low stock products (less than 10)
        $lowStockProducts = Product::withSum('warehouseStocks as total_stock', 'current_stock')
            ->having('total_stock', '<', 10)
            ->orHaving('total_stock', null)
            ->get();

        // High stock products (more than 100)
        $highStockProducts = Product::withSum('warehouseStocks as total_stock', 'current_stock')
            ->having('total_stock', '>', 100)
            ->get();

        // Stock per warehouse
        $warehouseStocks = DB::table('warehouse_stocks')
            ->join('warehouses', 'warehouse_stocks.warehouse_id', '=', 'warehouses.id')
            ->join('stores', 'warehouses.store_id', '=', 'stores.id')
            ->select('stores.name as store_name', DB::raw('SUM(warehouse_stocks.current_stock) as total_stock'))
            ->groupBy('stores.name')
            ->get();

        return view('superadmin.stock', compact(
            'totalProducts',
            'products',
            'lowStockProducts',
            'highStockProducts',
            'warehouseStocks'
        ));
    }

    /**
     * Employee Management - List all employees
     */
    public function employeeIndex(Request $request)
    {
        $employees = User::with('store')->orderBy('created_at', 'asc')->get();

        return view('superadmin.employees.index', compact('employees'));
    }

    /**
     * Employee Management - Show create form
     */
    public function employeeCreate()
    {
        $stores = Store::orderBy('name')->get();
        return view('superadmin.employees.create', compact('stores'));
    }

    /**
     * Employee Management - Store new employee
     */
    public function employeeStore(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:20|regex:/^[A-Za-z\s]+$/',
            'last_name' => 'required|string|max:20|regex:/^[A-Za-z\s]+$/',
            'username' => 'required|string|max:20|unique:users,username',
            'password' => 'required|string|min:8',
            'roles' => 'required|in:superadmin,cashier,storage',
            'store_id' => 'required_if:roles,cashier,storage|nullable|exists:stores,id',
        ], [
            'first_name.required' => 'Nama depan wajib diisi.',
            'first_name.max' => 'Nama depan maksimal 20 karakter.',
            'first_name.regex' => 'Nama depan hanya boleh berisi huruf dan spasi.',
            'last_name.required' => 'Nama belakang wajib diisi.',
            'last_name.max' => 'Nama belakang maksimal 20 karakter.',
            'last_name.regex' => 'Nama belakang hanya boleh berisi huruf dan spasi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'username.max' => 'Username maksimal 20 karakter.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'roles.required' => 'Role wajib dipilih.',
            'roles.in' => 'Role tidak valid.',
            'store_id.required_if' => 'Toko wajib dipilih untuk role Kasir dan Gudang.',
            'store_id.exists' => 'Toko tidak ditemukan.',
        ]);

        // Check for duplicate full name (first_name + last_name combination)
        $firstName = ucwords(strtolower($request->first_name));
        $lastName = ucwords(strtolower($request->last_name));

        $existingUser = User::whereRaw('LOWER(first_name) = ? AND LOWER(last_name) = ?', [
            strtolower($firstName),
            strtolower($lastName)
        ])->first();

        if ($existingUser) {
            return back()
                ->withErrors(['first_name' => 'Kombinasi Nama Depan dan Nama Belakang sudah digunakan oleh karyawan lain.'])
                ->withInput();
        }

        User::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'roles' => $request->roles,
            'store_id' => in_array($request->roles, ['cashier', 'storage']) ? $request->store_id : null,
        ]);

        return redirect()->route('superadmin.employees.index')
            ->with('success', 'Karyawan berhasil ditambahkan!');
    }

    /**
     * Employee Management - Show edit form
     */
    public function employeeEdit($id)
    {
        $employee = User::findOrFail($id);
        $stores = Store::orderBy('name')->get();
        return view('superadmin.employees.edit', compact('employee', 'stores'));
    }

    /**
     * Employee Management - Update employee
     */
    public function employeeUpdate(Request $request, $id)
    {
        $employee = User::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:20|regex:/^[A-Za-z\s]+$/',
            'last_name' => 'required|string|max:20|regex:/^[A-Za-z\s]+$/',
            'username' => 'required|string|max:20|unique:users,username,' . $id,
            'password' => 'nullable|string|min:8',
            'roles' => 'required|in:superadmin,cashier,storage',
            'store_id' => 'required_if:roles,cashier,storage|nullable|exists:stores,id',
        ], [
            'first_name.required' => 'Nama depan wajib diisi.',
            'first_name.max' => 'Nama depan maksimal 20 karakter.',
            'first_name.regex' => 'Nama depan hanya boleh berisi huruf dan spasi.',
            'last_name.required' => 'Nama belakang wajib diisi.',
            'last_name.max' => 'Nama belakang maksimal 20 karakter.',
            'last_name.regex' => 'Nama belakang hanya boleh berisi huruf dan spasi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'username.max' => 'Username maksimal 20 karakter.',
            'password.min' => 'Password minimal 8 karakter.',
            'roles.required' => 'Role wajib dipilih.',
            'roles.in' => 'Role tidak valid.',
            'store_id.required_if' => 'Toko wajib dipilih untuk role Kasir dan Gudang.',
            'store_id.exists' => 'Toko tidak ditemukan.',
        ]);

        // Check for duplicate full name (first_name + last_name combination), excluding current employee
        $firstName = ucwords(strtolower($request->first_name));
        $lastName = ucwords(strtolower($request->last_name));

        $existingUser = User::whereRaw('LOWER(first_name) = ? AND LOWER(last_name) = ?', [
            strtolower($firstName),
            strtolower($lastName)
        ])->where('id', '!=', $id)->first();

        if ($existingUser) {
            return back()
                ->withErrors(['first_name' => 'Kombinasi Nama Depan dan Nama Belakang sudah digunakan oleh karyawan lain.'])
                ->withInput();
        }

        $data = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'username' => $request->username,
            'roles' => $request->roles,
            'store_id' => in_array($request->roles, ['cashier', 'storage']) ? $request->store_id : null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $employee->update($data);

        return redirect()->route('superadmin.employees.index')
            ->with('success', 'Karyawan berhasil diupdate!');
    }

    /**
     * Employee Management - Delete employee
     */
    public function employeeDestroy($id)
    {
        $employee = User::findOrFail($id);

        // Prevent deleting own account
        if ($employee->id === Auth::id()) {
            return redirect()->route('superadmin.employees.index')
                ->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        $employee->delete();

        return redirect()->route('superadmin.employees.index')
            ->with('success', 'Karyawan berhasil dihapus!');
    }

    /**
     * Supplier Management - List all suppliers
     */
    public function supplierIndex(Request $request)
    {
        $query = Supplier::query();

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('supplier_code', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $suppliers = $query->orderBy('created_at', 'asc')->paginate(10);

        return view('superadmin.suppliers.index', compact('suppliers'));
    }

    /**
     * Supplier Management - Show create form
     */
    public function supplierCreate()
    {
        return view('superadmin.suppliers.create');
    }

    /**
     * Supplier Management - Store new supplier
     */
    public function supplierStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'address' => 'required|string',
            'phone' => 'required|string|max:20|regex:/^[0-9]+$/',
        ], [
            'name.required' => 'Nama supplier wajib diisi.',
            'name.max' => 'Nama supplier maksimal 50 karakter.',
            'address.required' => 'Alamat wajib diisi.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'phone.regex' => 'Nomor telepon hanya boleh berisi angka.',
        ]);

        // Auto-generate supplier code
        $lastSupplier = Supplier::orderBy('id', 'desc')->first();
        if ($lastSupplier) {
            // Extract number from last code (e.g., SUP005 -> 5)
            $lastNumber = intval(substr($lastSupplier->supplier_code, 3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $supplierCode = 'SUP' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        // Normalize data
        $name = ucwords(strtolower($request->name));
        $phone = $request->phone;
        $address = ucwords(strtolower($request->address));

        // Check for duplicate name
        $existingName = Supplier::whereRaw('LOWER(name) = ?', [strtolower($name)])->first();
        if ($existingName) {
            return back()
                ->withErrors(['name' => 'Nama supplier sudah digunakan.'])
                ->withInput();
        }

        // Check for duplicate phone
        $existingPhone = Supplier::where('phone', $phone)->first();
        if ($existingPhone) {
            return back()
                ->withErrors(['phone' => 'Nomor telepon sudah digunakan oleh supplier lain.'])
                ->withInput();
        }

        // Check for duplicate address
        $existingAddress = Supplier::whereRaw('LOWER(address) = ?', [strtolower($address)])->first();
        if ($existingAddress) {
            return back()
                ->withErrors(['address' => 'Alamat sudah digunakan oleh supplier lain.'])
                ->withInput();
        }

        Supplier::create([
            'supplier_code' => $supplierCode,
            'name' => $name,
            'address' => $address,
            'phone' => $phone,
        ]);

        return redirect()->route('superadmin.suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan!');
    }

    /**
     * Supplier Management - Show supplier detail
     */
    public function supplierShow($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('superadmin.suppliers.show', compact('supplier'));
    }

    /**
     * Supplier Management - Show edit form
     */
    public function supplierEdit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('superadmin.suppliers.edit', compact('supplier'));
    }

    /**
     * Supplier Management - Update supplier
     */
    public function supplierUpdate(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
        ], [
            'name.required' => 'Nama supplier wajib diisi.',
            'name.max' => 'Nama supplier maksimal 255 karakter.',
            'address.required' => 'Alamat wajib diisi.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
        ]);

        // Normalize data
        $name = ucwords(strtolower($request->name));
        $phone = $request->phone;
        $address = ucwords(strtolower($request->address));

        // Check for duplicate name (excluding current supplier)
        $existingName = Supplier::whereRaw('LOWER(name) = ?', [strtolower($name)])
            ->where('id', '!=', $id)->first();
        if ($existingName) {
            return back()
                ->withErrors(['name' => 'Nama supplier sudah digunakan.'])
                ->withInput();
        }

        // Check for duplicate phone (excluding current supplier)
        $existingPhone = Supplier::where('phone', $phone)
            ->where('id', '!=', $id)->first();
        if ($existingPhone) {
            return back()
                ->withErrors(['phone' => 'Nomor telepon sudah digunakan oleh supplier lain.'])
                ->withInput();
        }

        // Check for duplicate address (excluding current supplier)
        $existingAddress = Supplier::whereRaw('LOWER(address) = ?', [strtolower($address)])
            ->where('id', '!=', $id)->first();
        if ($existingAddress) {
            return back()
                ->withErrors(['address' => 'Alamat sudah digunakan oleh supplier lain.'])
                ->withInput();
        }

        $supplier->update([
            'name' => $name,
            'address' => $address,
            'phone' => $phone,
        ]);

        return redirect()->route('superadmin.suppliers.index')
            ->with('success', 'Supplier berhasil diupdate!');
    }

    /**
     * Supplier Management - Delete supplier
     */
    public function supplierDestroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->route('superadmin.suppliers.index')
            ->with('success', 'Supplier berhasil dihapus!');
    }
}
