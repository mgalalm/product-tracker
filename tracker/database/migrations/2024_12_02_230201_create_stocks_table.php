<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;
use App\Models\Retailer;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Retailer::class)->constrained()->cascadeOnDelete();
            $table->unsignedInteger('price');
            $table->string('currency')->default('EUR');
            $table->string('sku')->nullable();
            $table->boolean('in_stock')->default(false);
            $table->string('url');
            $table->boolean('tracked')->default(false );
            $table->timestamps();
            $table->index(['sku', 'retailer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
