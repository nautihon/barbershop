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
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('loyalty_points_used')->default(0)->after('total_amount');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('loyalty_points_used');
            $table->integer('loyalty_points_earned')->default(0)->after('discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['loyalty_points_used', 'discount_amount', 'loyalty_points_earned']);
        });
    }
};
