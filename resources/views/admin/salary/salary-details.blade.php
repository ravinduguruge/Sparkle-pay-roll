@extends('layouts.master')

@section('title', 'Salary Management')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Salary Management</h1>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 mb-6 rounded-md">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Employees Salary Table -->
    <div class="bg-white rounded-lg shadow-md overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#19264bff]">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Employee</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Normal Hour Rate</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">OT Hour Rate</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Daily Expenses</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Net Salary</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($employees as $employee)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $employee->name }}</div>
                            <div class="text-xs text-gray-500">{{ $employee->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">Rs {{ number_format($employee->normal_hour_rate ?? 0, 2) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">Rs {{ number_format($employee->ot_hour_rate ?? 0, 2) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">Rs {{ number_format($employee->daily_expenses ?? 0, 2) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $netSalary = $employee->salaryMonths->sum('remaining_amount');
                            @endphp
                            <div class="text-sm font-medium {{ $netSalary > 0 ? 'text-green-600' : 'text-gray-900' }}">
                                Rs {{ number_format($netSalary, 2) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.salary.employee', $employee->id) }}" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-users text-4xl mb-3"></i>
                            <p>No employees found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <!-- Total Employees -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                    <i class="fas fa-users text-blue-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Employees</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $employees->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Total Net Salary -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                    <i class="fas fa-money-bill-wave text-green-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Net Salary</p>
                    @php
                        $totalNetSalary = $employees->sum(function($emp) {
                            return $emp->salaryMonths->sum('remaining_amount');
                        });
                    @endphp
                    <p class="text-2xl font-bold text-gray-900">Rs {{ number_format($totalNetSalary, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Total Paid This Month -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                    <i class="fas fa-calendar-check text-purple-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Paid This Month</p>
                    @php
                        $currentMonth = now()->month;
                        $currentYear = now()->year;
                        $paidThisMonth = $employees->sum(function($emp) use ($currentMonth, $currentYear) {
                            $month = $emp->salaryMonths->where('year', $currentYear)->where('month', $currentMonth)->first();
                            return $month ? $month->paid_amount : 0;
                        });
                    @endphp
                    <p class="text-2xl font-bold text-gray-900">Rs {{ number_format($paidThisMonth, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
