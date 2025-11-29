<?php

use App\Http\Controllers\Employee\DashboardController as EmployeeDashboard;
use App\Http\Controllers\Employee\WorkEntryController;
use App\Http\Controllers\Employee\DailyWorkController;
use App\Http\Controllers\Employee\SalaryController as EmployeeSalary;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SalaryController;
use App\Http\Controllers\Admin\WorkApprovalController;
use App\Http\Controllers\Admin\EmployeeExpenseController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\CompanyToolController;
use App\Http\Controllers\Admin\OtherExpenseItemController;
use App\Http\Controllers\Admin\CompanyStoreController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

require __DIR__.'/auth.php';

// simple home redirect
Route::get('/', function () {
    return redirect()->route('login');
});
// Dashboard redirect based on role
Route::get('/dashboard', function () {
    if (
        Auth::check() &&
        (
            (Auth::user()->is_admin ?? false) ||
            (Auth::user()->role ?? '') === 'admin'
        )
    ) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('employee.dashboard');
})->middleware('auth')->name('dashboard');


// Employee area
Route::middleware(['auth'])->group(function () {

    Route::get('/employee/dashboard', [EmployeeDashboard::class, 'index'])
        ->name('employee.dashboard');

    Route::get('/employee/daily-work', [EmployeeDashboard::class, 'dailyWork'])
        ->name('employee.daily_work');

    // job in / out
    Route::post('/employee/job-in', [WorkEntryController::class, 'jobIn'])
        ->name('employee.job_in');

    Route::get('/employee/job-out-form/{workEntry}', [WorkEntryController::class, 'showJobOutForm'])
        ->name('employee.job_out_form');

    Route::post('/employee/job-out/{workEntry}', [WorkEntryController::class, 'jobOut'])
        ->name('employee.job_out');

    // New daily work submission
    Route::post('/employee/submit-daily-work', [DailyWorkController::class, 'submitDailyWork'])
        ->name('employee.submit_daily_work');

    // salary pages
    Route::get('/employee/salary', [EmployeeSalary::class, 'index'])
        ->name('employee.salary.index');

    Route::get('/employee/salary/{year}/{month}', [EmployeeSalary::class, 'showMonth'])
        ->name('employee.salary.month');
});

// Admin area
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    Route::resource('employees', EmployeeController::class)->except(['show']);

    Route::resource('projects', ProjectController::class);

    Route::get('salary', [SalaryController::class, 'index'])->name('salary.index');
    Route::get('salary/{user}', [SalaryController::class, 'showEmployee'])->name('salary.employee');
    Route::post('salary/{user}/month', [SalaryController::class, 'storeOrUpdateMonth'])->name('salary.month.store');
    Route::post('salary/{salaryMonth}/payment', [SalaryController::class, 'addPayment'])->name('salary.payment.add');

    Route::get('work-approvals', [WorkApprovalController::class, 'index'])->name('work_approvals.index');
    Route::post('work-approvals/{workEntry}/approve', [WorkApprovalController::class, 'approve'])->name('work_approvals.approve');

    Route::get('employee-expenses', [EmployeeExpenseController::class, 'index'])->name('employee_expenses.index');
    Route::post('employee-expenses', [EmployeeExpenseController::class, 'store'])->name('employee_expenses.store');
    Route::delete('employee-expenses/{employeeExpense}', [EmployeeExpenseController::class, 'destroy'])->name('employee_expenses.destroy');

    Route::get('vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
    Route::post('vehicles', [VehicleController::class, 'store'])->name('vehicles.store');
    Route::put('vehicles/{vehicle}', [VehicleController::class, 'update'])->name('vehicles.update');
    Route::delete('vehicles/{vehicle}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');

    Route::get('company-tools', [CompanyToolController::class, 'index'])->name('company_tools.index');
    Route::post('company-tools', [CompanyToolController::class, 'store'])->name('company_tools.store');
    Route::put('company-tools/{companyTool}', [CompanyToolController::class, 'update'])->name('company_tools.update');
    Route::delete('company-tools/{companyTool}', [CompanyToolController::class, 'destroy'])->name('company_tools.destroy');

    Route::get('other-expense-items', [OtherExpenseItemController::class, 'index'])->name('other_expense_items.index');
    Route::post('other-expense-items', [OtherExpenseItemController::class, 'store'])->name('other_expense_items.store');
    Route::put('other-expense-items/{otherExpenseItem}', [OtherExpenseItemController::class, 'update'])->name('other_expense_items.update');
    Route::delete('other-expense-items/{otherExpenseItem}', [OtherExpenseItemController::class, 'destroy'])->name('other_expense_items.destroy');

    Route::get('company-store', [CompanyStoreController::class, 'index'])->name('company_store.index');
    Route::post('company-store', [CompanyStoreController::class, 'store'])->name('company_store.store');
    Route::delete('company-store/{companyStore}', [CompanyStoreController::class, 'destroy'])->name('company_store.destroy');
});