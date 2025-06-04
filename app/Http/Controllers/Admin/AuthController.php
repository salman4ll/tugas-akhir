<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba login pakai guard admin
        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::guard('admin')->user();

            $token = $user->createToken('accessToken', [], now()->addDays(7))->plainTextToken;
            Session(['auth_token' => $token]);

            $roleName = $user->role->nama ?? null;

            return match ($roleName) {
                'logistik' => redirect()->route('admin.orders', ['type' => 'all']),
                'billing' => redirect()->route('billing.dashboard'),
                default => abort(403, 'Role tidak dikenali.'),
            };
        }

        // Jika gagal login
        return back()->withErrors(['email' => 'Email atau password salah'])->onlyInput('email');
    }


    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
