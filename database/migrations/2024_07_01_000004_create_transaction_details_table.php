<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id(); // id detail transaksi, unik, auto increment
            $table->string('no_inv', 30);
            $table->string('kode_produk', 30);
            $table->string('nama_produk', 150);
            $table->integer('qty');
            $table->decimal('harga', 15, 2);
            $table->decimal('disc1', 5, 2)->default(0);
            $table->decimal('disc2', 5, 2)->default(0);
            $table->decimal('disc3', 5, 2)->default(0);
            $table->decimal('harga_net', 15, 2);
            $table->decimal('jumlah', 15, 2);
            $table->timestamps();

            $table->foreign('no_inv')
                ->references('no_inv')->on('transactions')
                ->cascadeOnDelete();

            $table->foreign('kode_produk')
                ->references('kode_produk')->on('products')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
