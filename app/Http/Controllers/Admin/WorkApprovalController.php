<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkEntry;
use App\Models\SalaryMonth;
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

        // Automatically calculate and update monthly salary
        $this->updateMonthlySalary($workEntry);

        return back()->with('success', 'Work entry approved and salary updated.');
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

        // Calculate salary based on hours and rates
        $normalEarnings = $totalNormalHours * ($user->normal_hour_rate ?? 0);
        $otEarnings = $totalOTHours * ($user->ot_hour_rate ?? 0);
        
        // Update monthly salary (subtract expenses)
        $salaryMonth->monthly_salary = $normalEarnings + $otEarnings - $totalExpenses;
        $salaryMonth->allowance_total = $totalExpenses;
        $salaryMonth->recalcRemaining();
    }
}
