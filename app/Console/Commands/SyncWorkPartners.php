<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WorkEntry;
use App\Models\SalaryMonth;
use Carbon\Carbon;

class SyncWorkPartners extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'work:sync-partners';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create work entries for all work partners and calculate their salaries';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Syncing work partner entries...');

        // Get all approved work entries that have partners
        $workEntriesWithPartners = WorkEntry::where('status', 'approved')
            ->whereNotNull('work_partners')
            ->where('work_partners', '!=', '[]')
            ->where('work_partners', '!=', 'null')
            ->get();

        $createdCount = 0;

        foreach ($workEntriesWithPartners as $workEntry) {
            $partners = is_array($workEntry->work_partners) ? $workEntry->work_partners : json_decode($workEntry->work_partners, true);
            
            if (!$partners || !is_array($partners)) {
                continue;
            }

            foreach ($partners as $partnerId) {
                // Check if partner work entry already exists
                $existingEntry = WorkEntry::where('user_id', $partnerId)
                    ->where('work_date', $workEntry->work_date)
                    ->where('project_id', $workEntry->project_id)
                    ->where('site_on_time', $workEntry->site_on_time)
                    ->where('site_out_time', $workEntry->site_out_time)
                    ->first();

                if (!$existingEntry) {
                    // Create work entry for partner
                    $partnerWorkEntry = WorkEntry::create([
                        'user_id' => $partnerId,
                        'project_id' => $workEntry->project_id,
                        'work_date' => $workEntry->work_date,
                        'travel_start_time' => $workEntry->travel_start_time,
                        'site_on_time' => $workEntry->site_on_time,
                        'site_out_time' => $workEntry->site_out_time,
                        'travel_end_time' => $workEntry->travel_end_time,
                        'total_hours' => $workEntry->total_hours,
                        'description' => 'Worked with ' . $workEntry->user->name . ' - ' . ($workEntry->description ?? 'Team work'),
                        'status' => 'approved',
                        'work_partners' => null,
                    ]);

                    // Update salary for partner
                    $this->updateMonthlySalary($partnerWorkEntry);
                    
                    $createdCount++;
                    $this->line("Created work entry for partner ID {$partnerId} on {$workEntry->work_date}");
                }
            }
        }

        $this->info("Sync complete! Created {$createdCount} work partner entries.");
    }

    private function updateMonthlySalary(WorkEntry $workEntry)
    {
        $user = $workEntry->user;
        $workDate = Carbon::parse($workEntry->work_date);
        $year = $workDate->year;
        $month = $workDate->month;

        // Get or create salary month record
        $salaryMonth = SalaryMonth::firstOrCreate(
            [
                'user_id' => $user->id,
                'year' => $year,
                'month' => $month,
            ],
            [
                'monthly_salary' => 0,
                'paid_amount' => 0,
                'allowance_total' => 0,
                'remaining_amount' => 0,
            ]
        );

        // Calculate total hours and earnings for this month
        $approvedWorks = WorkEntry::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereYear('work_date', $year)
            ->whereMonth('work_date', $month)
            ->get();

        $totalNormalHours = 0;
        $totalOTHours = 0;

        foreach ($approvedWorks as $work) {
            if ($work->total_hours) {
                // Assume first 8 hours are normal, rest is OT
                if ($work->total_hours <= 8) {
                    $totalNormalHours += $work->total_hours;
                } else {
                    $totalNormalHours += 8;
                    $totalOTHours += ($work->total_hours - 8);
                }
            }
        }

        // Calculate total expenses from work entries
        $totalExpenses = 0;
        foreach ($approvedWorks as $work) {
            $totalExpenses += $work->expenses->sum('amount');
        }

        // Calculate salary based on hours and rates
        $normalEarnings = $totalNormalHours * ($user->normal_hour_rate ?? 0);
        $otEarnings = $totalOTHours * ($user->ot_hour_rate ?? 0);
        
        // Update monthly salary (add expenses as reimbursements)
        $salaryMonth->monthly_salary = $normalEarnings + $otEarnings + $totalExpenses;
        $salaryMonth->allowance_total = $totalExpenses;
        $salaryMonth->recalcRemaining();
    }
}
