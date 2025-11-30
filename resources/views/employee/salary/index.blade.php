<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Salary') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Net Salary Summary -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-xl p-8 mb-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm uppercase tracking-wide mb-2">Total Net Salary (All Months)</p>
                        <h1 class="text-5xl font-bold">Rs {{ number_format($netSalary, 2) }}</h1>
                    </div>
                    <div class="text-6xl opacity-20">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
            </div>

            <!-- Monthly Salary Records -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">
                        <i class="fas fa-calendar-alt mr-2"></i>Monthly Salary Details
                    </h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Month</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Hours Worked</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Earnings</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Work Expenses</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Daily Expenses</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Total Salary</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Paid</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Remaining</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($months as $month)
                                    @php
                                        $workEntries = \App\Models\WorkEntry::where('user_id', Auth::id())
                                            ->where('status', 'approved')
                                            ->whereYear('work_date', $month->year)
                                            ->whereMonth('work_date', $month->month)
                                            ->get();
                                        
                                        $totalHours = $workEntries->sum('total_hours');
                                        $earnings = $month->monthly_salary - $month->allowance_total;
                                        
                                        $dailyExpenses = \App\Models\EmployeeExpense::where('user_id', Auth::id())
                                            ->where('year', $month->year)
                                            ->where('month', $month->month)
                                            ->sum('amount');
                                        
                                        // Recalculate actual salary: earnings + work expenses - daily expenses
                                        $actualSalary = $earnings + $month->allowance_total;
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <i class="fas fa-calendar mr-2 text-blue-600"></i>
                                                {{ \Carbon\Carbon::create($month->year, $month->month)->format('F Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ number_format($totalHours, 2) }} hrs</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-green-600">Rs {{ number_format($earnings, 2) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-blue-600">+ Rs {{ number_format($month->allowance_total, 2) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-red-600">- Rs {{ number_format($dailyExpenses, 2) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">Rs {{ number_format($month->monthly_salary, 2) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-orange-600">Rs {{ number_format($month->paid_amount, 2) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold {{ $month->remaining_amount >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                Rs {{ number_format($month->remaining_amount, 2) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('employee.salary.month', [$month->year, $month->month]) }}" 
                                               class="text-blue-600 hover:text-blue-900 font-medium">
                                                <i class="fas fa-eye"></i> View Details
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                            <i class="fas fa-inbox text-4xl mb-3"></i>
                                            <p>No salary records found.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mt-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>Salary Calculation:</strong> Your total salary includes hourly earnings plus work expense reimbursements, minus any daily expenses or advances recorded by the admin.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
