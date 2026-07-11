<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_outs', function (Blueprint $table) {

            $table->id();

            $table->foreignId('product_id')
                  ->constrained('products')
                  ->cascadeOnDelete();

            $table->integer('jumlah');

            $table->date('tanggal');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_outs');
    }
};