<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'kode_produk';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'harga',
        'stok',
    ];

    /**
     * Cek apakah produk ini sudah pernah dipakai di transaksi.
     * Dipakai untuk mencegah penghapusan (business rule 1c).
     */
    public function hasTransactions(): bool
    {
        return TransactionDetail::where('kode_produk', $this->kode_produk)->exists();
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'kode_produk', 'kode_produk');
    }
}
