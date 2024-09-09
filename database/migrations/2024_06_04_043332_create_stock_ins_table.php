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
        Schema::create('stock_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained('products');
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses');
            $table->foreignId('unit_id')->nullable()->constrained('units');
            $table->foreignId('scale_id')->nullable()->constrained('scales');
            $table->foreignId('currency_id')->nullable()->constrained('currencies');
            $table->string('original_price')->nullable();
            $table->string('sub_total')->nullable();
            $table->string('grand_total')->nullable();
            $table->string('tax')->nullable();
            $table->string('quantity')->nullable();
            $table->string('convert_weight')->nullable();
            $table->string('converted_weight')->nullable();
            $table->string('volume')->nullable();
            $table->string('green')->nullable();
            $table->string('yellow')->nullable();
            $table->string('red')->nullable();
            $table->string('line')->nullable();
            $table->string('level')->nullable();
            $table->string('stand')->nullable();
            $table->longText('remark_en')->nullable();
            $table->longText('remark_mm')->nullable();
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
        Schema::dropIfExists('stock_ins');
    }
};
