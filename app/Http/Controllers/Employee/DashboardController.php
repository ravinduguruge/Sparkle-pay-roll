<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SalaryMonth;
use App\Models\WorkEntry;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\OtherExpenseItem;
use App\Models\CompanyTool;
use App\Models\CompanyStore;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // net salary = sum of remaining_amount for all months
        $netSalary = SalaryMonth::where('user_id', $user->id)->sum('remaining_amount');

        // Get recent work entries
        $recentWork = WorkEntry::where('user_id', $user->id)
            ->orderBy('work_date', 'desc')
            ->take(10)
            ->get();

        return view('employee.dashboard', [
            'user'          => $user,
            'netSalary'     => $netSalary,
            'recentWork'    => $recentWork,
        ]);
    }

    public function dailyWork()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // Check if user has an open work entry (not completed all steps)
        $openWorkEntry = WorkEntry::where('user_id', $user->id)
            ->whereDate('work_date', $today)
            ->where(function($query) {
                $query->whereNull('travel_end_time')
                      ->orWhereNull('status');
            })
            ->first();

        $projects = Project::where('status', 'active')->orderBy('name')->get();
        
        // Get all employees for work partners selection
        $employees = User::where('role', 'employee')
            ->where('id', '!=', $user->id)
            ->orderBy('name')
            ->get();

        // Get vehicles, expense items, and tools
        $vehicles = Vehicle::where('is_active', true)->orderBy('name')->get();
        $otherExpenseItems = OtherExpenseItem::where('is_active', true)->orderBy('name')->get();
        $companyTools = CompanyTool::where('is_active', true)->orderBy('name')->get();

        // Get store inventory with quantities and prices
        $storeInventory = CompanyStore::select('company_tool_id', 
                DB::raw('SUM(quantity) as available_quantity'),
                DB::raw('AVG(unit_price) as avg_price'))
            ->groupBy('company_tool_id')
            ->with('companyTool')
            ->having('available_quantity', '>', 0)
            ->get();

        // net salary = sum of remaining_amount for all months
        $netSalary = SalaryMonth::where('user_id', $user->id)->sum('remaining_amount');

        return view('employee.daily-work', [
            'user'                => $user,
            'projects'            => $projects,
            'employees'           => $employees,
            'vehicles'            => $vehicles,
            'otherExpenseItems'   => $otherExpenseItems,
            'companyTools'        => $companyTools,
            'storeInventory'      => $storeInventory,
            'openWorkEntry'       => $openWorkEntry,
            'netSalary'           => $netSalary,
        ]);
    }
}