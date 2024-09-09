<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('damage_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_in_id')->nullable()->constrained('stock_ins');
            $table->foreignId('stock_out_id')->nullable()->constrained('stock_outs');
            $table->string('quantity')->nullable();
            $table->string('level')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('damage_products');
    }
};
