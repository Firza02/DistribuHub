<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('q');

        $transactions = Transaction::with('customer')
            ->when($search, function ($query) use ($search) {
                $query->where('no_inv', 'like', "%{$search}%")
                      ->orWhere('nama_customer', 'like', "%{$search}%");
            })
            ->orderByDesc('no_inv')
            ->paginate(10)
            ->withQueryString();

        return view('transactions.index', compact('transactions', 'search'));
    }

    public function create()
    {
        $customers = Customer::orderBy('nama_customer')->get();
        $products = Product::orderBy('nama_produk')->get();
        $noInvPreview = Transaction::generateNoInv();

        // disiapkan di controller (bukan di dalam Blade) supaya @json di view
        // tidak perlu menulis closure/arrow function yang rawan bikin
        // parser Blade salah membaca tanda kurung bersarang.
        $productsForJs = $products->map(function ($p) {
            return [
                'kode' => $p->kode_produk,
                'nama' => $p->nama_produk,
                'harga' => (float) $p->harga,
                'stok' => (int) $p->stok,
            ];
        })->values();

        return view('transactions.create', compact('customers', 'products', 'noInvPreview', 'productsForJs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_customer' => 'required|exists:customers,kode_customer',
            'tgl_inv' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.kode_produk' => 'required|exists:products,kode_produk',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.disc1' => 'nullable|numeric|min:0|max:100',
            'items.*.disc2' => 'nullable|numeric|min:0|max:100',
            'items.*.disc3' => 'nullable|numeric|min:0|max:100',
        ]);

        try {
            $transaction = DB::transaction(function () use ($validated) {
                $customer = Customer::findOrFail($validated['kode_customer']);
                $noInv = Transaction::generateNoInv();

                $transaction = Transaction::create([
                    'no_inv' => $noInv,
                    'kode_customer' => $customer->kode_customer,
                    'nama_customer' => $customer->nama_customer,
                    'alamat' => $customer->alamatSingkat(),
                    'tgl_inv' => $validated['tgl_inv'],
                    'total' => 0,
                ]);

                $total = 0;

                foreach ($validated['items'] as $item) {
                    // lock baris produk agar aman dari race condition stok
                    $product = Product::where('kode_produk', $item['kode_produk'])
                        ->lockForUpdate()
                        ->firstOrFail();

                    // qty tidak boleh melebihi sisa stok
                    if ($item['qty'] > $product->stok) {
                        throw ValidationException::withMessages([
                            'items' => "Qty untuk produk {$product->nama_produk} ({$item['qty']}) melebihi sisa stok ({$product->stok}).",
                        ]);
                    }

                    $disc1 = $item['disc1'] ?? 0;
                    $disc2 = $item['disc2'] ?? 0;
                    $disc3 = $item['disc3'] ?? 0;
                    $hargaNet = TransactionDetail::hitungHargaNet($item['harga'], $disc1, $disc2, $disc3);
                    $jumlah = round($hargaNet * $item['qty'], 2);

                    TransactionDetail::create([
                        'no_inv' => $noInv,
                        'kode_produk' => $product->kode_produk,
                        'nama_produk' => $product->nama_produk,
                        'qty' => $item['qty'],
                        'harga' => $item['harga'],
                        'disc1' => $disc1,
                        'disc2' => $disc2,
                        'disc3' => $disc3,
                        'harga_net' => $hargaNet,
                        'jumlah' => $jumlah,
                    ]);

                    // stok berkurang setelah transaksi terbuat
                    $product->decrement('stok', $item['qty']);

                    $total += $jumlah;
                }

                $transaction->update(['total' => $total]);

                return $transaction;
            });
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect('/transactions/' . $transaction->no_inv)
            ->with('success', "Transaksi {$transaction->no_inv} berhasil dibuat.");
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('details');
        return view('transactions.show', compact('transaction'));
    }
}
