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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers');
            $table->foreignId('product_id')->nullable()->constrained('products');
            $table->foreignId('order_status_id')->nullable()->constrained('order_statuses');
            $table->foreignId('payment_id')->nullable()->constrained('payments');
            $table->foreignId('currency_id')->nullable()->constrained('currencies');
            $table->string('amount')->nullable();
            $table->string('tax')->nullable();
            $table->string('grand_total')->nullable();
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
        Schema::dropIfExists('purchase_orders');
    }
};
