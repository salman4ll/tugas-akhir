<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index($layanan_id)
    {
        $decrypted_id = Crypt::decrypt($layanan_id);
        $layanan = Layanan::with('perangkat')->findOrFail($decrypted_id);

        $layanan->encrypted_id = Crypt::encrypt($layanan->id);
        $layanan->formatted_price = formatIDR($layanan->harga_layanan);

        unset($layanan->id, $layanan->perangkat_id);

        $layanan->perangkat->encrypted_id = Crypt::encrypt($layanan->perangkat->id);
        $layanan->perangkat->formatted_price = formatIDR($layanan->perangkat->harga_perangkat);
        unset($layanan->perangkat->id, $layanan->perangkat->produk_id);

        $user = Auth::user();

        return view('product.payment_summary', compact('layanan', 'user'));
    }

    public function checkout (Request $request)
    {
        dd($request->all());
        $validator = Validator::make($request->all(), [
            'layanan_id' => 'required|exists:layanans,id',
            'perangkat_id' => 'required|exists:perangkats,id',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Process the order here...

        return redirect()->route('order.success');
    }
}
