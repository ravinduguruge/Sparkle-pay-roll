@extends('layouts.master')

@section('title', 'Daily Expenses & Advances')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Daily Expenses & Advances</h1>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 mb-6 rounded-md">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 mb-6 rounded-md">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Add New Expense/Advance Form -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="bg-[#19264bff] px-6 py-4">
            <h3 class="text-lg font-semibold text-white">
                <i class="fas fa-plus-circle mr-2"></i>Add New Expense/Advance
            </h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.employee_expenses.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Employee <span class="text-red-500">*</span></label>
                        <select name="user_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#19264bff] focus:ring focus:ring-[#19264bff] focus:ring-opacity-50">
                            <option value="">Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type <span class="text-red-500">*</span></label>
                        <select name="type" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#19264bff] focus:ring focus:ring-[#19264bff] focus:ring-opacity-50">
                            <option value="expense">Daily Expense</option>
                            <option value="advance">Advance Payment</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date <span class="text-red-500">*</span></label>
                        <input type="date" name="expense_date" required value="{{ date('Y-m-d') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#19264bff] focus:ring focus:ring-[#19264bff] focus:ring-opacity-50">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Amount (Rs.) <span class="text-red-500">*</span></label>
                        <input type="number" name="amount" step="0.01" min="0" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#19264bff] focus:ring focus:ring-[#19264bff] focus:ring-opacity-50" placeholder="0.00">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <input type="text" name="description" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#19264bff] focus:ring focus:ring-[#19264bff] focus:ring-opacity-50" placeholder="Optional description">
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-[#19264bff] hover:bg-[#0f1729] text-white font-bold py-2 px-6 rounded transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>Add Entry
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Expenses List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-[#19264bff] px-6 py-4">
            <h3 class="text-lg font-semibold text-white">
                <i class="fas fa-list mr-2"></i>Recent Expenses & Advances
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#19264bff]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Month</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($expenses as $expense)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($expense->expense_date)->format('M d, Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $expense->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $expense->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($expense->type === 'expense')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-money-bill-wave mr-1"></i> Expense
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-hand-holding-usd mr-1"></i> Advance
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-red-600">- Rs {{ number_format($expense->amount, 2) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $expense->description ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-700">{{ \Carbon\Carbon::create($expense->year, $expense->month)->format('M Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <form action="{{ route('admin.employee_expenses.destroy', $expense->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this entry? Salary will be recalculated.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-3"></i>
                                <p>No expenses or advances recorded yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($expenses->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

