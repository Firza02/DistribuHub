<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('q');

        $products = Product::when($search, function ($query) use ($search) {
                $query->where('kode_produk', 'like', "%{$search}%")
                      ->orWhere('nama_produk', 'like', "%{$search}%");
            })
            ->orderBy('kode_produk')
            ->paginate(10)
            ->withQueryString();

        return view('products.index', compact('products', 'search'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // alphanumeric only, tidak boleh karakter spesial, harus unik (business rule 1b)
            'kode_produk' => [
                'required',
                'alpha_num',
                'max:30',
                Rule::unique('products', 'kode_produk'),
            ],
            'nama_produk' => 'required|string|max:150',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ], [
            'kode_produk.unique' => 'Kode produk sudah digunakan, silakan gunakan kode lain.',
            'kode_produk.alpha_num' => 'Kode produk hanya boleh huruf dan angka, tanpa karakter spesial.',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        // kode_produk sengaja tidak diubah (primary key & sudah bisa dipakai relasi transaksi)
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:150',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        // business rule 1c: tidak bisa hapus produk jika sudah dibuat transaksi
        if ($product->hasTransactions()) {
            return back()->with('error', 'Produk tidak bisa dihapus karena sudah memiliki transaksi.');
        }

        $product->delete();

        return back()->with('success', 'Produk berhasil dihapus.');
    }
}
