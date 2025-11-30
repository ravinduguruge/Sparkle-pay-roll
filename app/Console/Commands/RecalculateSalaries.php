<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SalaryMonth;
use App\Models\WorkEntry;
use App\Models\User;

class RecalculateSalaries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'salary:recalculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate all monthly salaries based on approved work entries';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Recalculating all salaries...');

        // Get all salary months
        $salaryMonths = SalaryMonth::all();

        foreach ($salaryMonths as $salaryMonth) {
            $user = $salaryMonth->user;
            $year = $salaryMonth->year;
            $month = $salaryMonth->month;

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

            // Get daily expenses and advances
            $totalDailyExpensesAndAdvances = \App\Models\EmployeeExpense::where('user_id', $user->id)
                ->where('year', $year)
                ->where('month', $month)
                ->sum('amount');

            // Calculate salary based on hours and rates
            $normalEarnings = $totalNormalHours * ($user->normal_hour_rate ?? 0);
            $otEarnings = $totalOTHours * ($user->ot_hour_rate ?? 0);
            
            // Update monthly salary (add work expenses as reimbursements, subtract daily expenses & advances)
            $salaryMonth->monthly_salary = $normalEarnings + $otEarnings + $totalExpenses - $totalDailyExpensesAndAdvances;
            $salaryMonth->allowance_total = $totalExpenses;
            $salaryMonth->recalcRemaining();

            $this->line("Updated {$user->name} - {$year}/{$month}: Rs. {$salaryMonth->monthly_salary}");
        }

        $this->info('All salaries recalculated successfully!');
    }
}
