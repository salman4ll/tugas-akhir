<?php

namespace App\Http\Controllers;

use App\Models\FaqKategoriProduk;
use App\Models\KategoriProduk;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::query();

        // Filter berdasarkan kata kunci pencarian
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_produk', 'like', "%$search%")
                    ->orWhere('deskripsi', 'like', "%$search%");
            });
        }

        // Ambil data dan enkripsi id
        $products = $query->get()->map(function ($product) {
            $product->encrypted_id = Crypt::encrypt($product->id);
            unset($product->id);
            return $product;
        });

        return view('product.index', compact('products'));
    }

    public function detail($id)
    {
        $id = Crypt::decrypt($id);
        $product = Produk::with(['perangkat', 'perangkat.layanan', 'faq_produk'])->findOrFail($id);

        $product->encrypted_id = Crypt::encrypt($product->id);
        unset($product->id);

        foreach ($product->perangkat as $perangkat) {
            $perangkat->encrypted_id = Crypt::encrypt($perangkat->id);
            unset($perangkat->id);

            foreach ($perangkat->layanan as $layanan) {
                $layanan->encrypted_id = Crypt::encrypt($layanan->id);
                unset($layanan->id);
            }
        }

        foreach ($product->faq_produk as $faq) {
            $faq->encrypted_id = Crypt::encrypt($faq->id);
            unset($faq->id);
        }

        return view('product.detail', compact('product'));
    }
}
