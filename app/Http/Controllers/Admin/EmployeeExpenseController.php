<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeExpense;
use App\Models\SalaryMonth;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmployeeExpenseController extends Controller
{
    public function index()
    {
        $expenses = EmployeeExpense::with('user')
            ->orderByDesc('expense_date')
            ->paginate(20);

        $employees = User::where('role', 'employee')
            ->orderBy('name')
            ->get();

        return view('admin.employee-expenses.index', compact('expenses', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:expense,advance',
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
        ]);

        $expenseDate = Carbon::parse($request->expense_date);

        $expense = EmployeeExpense::create([
            'user_id' => $request->user_id,
            'type' => $request->type,
            'expense_date' => $request->expense_date,
            'amount' => $request->amount,
            'description' => $request->description,
            'year' => $expenseDate->year,
            'month' => $expenseDate->month,
        ]);

        // Update salary month
        $this->updateSalaryMonth($expense);

        return redirect()->route('admin.employee_expenses.index')
            ->with('success', ucfirst($request->type) . ' added successfully and salary updated.');
    }

    public function destroy(EmployeeExpense $employeeExpense)
    {
        $userId = $employeeExpense->user_id;
        $year = $employeeExpense->year;
        $month = $employeeExpense->month;

        $employeeExpense->delete();

        // Recalculate salary for that month
        $this->recalculateSalaryMonth($userId, $year, $month);

        return redirect()->route('admin.employee_expenses.index')
            ->with('success', 'Expense deleted and salary recalculated.');
    }

    private function updateSalaryMonth(EmployeeExpense $expense)
    {
        // Get or create salary month record
        $salaryMonth = SalaryMonth::firstOrCreate(
            [
                'user_id' => $expense->user_id,
                'year' => $expense->year,
                'month' => $expense->month,
            ],
            [
                'monthly_salary' => 0,
                'paid_amount' => 0,
                'allowance_total' => 0,
                'remaining_amount' => 0,
            ]
        );

        // Recalculate with all expenses
        $this->recalculateSalaryMonth($expense->user_id, $expense->year, $expense->month);
    }

    private function recalculateSalaryMonth($userId, $year, $month)
    {
        $salaryMonth = SalaryMonth::where('user_id', $userId)
            ->where('year', $year)
            ->where('month', $month)
            ->first();

        if (!$salaryMonth) {
            return;
        }

        // Get total expenses and advances for this month
        $totalExpensesAndAdvances = EmployeeExpense::where('user_id', $userId)
            ->where('year', $year)
            ->where('month', $month)
            ->sum('amount');

        // Recalculate base salary from work entries
        $user = User::find($userId);
        $approvedWorks = \App\Models\WorkEntry::where('user_id', $userId)
            ->where('status', 'approved')
            ->whereYear('work_date', $year)
            ->whereMonth('work_date', $month)
            ->get();

        $totalNormalHours = 0;
        $totalOTHours = 0;

        foreach ($approvedWorks as $work) {
            if ($work->total_hours) {
                if ($work->total_hours <= 8) {
                    $totalNormalHours += $work->total_hours;
                } else {
                    $totalNormalHours += 8;
                    $totalOTHours += ($work->total_hours - 8);
                }
            }
        }

        // Calculate work expenses (reimbursements)
        $totalWorkExpenses = 0;
        foreach ($approvedWorks as $work) {
            $totalWorkExpenses += $work->expenses->sum('amount');
        }

        // Calculate earnings
        $normalEarnings = $totalNormalHours * ($user->normal_hour_rate ?? 0);
        $otEarnings = $totalOTHours * ($user->ot_hour_rate ?? 0);
        
        // Monthly salary = Earnings + Work Expenses (reimbursements) - Daily Expenses & Advances
        $salaryMonth->monthly_salary = $normalEarnings + $otEarnings + $totalWorkExpenses - $totalExpensesAndAdvances;
        $salaryMonth->allowance_total = $totalWorkExpenses;
        $salaryMonth->recalcRemaining();
    }
}
