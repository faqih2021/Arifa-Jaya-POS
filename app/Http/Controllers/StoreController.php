<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::all();
        return response()->json($stores);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'store_code' => 'required|string|unique:stores',
            'name' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'is_main_store' => 'boolean',
        ]);

        $store = Store::create($validated);
        return response()->json($store, 201);
    }

    public function show(Store $store)
    {
        return response()->json($store);
    }

    public function update(Request $request, Store $store)
    {
        $validated = $request->validate([
            'store_code' => 'string|unique:stores,store_code,' . $store->id,
            'name' => 'string',
            'address' => 'string',
            'phone' => 'string|max:20',
            'is_main_store' => 'boolean',
        ]);

        $store->update($validated);
        return response()->json($store);
    }

    public function destroy(Store $store)
    {
        $store->delete();
        return response()->json(null, 204);
    }
}
