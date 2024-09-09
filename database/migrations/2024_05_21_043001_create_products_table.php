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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories');
            $table->foreignId('sub_category_id')->nullable()->constrained('sub_categories');
            $table->foreignId('country_id')->nullable()->constrained('countries');
            $table->string('name_en')->nullable();
            $table->string('name_mm')->nullable();
            $table->string('sku')->nullable();
            $table->boolean('new_item')->nullable();
            $table->boolean('seasonal')->nullable();
            $table->boolean('organic')->nullable();
            $table->boolean('recommended')->nullable();
            $table->longText('description_en')->nullable();
            $table->longText('description_mm')->nullable();
            $table->longText('remark_en')->nullable();
            $table->longText('remark_mm')->nullable();
            $table->string('image')->nullable();
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
        Schema::dropIfExists('products');
    }
};
