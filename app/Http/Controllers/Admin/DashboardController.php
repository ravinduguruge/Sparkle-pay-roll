<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SalaryMonth;
use App\Models\User;
use App\Models\WorkEntry;

class DashboardController extends Controller
{
    public function index()
    {
        $employeesCount = User::where('role', 'employee')->count();
        $projectsCount  = Project::count();
        $pendingWorks   = WorkEntry::where('status', 'pending')->count();

        $totalNetSalary = SalaryMonth::sum('remaining_amount');

        return view('admin.dashboard', compact(
            'employeesCount', 'projectsCount', 'pendingWorks', 'totalNetSalary'
        ));
    }
}

