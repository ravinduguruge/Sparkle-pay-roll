@extends('layouts.master')

@section('title', 'Manage Salary - ' . $user->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manage Salary</h1>
            <p class="text-gray-600 mt-1">{{ $user->name }} - {{ $user->email }}</p>
        </div>
        <a href="{{ route('admin.salary.index') }}" class="text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left mr-2"></i>Back to Salary List
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 mb-6 rounded-md">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Employee Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-xs text-gray-500 uppercase">Normal Hour Rate</p>
            <p class="text-xl font-bold text-gray-900">Rs {{ number_format($user->normal_hour_rate ?? 0, 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-xs text-gray-500 uppercase">OT Hour Rate</p>
            <p class="text-xl font-bold text-gray-900">Rs {{ number_format($user->ot_hour_rate ?? 0, 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-xs text-gray-500 uppercase">Daily Expenses</p>
            <p class="text-xl font-bold text-gray-900">Rs {{ number_format($user->daily_expenses ?? 0, 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-xs text-gray-500 uppercase">Remaining Salary</p>
            @php
                $remainingSalary = $user->salaryMonths->sum('remaining_amount');
            @endphp
            <p class="text-xl font-bold text-orange-600">Rs {{ number_format($remainingSalary, 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-xs text-gray-500 uppercase">Total Net Salary</p>
            @php
                $netSalary = $user->salaryMonths->sum('monthly_salary');
            @endphp
            <p class="text-xl font-bold text-green-600">Rs {{ number_format($netSalary, 2) }}</p>
        </div>
    </div>

    <!-- Add Monthly Salary Form -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Set Monthly Salary</h2>
        <form method="POST" action="{{ route('admin.salary.month.store', $user->id) }}">
            @csrf
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                    <select id="year" name="year" class="border-gray-300 focus:border-[#19264bff] focus:ring-[#19264bff] rounded-md shadow-sm block w-full" required>
                        @for($y = date('Y'); $y >= date('Y') - 2; $y--)
                            <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label for="month" class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                    <select id="month" name="month" class="border-gray-300 focus:border-[#19264bff] focus:ring-[#19264bff] rounded-md shadow-sm block w-full" required>
                        @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $index => $monthName)
                            <option value="{{ $index + 1 }}" {{ ($index + 1) == date('n') ? 'selected' : '' }}>{{ $monthName }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="monthly_salary" class="block text-sm font-medium text-gray-700 mb-1">Monthly Salary (Rs)</label>
                    <input type="number" step="0.01" id="monthly_salary" name="monthly_salary" class="border-gray-300 focus:border-[#19264bff] focus:ring-[#19264bff] rounded-md shadow-sm block w-full" placeholder="0.00" required>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="bg-[#19264bff] hover:bg-[#2a3a6b] text-white px-6 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>Save Monthly Salary
                </button>
            </div>
        </form>
    </div>

    <!-- Salary History by Month -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">Salary History</h2>
        </div>

        @forelse($months as $month)
            <div class="border-b border-gray-200 last:border-b-0">
                <!-- Month Header -->
                <div class="px-6 py-4 bg-gray-50 cursor-pointer hover:bg-gray-100" onclick="toggleMonth('month-{{ $month->id }}')">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-alt text-[#19264bff] mr-3"></i>
                            <div>
                                <h3 class="font-semibold text-gray-900">
                                    {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}
                                </h3>
                                <p class="text-xs text-gray-500 mt-1">
                                    Monthly: Rs {{ number_format($month->monthly_salary, 2) }} | 
                                    Paid: Rs {{ number_format($month->paid_amount, 2) }} | 
                                    Remaining: Rs {{ number_format($month->remaining_amount, 2) }}
                                </p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>

                <!-- Month Details (Collapsible) -->
                <div id="month-{{ $month->id }}" class="hidden">
                    <div class="px-6 py-4">
                        <!-- Summary -->
                        <div class="grid grid-cols-4 gap-4 mb-4">
                            <div class="bg-blue-50 rounded p-3">
                                <p class="text-xs text-blue-600 font-medium">Monthly Salary</p>
                                <p class="text-lg font-bold text-blue-900">Rs {{ number_format($month->monthly_salary, 2) }}</p>
                            </div>
                            <div class="bg-green-50 rounded p-3">
                                <p class="text-xs text-green-600 font-medium">Total Paid</p>
                                <p class="text-lg font-bold text-green-900">Rs {{ number_format($month->paid_amount, 2) }}</p>
                            </div>
                            <div class="bg-purple-50 rounded p-3">
                                <p class="text-xs text-purple-600 font-medium">Allowance Total</p>
                                <p class="text-lg font-bold text-purple-900">Rs {{ number_format($month->allowance_total, 2) }}</p>
                            </div>
                            <div class="bg-orange-50 rounded p-3">
                                <p class="text-xs text-orange-600 font-medium">Remaining</p>
                                <p class="text-lg font-bold text-orange-900">Rs {{ number_format($month->remaining_amount, 2) }}</p>
                            </div>
                        </div>

                        <!-- Add Payment Form -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-4">
                            <h4 class="font-semibold text-gray-800 mb-3">Add Payment</h4>
                            <form method="POST" action="{{ route('admin.salary.payment.add', $month->id) }}">
                                @csrf
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Paid Date</label>
                                        <input type="date" name="paid_date" value="{{ date('Y-m-d') }}" class="border-gray-300 focus:border-[#19264bff] focus:ring-[#19264bff] rounded-md shadow-sm block w-full text-sm" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                                        <input type="text" name="description" placeholder="e.g., Salary payment" class="border-gray-300 focus:border-[#19264bff] focus:ring-[#19264bff] rounded-md shadow-sm block w-full text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Salary Amount (Rs)</label>
                                        <input type="number" step="0.01" name="salary_amount" placeholder="0.00" class="border-gray-300 focus:border-[#19264bff] focus:ring-[#19264bff] rounded-md shadow-sm block w-full text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Allowance Amount (Rs)</label>
                                        <input type="number" step="0.01" name="allowance_amount" placeholder="0.00" class="border-gray-300 focus:border-[#19264bff] focus:ring-[#19264bff] rounded-md shadow-sm block w-full text-sm">
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                        <i class="fas fa-plus-circle mr-1"></i>Add Payment
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Payment History -->
                        @if($month->payments->count() > 0)
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-2">Payment History</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Salary</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Allowance</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($month->payments as $payment)
                                                <tr>
                                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                                        {{ \Carbon\Carbon::parse($payment->paid_date)->format('M d, Y') }}
                                                    </td>
                                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $payment->description ?? '-' }}</td>
                                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                                        Rs {{ number_format($payment->salary_amount, 2) }}
                                                    </td>
                                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                                        Rs {{ number_format($payment->allowance_amount, 2) }}
                                                    </td>
                                                    <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        Rs {{ number_format($payment->salary_amount + $payment->allowance_amount, 2) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 italic">No payments recorded yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="px-6 py-8 text-center text-gray-500">
                <i class="fas fa-calendar-times text-4xl mb-3"></i>
                <p>No salary records yet. Set a monthly salary above to get started.</p>
            </div>
        @endforelse
    </div>
</div>

<script>
function toggleMonth(id) {
    const element = document.getElementById(id);
    element.classList.toggle('hidden');
}
</script>
@endsection
