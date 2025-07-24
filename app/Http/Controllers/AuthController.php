<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\CpCustomer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            $token = $user->createToken('accessToken', [], now()->addDays(7))->plainTextToken;
            Session(['auth_token' => $token]);

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'username' => 'username atau password salah',
        ])->onlyInput('username');
    }


    public function register(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nama_perusahaan' => ['required'],
            'npwp_perusahaan' => ['required', 'unique:tbl_customer,npwp_perusahaan'],
            'email_perusahaan' => ['required', 'email', 'unique:tbl_customer,email_perusahaan'],
            'no_telp_perusahaan' => ['required', 'unique:tbl_customer,no_telp_perusahaan'],
            'username' => ['required', 'unique:tbl_customer,username'],
            'nama' => ['required'],
            'email' => ['required', 'email', 'unique:tbl_cp_customer,email'],
            'no_telp' => ['required', 'unique:tbl_cp_customer,no_telp'],
            'status_perusahaan' => ['required'],
            'provinsi_id' => ['required'],
            'kabupaten_id' => ['required'],
            'kecamatan_id' => ['required'],
            'kelurahan_id' => ['required'],
            'rt' => ['required'],
            'rw' => ['required'],
            'kode_pos' => ['required'],
            'detail_alamat' => ['required'],
            'latitude' => ['required'],
            'longitude' => ['required'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'nama_perusahaan' => $request->nama_perusahaan,
            'email_perusahaan' => $request->email_perusahaan,
            'npwp_perusahaan' => $request->npwp_perusahaan,
            'no_telp_perusahaan' => $request->no_telp_perusahaan,
            'username' => $request->username,
            'status_perusahaan' => $request->status_perusahaan,
            'password' => Hash::make($request->password)
        ]);

        CpCustomer::create([
            'customer_id' => $user->id,
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
        ]);

        Address::create([
            'customer_id' => $user->id,
            'provinsi_id' => $request->provinsi_id,
            'kabupaten_id' => $request->kabupaten_id,
            'kecamatan_id' => $request->kecamatan_id,
            'kelurahan_id' => $request->kelurahan_id,
            'rt' => $request->rt,
            'rw' => $request->rw,
            'alamat' => $request->detail_alamat,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'kode_pos' => $request->kode_pos,
        ]);

        Auth::login($user);

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
