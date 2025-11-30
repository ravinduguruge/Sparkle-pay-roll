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
        Schema::create('company_store', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_tool_id')->constrained('company_tools')->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->decimal('unit_price', 10, 2);
            $table->date('purchase_date');
            $table->foreignId('purchased_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_store');
    }
};
