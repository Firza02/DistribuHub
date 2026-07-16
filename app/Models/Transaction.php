<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'no_inv';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_inv',
        'kode_customer',
        'nama_customer',
        'alamat',
        'tgl_inv',
        'total',
    ];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'no_inv', 'no_inv');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'kode_customer', 'kode_customer');
    }

    /**
     * Generate nomor invoice otomatis: INV/YYMM/NNNN, reset tiap bulan.
     */
    public static function generateNoInv(): string
    {
        $prefix = 'INV/' . now()->format('ym') . '/';

        $last = self::where('no_inv', 'like', $prefix . '%')
            ->orderByDesc('no_inv')
            ->lockForUpdate()
            ->first();

        $nextNumber = 1;
        if ($last) {
            $lastNumber = (int) substr($last->no_inv, strlen($prefix));
            $nextNumber = $lastNumber + 1;
        }

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
