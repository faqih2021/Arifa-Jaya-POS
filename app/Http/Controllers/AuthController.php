<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        // Jika sudah login, redirect ke dashboard sesuai role
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username harus diisi',
            'password.required' => 'Password harus diisi',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('username'));
        }

        // Ambil credentials
        $credentials = $request->only('username', 'password');
        $remember = $request->has('remember');

        // Attempt login
        if (Auth::attempt($credentials, $remember)) {
            // Regenerate session untuk keamanan
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect berdasarkan role
            return $this->redirectBasedOnRole($user);
        }

        // Login gagal
        return back()
            ->withErrors([
                'username' => 'Username atau password salah.',
            ])
            ->withInput($request->only('username'));
    }

    /**
     * Redirect user berdasarkan role.
     */
    protected function redirectBasedOnRole($user)
    {
        $firstName = $user->first_name ?? 'User';

        switch ($user->roles) {
            case 'superadmin':
                return redirect()->route('superadmin.dashboard')
                    ->with('success', 'Selamat datang, ' . $firstName . '!');

            case 'cashier':
                return redirect()->route('cashier.dashboard')
                    ->with('success', 'Selamat datang, ' . $firstName . '!');

            case 'storage':
                return redirect()->route('storage.dashboard')
                    ->with('success', 'Selamat datang, ' . $firstName . '!');

            default:
                // Jika role tidak dikenal, logout dan redirect ke login
                Auth::logout();
                return redirect()->route('login')
                    ->with('error', 'Role tidak valid. Silakan hubungi administrator.');
        }
    }

    /**
     * Handle logout request (supports both GET and POST).
     */
    public function logout(Request $request)
    {
        // Logout user
        Auth::logout();

        // Clear session completely
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Clear all session data
        Session::flush();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil logout.');
    }
}
