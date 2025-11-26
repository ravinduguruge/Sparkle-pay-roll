<?php

use App\Http\Controllers\Employee\DashboardController as EmployeeDashboard;
use App\Http\Controllers\Employee\WorkEntryController;
use App\Http\Controllers\Employee\SalaryController as EmployeeSalary;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SalaryController;
use App\Http\Controllers\Admin\WorkApprovalController;
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

    // job in / out
    Route::post('/employee/job-in', [WorkEntryController::class, 'jobIn'])
        ->name('employee.job_in');

    Route::get('/employee/job-out-form/{workEntry}', [WorkEntryController::class, 'showJobOutForm'])
        ->name('employee.job_out_form');

    Route::post('/employee/job-out/{workEntry}', [WorkEntryController::class, 'jobOut'])
        ->name('employee.job_out');

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
});