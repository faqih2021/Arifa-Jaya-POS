<?php

namespace App\Http\Controllers;

use App\Models\WarehouseStock;
use Illuminate\Http\Request;

class WarehouseStockController extends Controller
{
    public function index()
    {
        $stocks = WarehouseStock::with(['warehouse', 'product'])->get();
        return response()->json($stocks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'product_id' => 'required|exists:products,id',
            'current_stock' => 'required|integer',
            'minimum_stock' => 'required|integer',
            'maximum_stock' => 'required|integer',
        ]);

        $stock = WarehouseStock::create($validated);
        return response()->json($stock, 201);
    }

    public function show(WarehouseStock $warehouseStock)
    {
        return response()->json($warehouseStock->load(['warehouse', 'product']));
    }

    public function update(Request $request, WarehouseStock $warehouseStock)
    {
        $validated = $request->validate([
            'warehouse_id' => 'exists:warehouses,id',
            'product_id' => 'exists:products,id',
            'current_stock' => 'integer',
            'minimum_stock' => 'integer',
            'maximum_stock' => 'integer',
        ]);

        $warehouseStock->update($validated);
        return response()->json($warehouseStock);
    }

    public function destroy(WarehouseStock $warehouseStock)
    {
        $warehouseStock->delete();
        return response()->json(null, 204);
    }
}
