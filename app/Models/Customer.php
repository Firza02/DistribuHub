<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'kode_customer';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_customer',
        'nama_customer',
        'alamat_lengkap',
        'provinsi',
        'kota',
        'kecamatan',
        'kelurahan',
        'kode_pos',
    ];

    /**
     * Cek apakah customer ini sudah pernah bertransaksi.
     * Dipakai untuk mencegah penghapusan (business rule 2c).
     */
    public function hasTransactions(): bool
    {
        return Transaction::where('kode_customer', $this->kode_customer)->exists();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'kode_customer', 'kode_customer');
    }

    public function alamatSingkat(): string
    {
        return collect([
            $this->alamat_lengkap,
            $this->kelurahan,
            $this->kecamatan,
            $this->kota,
            $this->provinsi,
            $this->kode_pos,
        ])->filter()->implode(', ');
    }
}
