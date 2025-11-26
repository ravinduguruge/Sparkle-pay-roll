<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\SalaryMonth;
use Illuminate\Support\Facades\Auth;


class SalaryController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $months = SalaryMonth::where('user_id', $user->id)
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();

        $netSalary = $months->sum('remaining_amount');

        return view('employee.salary.index', compact('months', 'netSalary'));
    }

    public function showMonth($year, $month)
    {
        $user = Auth::user();

        $salaryMonth = SalaryMonth::where('user_id', $user->id)
            ->where('year', $year)
            ->where('month', $month)
            ->with('payments')
            ->firstOrFail();

        return view('employee.salary.show', compact('salaryMonth'));
    }
}
