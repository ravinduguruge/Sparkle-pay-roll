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
            // Drop old time columns
            $table->dropColumn(['job_in_time', 'job_out_time']);
            
            // Add new datetime columns
            $table->dateTime('travel_start_time')->nullable()->after('work_date');
            $table->dateTime('site_on_time')->nullable()->after('travel_start_time');
            $table->dateTime('site_out_time')->nullable()->after('site_on_time');
            $table->dateTime('travel_end_time')->nullable()->after('site_out_time');
            
            // Add work partners field (JSON array of user IDs)
            $table->json('work_partners')->nullable()->after('travel_end_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_entries', function (Blueprint $table) {
            // Restore old columns
            $table->dateTime('job_in_time')->nullable();
            $table->dateTime('job_out_time')->nullable();
            
            // Drop new columns
            $table->dropColumn(['travel_start_time', 'site_on_time', 'site_out_time', 'travel_end_time', 'work_partners']);
        });
    }
};
