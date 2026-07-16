<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $table = 'transaction_details';

    protected $fillable = [
        'no_inv',
        'kode_produk',
        'nama_produk',
        'qty',
        'harga',
        'disc1',
        'disc2',
        'disc3',
        'harga_net',
        'jumlah',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'no_inv', 'no_inv');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'kode_produk', 'kode_produk');
    }

    /**
     * Hitung harga net dari diskon bertingkat (disc1 -> disc2 -> disc3).
     * Contoh: harga 100000, disc1 10%, disc2 5%, disc3 0%
     *   100000 - 10% = 90000
     *   90000 - 5%   = 85500
     */
    public static function hitungHargaNet(float $harga, float $disc1, float $disc2, float $disc3): float
    {
        $net = $harga;
        $net -= $net * ($disc1 / 100);
        $net -= $net * ($disc2 / 100);
        $net -= $net * ($disc3 / 100);
        return round($net, 2);
    }
}
