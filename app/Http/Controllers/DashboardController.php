<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Rentang bulan (format YYYY-MM). Kalau hanya salah satu diisi,
        // dianggap sebagai bulan tunggal (from = to).
        $monthFrom = $request->get('month_from');
        $monthTo = $request->get('month_to');

        if ($monthFrom && ! $monthTo) {
            $monthTo = $monthFrom;
        }
        if ($monthTo && ! $monthFrom) {
            $monthFrom = $monthTo;
        }
        // Jika user salah memasukkan urutan (from lebih baru dari to), tukar otomatis.
        if ($monthFrom && $monthTo && $monthFrom > $monthTo) {
            [$monthFrom, $monthTo] = [$monthTo, $monthFrom];
        }

        $kodeCustomer = $request->get('kode_customer');
        $kodeProduk = $request->get('kode_produk');

        // ----- Ringkasan & tren, mengikuti semua filter (termasuk rentang bulan) -----
        $summaryQuery = $this->filteredDetails($monthFrom, $monthTo, $kodeCustomer, $kodeProduk);

        $totalRevenue = (clone $summaryQuery)->sum('transaction_details.jumlah');
        $totalQty = (clone $summaryQuery)->sum('transaction_details.qty');
        $totalTransaksi = (clone $summaryQuery)->distinct('transaction_details.no_inv')->count('transaction_details.no_inv');
        $rataRata = $totalTransaksi > 0 ? $totalRevenue / $totalTransaksi : 0;

        // ----- Tren penjualan per bulan, dalam rentang yang dipilih (atau semua jika kosong) -----
        $trend = $this->filteredDetails($monthFrom, $monthTo, $kodeCustomer, $kodeProduk)
            ->selectRaw("DATE_FORMAT(transactions.tgl_inv, '%Y-%m') as bulan, SUM(transaction_details.jumlah) as total")
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // ----- Produk terlaris (top 5, berdasarkan omzet) -----
        $topProducts = $this->filteredDetails($monthFrom, $monthTo, $kodeCustomer, $kodeProduk)
            ->select('transaction_details.kode_produk', 'transaction_details.nama_produk')
            ->selectRaw('SUM(transaction_details.qty) as total_qty, SUM(transaction_details.jumlah) as total_revenue')
            ->groupBy('transaction_details.kode_produk', 'transaction_details.nama_produk')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        // ----- Customer teratas (top 5, berdasarkan omzet) -----
        $topCustomers = $this->filteredDetails($monthFrom, $monthTo, $kodeCustomer, $kodeProduk)
            ->select('transactions.kode_customer', 'transactions.nama_customer')
            ->selectRaw('SUM(transaction_details.jumlah) as total_revenue, COUNT(DISTINCT transaction_details.no_inv) as total_transaksi')
            ->groupBy('transactions.kode_customer', 'transactions.nama_customer')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        $customers = Customer::orderBy('nama_customer')->get();
        $products = Product::orderBy('nama_produk')->get();

        return view('dashboard.index', compact(
            'monthFrom', 'monthTo', 'kodeCustomer', 'kodeProduk',
            'totalRevenue', 'totalQty', 'totalTransaksi', 'rataRata',
            'trend', 'topProducts', 'topCustomers',
            'customers', 'products'
        ));
    }

    /**
     * Query dasar transaction_details join transactions, dengan filter opsional.
     * Dipisah jadi helper supaya setiap agregasi bisa mulai dari query yang bersih.
     */
    private function filteredDetails(?string $monthFrom, ?string $monthTo, ?string $kodeCustomer, ?string $kodeProduk)
    {
        return TransactionDetail::query()
            ->join('transactions', 'transaction_details.no_inv', '=', 'transactions.no_inv')
            ->when($monthFrom, function ($query) use ($monthFrom) {
                $start = Carbon::parse($monthFrom . '-01')->startOfMonth()->toDateString();
                $query->whereDate('transactions.tgl_inv', '>=', $start);
            })
            ->when($monthTo, function ($query) use ($monthTo) {
                $end = Carbon::parse($monthTo . '-01')->endOfMonth()->toDateString();
                $query->whereDate('transactions.tgl_inv', '<=', $end);
            })
            ->when($kodeCustomer, function ($query) use ($kodeCustomer) {
                $query->where('transactions.kode_customer', $kodeCustomer);
            })
            ->when($kodeProduk, function ($query) use ($kodeProduk) {
                $query->where('transaction_details.kode_produk', $kodeProduk);
            });
    }
}
