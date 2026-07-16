<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('q');

        $customers = Customer::when($search, function ($query) use ($search) {
                $query->where('kode_customer', 'like', "%{$search}%")
                      ->orWhere('nama_customer', 'like', "%{$search}%");
            })
            ->orderBy('kode_customer')
            ->paginate(10)
            ->withQueryString();

        return view('customers.index', compact('customers', 'search'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_customer' => [
                'required',
                'alpha_num',
                'max:30',
                Rule::unique('customers', 'kode_customer'),
            ],
            'nama_customer' => 'required|string|max:150',
            'alamat_lengkap' => 'required|string',
            'provinsi' => 'required|string|max:100',
            'kota' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'kelurahan' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
        ], [
            'kode_customer.unique' => 'Kode customer sudah digunakan, silakan gunakan kode lain.',
            'kode_customer.alpha_num' => 'Kode customer hanya boleh huruf dan angka, tanpa karakter spesial.',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil ditambahkan.');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'nama_customer' => 'required|string|max:150',
            'alamat_lengkap' => 'required|string',
            'provinsi' => 'required|string|max:100',
            'kota' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'kelurahan' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        // business rule 2c: tidak bisa hapus customer jika sudah dibuat transaksi
        if ($customer->hasTransactions()) {
            return back()->with('error', 'Customer tidak bisa dihapus karena sudah memiliki transaksi.');
        }

        $customer->delete();

        return back()->with('success', 'Customer berhasil dihapus.');
    }
}
