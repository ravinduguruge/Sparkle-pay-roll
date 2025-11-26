<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalaryMonth;
use App\Models\SalaryPayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function index()
    {
        $employees = User::where('role', 'employee')->orderBy('name')->get();
        return view('admin.salary.index', compact('employees'));
    }

    public function showEmployee(User $user)
    {
        $months = $user->salaryMonths()->orderByDesc('year')->orderByDesc('month')->get();
        return view('admin.salary.employee', compact('user', 'months'));
    }

    public function storeOrUpdateMonth(Request $request, User $user)
    {
        $request->validate([
            'year'   => 'required|integer',
            'month'  => 'required|integer|min:1|max:12',
            'monthly_salary' => 'required|numeric|min:0',
        ]);

        $salaryMonth = SalaryMonth::firstOrNew([
            'user_id' => $user->id,
            'year'    => $request->year,
            'month'   => $request->month,
        ]);

        $salaryMonth->monthly_salary = $request->monthly_salary;
        // if new, others are default 0
        $salaryMonth->recalcRemaining();

        return back()->with('success', 'Monthly salary saved.');
    }

    public function addPayment(Request $request, SalaryMonth $salaryMonth)
    {
        $request->validate([
            'paid_date'       => 'required|date',
            'description'     => 'nullable|string',
            'salary_amount'   => 'nullable|numeric|min:0',
            'allowance_amount'=> 'nullable|numeric|min:0',
        ]);

        $salaryAmount   = $request->salary_amount ?? 0;
        $allowanceAmount= $request->allowance_amount ?? 0;

        SalaryPayment::create([
            'salary_month_id' => $salaryMonth->id,
            'paid_date'       => $request->paid_date,
            'description'     => $request->description,
            'salary_amount'   => $salaryAmount,
            'allowance_amount'=> $allowanceAmount,
        ]);

        $salaryMonth->paid_amount      += $salaryAmount;
        $salaryMonth->allowance_total  += $allowanceAmount;
        $salaryMonth->recalcRemaining();

        return back()->with('success', 'Payment recorded.');
    }
}
