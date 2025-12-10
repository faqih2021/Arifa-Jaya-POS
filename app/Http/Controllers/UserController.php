<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('store')->get();
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'store_id' => 'nullable|exists:stores,id',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'username' => 'required|string|unique:users',
            'password' => 'required|string|min:8',
            'roles' => 'required|in:superadmin,cashier,storage',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        return response()->json($user, 201);
    }

    public function show(User $user)
    {
        return response()->json($user->load('store'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'store_id' => 'nullable|exists:stores,id',
            'first_name' => 'string',
            'last_name' => 'string',
            'username' => 'string|unique:users,username,' . $user->id,
            'password' => 'string|min:8',
            'roles' => 'in:superadmin,cashier,storage',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);
        return response()->json($user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }
}
