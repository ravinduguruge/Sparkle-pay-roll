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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('key_employee_id')->nullable(); // one key employee
            $table->decimal('budget_amount', 12, 2)->default(0);
            $table->decimal('remaining_budget', 12, 2)->default(0);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('key_employee_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
