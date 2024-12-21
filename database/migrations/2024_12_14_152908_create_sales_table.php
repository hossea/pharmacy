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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medicine_id');
            $table->integer('quantity_sold');
            $table->decimal('price_per_unit', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2);
            $table->enum('payment_method', ['Mpesa', 'Cash', 'Debt', 'Bank Transfer']);
            $table->decimal('discount', 10, 2)->nullable();
            $table->string('sold_by');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'discount']);
        });
    }
};
