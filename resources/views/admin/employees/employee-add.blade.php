@extends('layouts.master')

@section('title', 'Add New Employee')

@section('content')
<div class="p-6">
    <div class="w-full">
        <div class="mb-4">
            <a href="{{ route('admin.employees.index') }}" class="bg-[#19264bff] hover:bg-[#2a3a6b] text-white px-6 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                ← Back to Employees
            </a>
        </div>

        <h1 class="text-2xl font-bold text-slate-800 mb-4">Add New Employee</h1>

        <div class="bg-white rounded-2xl shadow p-6">
            <form action="{{ route('admin.employees.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3">
                    <!-- Left Column -->
                    <!-- Name -->
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" 
                               class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                               required placeholder="Enter full name">
                        @error('name')
                            <p class="text-red-600 text-xs mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                               class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                               required placeholder="employee@example.com">
                        @error('email')
                            <p class="text-red-600 text-xs mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Password *</label>
                        <input type="password" name="password" 
                               class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                               required placeholder="Minimum 6 characters">
                        @error('password')
                            <p class="text-red-600 text-xs mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Confirm Password *</label>
                        <input type="password" name="password_confirmation" 
                               class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                               required placeholder="Re-enter password">
                    </div>

                    <!-- Right Column -->
                    <!-- Normal Hour Rate -->
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Normal Hour Rate (Rs.) *</label>
                        <input type="number" name="normal_hour_rate" value="{{ old('normal_hour_rate') }}" 
                               step="0.01" min="0"
                               class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                               required placeholder="0.00">
                        @error('normal_hour_rate')
                            <p class="text-red-600 text-xs mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- OT Hour Rate -->
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">OT Hour Rate (Rs.) *</label>
                        <input type="number" name="ot_hour_rate" value="{{ old('ot_hour_rate') }}" 
                               step="0.01" min="0"
                               class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                               required placeholder="0.00">
                        @error('ot_hour_rate')
                            <p class="text-red-600 text-xs mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium text-sm">
                        Create Employee
                    </button>
                    <a href="{{ route('admin.employees.index') }}" class="px-6 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 font-medium text-sm">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection