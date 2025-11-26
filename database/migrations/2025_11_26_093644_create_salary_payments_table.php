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
        Schema::create('salary_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('salary_month_id');
            $table->date('paid_date');
            $table->string('description')->nullable();
            $table->decimal('salary_amount', 12, 2)->default(0);
            $table->decimal('allowance_amount', 12, 2)->default(0);
            $table->timestamps();
            
            $table->foreign('salary_month_id')->references('id')->on('salary_months')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_payments');
    }
};
