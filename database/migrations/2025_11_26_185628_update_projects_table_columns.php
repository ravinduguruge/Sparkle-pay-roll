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
        Schema::table('projects', function (Blueprint $table) {
            // Rename budget_amount to total_budget
            $table->renameColumn('budget_amount', 'total_budget');
            
            // Add new columns
            $table->decimal('advance_payment', 12, 2)->default(0)->after('total_budget');
            $table->decimal('key_employee_amount', 12, 2)->default(0)->after('advance_payment');
            $table->string('status')->default('active')->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Reverse the changes
            $table->renameColumn('total_budget', 'budget_amount');
            $table->dropColumn(['advance_payment', 'key_employee_amount', 'status']);
        });
    }
};
