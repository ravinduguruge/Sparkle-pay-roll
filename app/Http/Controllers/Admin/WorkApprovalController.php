<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkEntry;
use App\Models\SalaryMonth;
use App\Models\User;
use Carbon\Carbon;

class WorkApprovalController extends Controller
{
    public function index()
    {
        $pending = WorkEntry::with(['user', 'project'])
            ->where('status', 'pending')
            ->orderByDesc('work_date')
            ->get();

        $approved = WorkEntry::with(['user', 'project'])
            ->where('status', 'approved')
            ->orderByDesc('work_date')
            ->limit(50)
            ->get();

        return view('admin.day-to-day-work.work-details', compact('pending', 'approved'));
    }

    public function approve(WorkEntry $workEntry)
    {
        $workEntry->status = 'approved';
        $workEntry->save();

        // Automatically calculate and update monthly salary for main employee
        $this->updateMonthlySalary($workEntry);

        // Also update salary for all work partners who worked on site
        if ($workEntry->work_partners && is_array($workEntry->work_partners)) {
            foreach ($workEntry->work_partners as $partnerId) {
                // Create work entry for each partner with same hours
                $partnerWorkEntry = WorkEntry::create([
                    'user_id' => $partnerId,
                    'project_id' => $workEntry->project_id,
                    'work_date' => $workEntry->work_date,
                    'travel_start_time' => $workEntry->travel_start_time,
                    'site_on_time' => $workEntry->site_on_time,
                    'site_out_time' => $workEntry->site_out_time,
                    'travel_end_time' => $workEntry->travel_end_time,
                    'total_hours' => $workEntry->total_hours,
                    'description' => 'Worked with ' . $workEntry->user->name . ' - ' . $workEntry->description,
                    'status' => 'approved',
                ]);

                // Update salary for partner
                $this->updateMonthlySalary($partnerWorkEntry);
            }
        }

        return back()->with('success', 'Work entry approved and salary updated for all workers.');
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
        $workDaysCount = $approvedWorks->where('total_hours', '>', 0)->count();

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
    }
}
