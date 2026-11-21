<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->string('movement_type', 3);
            $table->string('reference_code', 40)->index();
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('balance_after');
            $table->date('occurred_at');
            $table->string('notes', 255)->nullable();
            $table->timestamps();

            $table->index(['product_id', 'occurred_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_cards');
    }
};
