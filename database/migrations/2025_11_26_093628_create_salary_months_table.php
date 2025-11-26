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
        Schema::create('salary_months', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('year');
            $table->unsignedTinyInteger('month'); // 1-12

            $table->decimal('monthly_salary', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('allowance_total', 12, 2)->default(0);

            $table->decimal('remaining_amount', 12, 2)->default(0); // monthly_salary - paid_amount - allowance_total

            $table->timestamps();
            $table->unique(['user_id', 'year', 'month']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_months');
    }
};
