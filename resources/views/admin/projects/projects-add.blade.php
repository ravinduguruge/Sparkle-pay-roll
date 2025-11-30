@extends('layouts.master')

@section('title', 'Add New Project')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Add New Project</h1>
        <a href="{{ route('admin.projects.index') }}" class="bg-[#19264bff] hover:bg-[#2a3a6b] text-white px-6 py-2 rounded-md text-sm font-medium transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Back to Projects
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('admin.projects.store') }}">
            @csrf

            <div class="grid grid-cols-2 gap-x-6 gap-y-3">
                <!-- Project Name -->
                <div>
                    <x-input-label  for="name" :value="__('Project Name')" class="block text-xs font-medium text-slate-700 mb-1" />
                    <x-text-input id="name" class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" type="text" name="name" :value="old('name')" required autofocus placeholder="Enter project name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <!-- Key Employee -->
                <div>
                    <x-input-label for="key_employee_id" :value="__('Key Employee')" class="block text-xs font-medium text-slate-700 mb-1" />
                    <select id="key_employee_id" name="key_employee_id" class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="">Select Employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('key_employee_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }} - {{ $employee->email }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('key_employee_id')" class="mt-1" />
                </div>

                <!-- Total Budget -->
                <div>
                    <x-input-label for="total_budget" :value="__('Total Budget (Rs)')" class="block text-xs font-medium text-slate-700 mb-1" />
                    <x-text-input id="total_budget" class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" type="number" step="0.01" name="total_budget" :value="old('total_budget')" required placeholder="0.00" />
                    <x-input-error :messages="$errors->get('total_budget')" class="mt-1" />
                </div>

                <!-- Advance Payment -->
                <div>
                    <x-input-label for="advance_payment" :value="__('Advance Payment (Rs)')" class="block text-xs font-medium text-slate-700 mb-1" />
                    <x-text-input id="advance_payment" class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" type="number" step="0.01" name="advance_payment" :value="old('advance_payment')" placeholder="0.00" />
                    <x-input-error :messages="$errors->get('advance_payment')" class="mt-1" />
                </div>

                <!-- Key Employee Amount -->
                <div>
                    <x-input-label for="key_employee_amount" :value="__('Key Employee Amount (Rs)')" class="block text-xs font-medium text-slate-700 mb-1" />
                    <x-text-input id="key_employee_amount" class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" type="number" step="0.01" name="key_employee_amount" :value="old('key_employee_amount')" placeholder="0.00" />
                    <x-input-error :messages="$errors->get('key_employee_amount')" class="mt-1" />
                </div>

                <!-- Amount Spent -->
                <div>
                    <x-input-label for="amount_spent" :value="__('Amount Spent (Rs)')" class="block text-xs font-medium text-slate-700 mb-1" />
                    <x-text-input id="amount_spent" class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" type="number" step="0.01" name="amount_spent" :value="old('amount_spent', 0)" placeholder="0.00" />
                    <x-input-error :messages="$errors->get('amount_spent')" class="mt-1" />
                </div>

                <!-- Amount in Hand -->
                <div>
                    <x-input-label for="amount_in_hand" :value="__('Amount in Hand (Rs)')" class="block text-xs font-medium text-slate-700 mb-1" />
                    <x-text-input id="amount_in_hand" class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" type="number" step="0.01" name="amount_in_hand" :value="old('amount_in_hand', 0)" placeholder="0.00" />
                    <x-input-error :messages="$errors->get('amount_in_hand')" class="mt-1" />
                </div>

                <!-- Status -->
                <div>
                    <x-input-label for="status" :value="__('Status')" class="block text-xs font-medium text-slate-700 mb-1" />
                    <select id="status" name="status" class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="on_hold" {{ old('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-1" />
                </div>

                <!-- Description (Full Width) -->
                <div class="col-span-2">
                    <x-input-label for="description" :value="__('Description')" class="block text-xs font-medium text-slate-700 mb-1" />
                    <textarea id="description" name="description" rows="3" class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter project description (optional)">{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-1" />
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end mt-6 gap-3">
                <a href="{{ route('admin.projects.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                    Cancel
                </a>
                <x-primary-button class="bg-[#19264bff] hover:bg-[#2a3a6b] ">
                    {{ __('Create Project') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
@endsection
