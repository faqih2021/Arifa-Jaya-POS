<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index()
    {
        $memberships = Membership::with(['registeredByUser', 'registeredAtStore'])->get();
        return response()->json($memberships);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'registered_by_user_id' => 'required|exists:users,id',
            'registered_at_store_id' => 'required|exists:stores,id',
            'membership_code' => 'required|string|unique:memberships',
            'name' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'joined_at' => 'required|date',
        ]);

        $membership = Membership::create($validated);
        return response()->json($membership, 201);
    }

    public function show(Membership $membership)
    {
        return response()->json($membership->load(['registeredByUser', 'registeredAtStore']));
    }

    public function update(Request $request, Membership $membership)
    {
        $validated = $request->validate([
            'registered_by_user_id' => 'exists:users,id',
            'registered_at_store_id' => 'exists:stores,id',
            'membership_code' => 'string|unique:memberships,membership_code,' . $membership->id,
            'name' => 'string',
            'address' => 'string',
            'phone' => 'string|max:20',
            'joined_at' => 'date',
        ]);

        $membership->update($validated);
        return response()->json($membership);
    }

    public function destroy(Membership $membership)
    {
        $membership->delete();
        return response()->json(null, 204);
    }
}
