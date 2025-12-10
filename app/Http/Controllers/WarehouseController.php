<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::with('store')->get();
        return response()->json($warehouses);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'warehouse_code' => 'required|string|unique:warehouses',
            'warehouse_type' => 'required|in:main,branch',
            'name' => 'required|string',
            'address' => 'required|string',
        ]);

        $warehouse = Warehouse::create($validated);
        return response()->json($warehouse, 201);
    }

    public function show(Warehouse $warehouse)
    {
        return response()->json($warehouse->load('store'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([
            'store_id' => 'exists:stores,id',
            'warehouse_code' => 'string|unique:warehouses,warehouse_code,' . $warehouse->id,
            'warehouse_type' => 'in:main,branch',
            'name' => 'string',
            'address' => 'string',
        ]);

        $warehouse->update($validated);
        return response()->json($warehouse);
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return response()->json(null, 204);
    }
}
