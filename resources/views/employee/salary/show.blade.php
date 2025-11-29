<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ \Carbon\Carbon::create($salaryMonth->year, $salaryMonth->month)->format('F Y') }} - Salary Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('employee.salary.index') }}" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-arrow-left"></i> Back to All Months
                </a>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-sm text-gray-500 mb-1">Total Salary</div>
                    <div class="text-2xl font-bold text-gray-900">Rs {{ number_format($salaryMonth->monthly_salary, 2) }}</div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-sm text-gray-500 mb-1">Work Allowances</div>
                    <div class="text-2xl font-bold text-blue-600">+ Rs {{ number_format($salaryMonth->allowance_total, 2) }}</div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-sm text-gray-500 mb-1">Paid Amount</div>
                    <div class="text-2xl font-bold text-orange-600">Rs {{ number_format($salaryMonth->paid_amount, 2) }}</div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-sm text-gray-500 mb-1">Remaining</div>
                    <div class="text-2xl font-bold {{ $salaryMonth->remaining_amount >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        Rs {{ number_format($salaryMonth->remaining_amount, 2) }}
                    </div>
                </div>
            </div>

            <!-- Work Entries -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">
                        <i class="fas fa-briefcase mr-2"></i>Work Entries
                    </h3>
                    
                    @php
                        $workEntries = \App\Models\WorkEntry::where('user_id', Auth::id())
                            ->where('status', 'approved')
                            ->whereYear('work_date', $salaryMonth->year)
                            ->whereMonth('work_date', $salaryMonth->month)
                            ->orderBy('work_date')
                            ->get();
                    @endphp

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Project</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Hours</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Work Expenses</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Description</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($workEntries as $work)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">{{ \Carbon\Carbon::parse($work->work_date)->format('M d, Y') }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $work->project->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold">{{ number_format($work->total_hours, 2) }} hrs</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-blue-600">+ Rs {{ number_format($work->expenses->sum('amount'), 2) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $work->description }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">No work entries for this month.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Daily Expenses & Advances -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">
                        <i class="fas fa-receipt mr-2"></i>Daily Expenses & Advances
                    </h3>
                    
                    @php
                        $dailyExpenses = \App\Models\EmployeeExpense::where('user_id', Auth::id())
                            ->where('year', $salaryMonth->year)
                            ->where('month', $salaryMonth->month)
                            ->orderBy('expense_date')
                            ->get();
                    @endphp

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Amount</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Description</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($dailyExpenses as $expense)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">{{ \Carbon\Carbon::parse($expense->expense_date)->format('M d, Y') }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            @if($expense->type === 'expense')
                                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Expense</span>
                                            @else
                                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Advance</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-red-600">- Rs {{ number_format($expense->amount, 2) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $expense->description ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-4 text-center text-gray-500">No daily expenses or advances for this month.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($dailyExpenses->count() > 0)
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="2" class="px-4 py-3 text-right text-sm font-semibold">Total Deductions:</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-red-600">- Rs {{ number_format($dailyExpenses->sum('amount'), 2) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            @if($salaryMonth->payments && $salaryMonth->payments->count() > 0)
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">
                            <i class="fas fa-history mr-2"></i>Payment History
                        </h3>
                        
                        <div class="space-y-3">
                            @foreach($salaryMonth->payments as $payment)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $payment->description }}</div>
                                    </div>
                                    <div class="text-lg font-bold text-green-600">Rs {{ number_format($payment->amount, 2) }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
