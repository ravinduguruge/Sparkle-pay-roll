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
        Schema::table('work_entries', function (Blueprint $table) {
            // Rename columns to match code
            $table->renameColumn('job_in_at', 'job_in_time');
            $table->renameColumn('job_out_at', 'job_out_time');
            $table->renameColumn('job_description', 'description');
            
            // Add missing columns
            $table->decimal('total_hours', 5, 2)->nullable()->after('job_out_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_entries', function (Blueprint $table) {
            $table->renameColumn('job_in_time', 'job_in_at');
            $table->renameColumn('job_out_time', 'job_out_at');
            $table->renameColumn('description', 'job_description');
            $table->dropColumn('total_hours');
        });
    }
};
