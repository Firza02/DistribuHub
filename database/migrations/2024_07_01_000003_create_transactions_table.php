<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->string('no_inv', 30)->primary(); // INV/2507/0001
            $table->string('kode_customer', 30);
            $table->string('nama_customer', 150);
            $table->text('alamat')->nullable();
            $table->date('tgl_inv');
            $table->decimal('total', 15, 2)->default(0);
            $table->timestamps();

            $table->foreign('kode_customer')
                ->references('kode_customer')->on('customers')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
