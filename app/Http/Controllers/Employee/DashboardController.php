<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SalaryMonth;
use App\Models\WorkEntry;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();
        $today = Carbon::today();

        $openWorkEntry = WorkEntry::where('user_id', $user->id)
            ->whereDate('work_date', $today)
            ->whereNull('job_out_time')
            ->first();

        $projects = Project::orderBy('name')->get();

        // net salary = sum of remaining_amount for all months
        $netSalary = SalaryMonth::where('user_id', $user->id)->sum('remaining_amount');

        return view('employee.dashboard', [
            'user'          => $user,
            'projects'      => $projects,
            'openWorkEntry' => $openWorkEntry,
            'netSalary'     => $netSalary,
        ]);
    }
}

