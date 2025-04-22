<?php

namespace App\Http\Controllers;

use App\Models\FaqKategoriProduk;
use App\Models\KategoriProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ProductController extends Controller
{
    public function index()
    {
        $products = KategoriProduk::all()->map(function ($product) {
            $product->encrypted_id = Crypt::encrypt($product->id);
            unset($product->id);
            return $product;
        });

        return view('product.index', compact('products'));
    }

    public function detail($id)
    {
        $id = Crypt::decrypt($id);
        $product = KategoriProduk::with(['produk', 'produk.layanan', 'faq_product'])->findOrFail($id);

        // Encrypt ID kategori & hapus aslinya
        $product->encrypted_id = Crypt::encrypt($product->id);
        unset($product->id);

        // Encrypt semua produk ID & hapus ID asli
        foreach ($product->produk as $produk) {
            $produk->encrypted_id = Crypt::encrypt($produk->id);
            unset($produk->id);

            // Encrypt semua layanan dari produk & hapus ID asli
            foreach ($produk->layanan as $layanan) {
                $layanan->encrypted_id = Crypt::encrypt($layanan->id);
                unset($layanan->id);
            }
        }

        // Encrypt semua FAQ ID & hapus ID asli
        foreach ($product->faq_product as $faq) {
            $faq->encrypted_id = Crypt::encrypt($faq->id);
            unset($faq->id);
        }

        return view('product.detail', compact('product'));
    }
}
