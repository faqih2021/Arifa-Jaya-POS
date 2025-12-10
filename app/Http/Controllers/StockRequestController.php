<?php

namespace App\Http\Controllers;

use App\Models\StockRequest;
use Illuminate\Http\Request;

class StockRequestController extends Controller
{
    public function index()
    {
        $requests = StockRequest::with(['requestedByUser', 'fromWarehouse', 'toWarehouse', 'approvedByUser', 'details'])->get();
        return response()->json($requests);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'requested_by_user_id' => 'required|exists:users,id',
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id',
            'status' => 'required|in:pending,approved,rejected,completed',
            'request_date' => 'required|date',
        ]);

        $stockRequest = StockRequest::create($validated);
        return response()->json($stockRequest, 201);
    }

    public function show(StockRequest $stockRequest)
    {
        return response()->json($stockRequest->load(['requestedByUser', 'fromWarehouse', 'toWarehouse', 'approvedByUser', 'details']));
    }

    public function update(Request $request, StockRequest $stockRequest)
    {
        $validated = $request->validate([
            'requested_by_user_id' => 'exists:users,id',
            'from_warehouse_id' => 'exists:warehouses,id',
            'to_warehouse_id' => 'exists:warehouses,id',
            'approved_by_user_id' => 'nullable|exists:users,id',
            'status' => 'in:pending,approved,rejected,completed',
            'request_date' => 'date',
            'approved_date' => 'nullable|date',
        ]);

        $stockRequest->update($validated);
        return response()->json($stockRequest);
    }

    public function destroy(StockRequest $stockRequest)
    {
        $stockRequest->delete();
        return response()->json(null, 204);
    }

    public function approve(Request $request, StockRequest $stockRequest)
    {
        $validated = $request->validate([
            'approved_by_user_id' => 'required|exists:users,id',
        ]);

        $stockRequest->update([
            'status' => 'approved',
            'approved_by_user_id' => $validated['approved_by_user_id'],
            'approved_date' => now(),
        ]);

        return response()->json($stockRequest);
    }

    public function reject(Request $request, StockRequest $stockRequest)
    {
        $stockRequest->update(['status' => 'rejected']);
        return response()->json($stockRequest);
    }
}
