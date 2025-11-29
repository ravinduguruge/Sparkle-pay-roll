@extends('layouts.master')

@section('title', 'Edit Employee')

@section('content')
<div class="p-6">
    <div class="w-full">
        <div class="mb-4">
            <a href="{{ route('admin.employees.index') }}" class="bg-[#19264bff] hover:bg-[#2a3a6b] text-white px-6 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                ← Back to Employees
            </a>
        </div>

        <h1 class="text-2xl font-bold text-slate-800 mb-4">Edit Employee</h1>

        <div class="bg-white rounded-2xl shadow p-6">
            <form action="{{ route('admin.employees.update', $employee) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3">
                    <!-- Left Column -->
                    <!-- Name -->
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name', $employee->name) }}" 
                               class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                               required placeholder="Enter full name">
                        @error('name')
                            <p class="text-red-600 text-xs mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email', $employee->email) }}" 
                               class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                               required placeholder="employee@example.com">
                        @error('email')
                            <p class="text-red-600 text-xs mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">New Password (leave blank to keep current)</label>
                        <input type="password" name="password" 
                               class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                               placeholder="Leave blank to keep current password">
                        @error('password')
                            <p class="text-red-600 text-xs mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Confirm New Password</label>
                        <input type="password" name="password_confirmation" 
                               class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                               placeholder="Re-enter new password">
                    </div>

                    <!-- Right Column -->
                    <!-- Normal Hour Rate -->
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Normal Hour Rate (Rs.) *</label>
                        <input type="number" name="normal_hour_rate" value="{{ old('normal_hour_rate', $employee->normal_hour_rate) }}" 
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
                        <input type="number" name="ot_hour_rate" value="{{ old('ot_hour_rate', $employee->ot_hour_rate) }}" 
                               step="0.01" min="0"
                               class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                               required placeholder="0.00">
                        @error('ot_hour_rate')
                            <p class="text-red-600 text-xs mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Permissions Section -->
                    <div class="md:col-span-2 mt-4 pt-4 border-t border-slate-200">
                        <h3 class="text-sm font-semibold text-slate-700 mb-3">Permissions</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Can Manage Work -->
                            <div class="flex items-center">
                                <input type="checkbox" name="can_manage_work" id="can_manage_work" value="1" 
                                       {{ old('can_manage_work', $employee->can_manage_work) ? 'checked' : '' }}
                                       class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                                <label for="can_manage_work" class="ml-2 text-sm text-slate-700">
                                    <span class="font-medium">Can Manage Work</span>
                                    <span class="block text-xs text-slate-500">Allow to submit daily work entries</span>
                                </label>
                            </div>

                            <!-- Can Add Purchases -->
                            <div class="flex items-center">
                                <input type="checkbox" name="can_add_purchases" id="can_add_purchases" value="1" 
                                       {{ old('can_add_purchases', $employee->can_add_purchases) ? 'checked' : '' }}
                                       class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                                <label for="can_add_purchases" class="ml-2 text-sm text-slate-700">
                                    <span class="font-medium">Can Add Purchases</span>
                                    <span class="block text-xs text-slate-500">Allow to add company tool purchases</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium text-sm">
                        Update Employee
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