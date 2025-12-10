<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // Check if user has one of the allowed roles
        if (!in_array($user->roles, $roles)) {
            // Redirect to appropriate dashboard based on their actual role
            return $this->redirectToOwnDashboard($user);
        }

        return $next($request);
    }

    /**
     * Redirect user to their own dashboard
     */
    protected function redirectToOwnDashboard($user): Response
    {
        $message = 'Anda tidak memiliki akses ke halaman tersebut.';

        switch ($user->roles) {
            case 'superadmin':
                return redirect()->route('superadmin.dashboard')
                    ->with('error', $message);
            case 'cashier':
                return redirect()->route('cashier.dashboard')
                    ->with('error', $message);
            case 'storage':
                return redirect()->route('storage.dashboard')
                    ->with('error', $message);
            default:
                return redirect()->route('login')
                    ->with('error', 'Role tidak valid.');
        }
    }
}
