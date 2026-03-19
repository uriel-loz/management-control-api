<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_product', function (Blueprint $table) {
            $table->unsignedInteger('quantity')->default(1)->after('product_id');
            $table->decimal('unit_price', 16, 2)->after('quantity');
            $table->decimal('subtotal', 16, 2)->after('unit_price');
        });
    }

    public function down(): void
    {
        Schema::table('order_product', function (Blueprint $table) {
            $table->dropColumn(['quantity', 'unit_price', 'subtotal']);
        });
    }
};
