<?php

namespace App\Http\Controllers;

use App\Models\StockRequestDetail;
use Illuminate\Http\Request;

class StockRequestDetailController extends Controller
{
    public function index()
    {
        $details = StockRequestDetail::with(['stockRequest', 'product'])->get();
        return response()->json($details);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'stock_request_id' => 'required|exists:stock_requests,id',
            'product_id' => 'required|exists:products,id',
            'requested_quantity' => 'required|integer',
            'approved_quantity' => 'required|integer',
        ]);

        $detail = StockRequestDetail::create($validated);
        return response()->json($detail, 201);
    }

    public function show(StockRequestDetail $stockRequestDetail)
    {
        return response()->json($stockRequestDetail->load(['stockRequest', 'product']));
    }

    public function update(Request $request, StockRequestDetail $stockRequestDetail)
    {
        $validated = $request->validate([
            'stock_request_id' => 'exists:stock_requests,id',
            'product_id' => 'exists:products,id',
            'requested_quantity' => 'integer',
            'approved_quantity' => 'integer',
        ]);

        $stockRequestDetail->update($validated);
        return response()->json($stockRequestDetail);
    }

    public function destroy(StockRequestDetail $stockRequestDetail)
    {
        $stockRequestDetail->delete();
        return response()->json(null, 204);
    }
}
