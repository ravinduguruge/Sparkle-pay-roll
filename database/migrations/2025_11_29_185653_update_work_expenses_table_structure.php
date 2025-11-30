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
        Schema::table('work_expenses', function (Blueprint $table) {
            // Remove old columns
            $table->dropColumn(['description']);
            
            // Add new columns
            $table->string('expense_type')->after('work_entry_id'); // refreshment, vehicle, other, purchase
            $table->string('item_name')->nullable()->after('expense_type');
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->after('item_name');
            $table->decimal('distance_km', 10, 2)->nullable()->after('vehicle_id');
            $table->foreignId('other_expense_item_id')->nullable()->constrained('other_expense_items')->after('distance_km');
            $table->foreignId('company_tool_id')->nullable()->constrained('company_tools')->after('other_expense_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_expenses', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->dropForeign(['vehicle_id']);
            $table->dropForeign(['other_expense_item_id']);
            $table->dropForeign(['company_tool_id']);
            $table->dropColumn(['expense_type', 'item_name', 'vehicle_id', 'distance_km', 'other_expense_item_id', 'company_tool_id']);
        });
    }
};
