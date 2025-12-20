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
        Schema::create('monthly_revenues', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->integer('month'); // 1-12
            $table->decimal('revenue', 15, 2)->default(0);
            $table->decimal('order_revenue', 15, 2)->default(0);
            $table->decimal('appointment_revenue', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->boolean('is_closed')->default(false); // Đã kết thúc tháng chưa
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
            
            $table->unique(['year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_revenues');
    }
};
