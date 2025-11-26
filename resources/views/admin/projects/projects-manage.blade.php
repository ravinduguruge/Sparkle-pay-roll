@extends('layouts.master')

@section('title', 'Edit Project')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Project</h1>
        <a href="{{ route('admin.projects.index') }}" class="bg-[#19264bff] hover:bg-[#2a3a6b] text-white px-6 py-2 rounded-md text-sm font-medium transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Back to Projects
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('admin.projects.update', $project->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-x-6 gap-y-3">
                <!-- Project Name -->
                <div>
                    <x-input-label for="name" :value="__('Project Name')" class="text-xs" />
                    <x-text-input id="name" class="block mt-1 w-full py-1.5 text-sm" type="text" name="name" :value="old('name', $project->name)" required autofocus placeholder="Enter project name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <!-- Key Employee -->
                <div>
                    <x-input-label for="key_employee_id" :value="__('Key Employee')" class="text-xs" />
                    <select id="key_employee_id" name="key_employee_id" class="border-gray-300 focus:border-[#19264bff] focus:ring-[#19264bff] rounded-md shadow-sm block mt-1 w-full py-1.5 text-sm" required>
                        <option value="">Select Employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ (old('key_employee_id', $project->key_employee_id) == $employee->id) ? 'selected' : '' }}>
                                {{ $employee->name }} - {{ $employee->email }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('key_employee_id')" class="mt-1" />
                </div>

                <!-- Total Budget -->
                <div>
                    <x-input-label for="total_budget" :value="__('Total Budget (Rs)')" class="text-xs" />
                    <x-text-input id="total_budget" class="block mt-1 w-full py-1.5 text-sm" type="number" step="0.01" name="total_budget" :value="old('total_budget', $project->total_budget)" required placeholder="0.00" />
                    <x-input-error :messages="$errors->get('total_budget')" class="mt-1" />
                    <p class="text-xs text-gray-500 mt-1">Current remaining: Rs {{ number_format($project->remaining_budget, 2) }}</p>
                </div>

                <!-- Advance Payment -->
                <div>
                    <x-input-label for="advance_payment" :value="__('Advance Payment (Rs)')" class="text-xs" />
                    <x-text-input id="advance_payment" class="block mt-1 w-full py-1.5 text-sm" type="number" step="0.01" name="advance_payment" :value="old('advance_payment', $project->advance_payment ?? 0)" placeholder="0.00" />
                    <x-input-error :messages="$errors->get('advance_payment')" class="mt-1" />
                </div>

                <!-- Current Key Employee Amount (Read-only) -->
                <div>
                    <x-input-label for="current_key_employee_amount" :value="__('Current Key Employee Amount (Rs)')" class="text-xs" />
                    <x-text-input id="current_key_employee_amount" class="block mt-1 w-full py-1.5 text-sm bg-gray-100" type="text" :value="'Rs ' . number_format($project->key_employee_amount ?? 0, 2)" readonly />
                </div>

                <!-- Add New Key Employee Amount -->
                <div>
                    <x-input-label for="new_key_employee_amount" :value="__('Add New Key Employee Amount (Rs)')" class="text-xs" />
                    <x-text-input id="new_key_employee_amount" class="block mt-1 w-full py-1.5 text-sm" type="number" step="0.01" name="new_key_employee_amount" :value="old('new_key_employee_amount')" placeholder="0.00" />
                    <x-input-error :messages="$errors->get('new_key_employee_amount')" class="mt-1" />
                </div>

                <!-- Status -->
                <div>
                    <x-input-label for="status" :value="__('Status')" class="text-xs" />
                    <select id="status" name="status" class="border-gray-300 focus:border-[#19264bff] focus:ring-[#19264bff] rounded-md shadow-sm block mt-1 w-full py-1.5 text-sm" required>
                        <option value="active" {{ old('status', $project->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="on_hold" {{ old('status', $project->status) == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                        <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-1" />
                </div>

                <!-- Description (Full Width) -->
                <div class="col-span-2">
                    <x-input-label for="description" :value="__('Description')" class="text-xs" />
                    <textarea id="description" name="description" rows="3" class="border-gray-300 focus:border-[#19264bff] focus:ring-[#19264bff] rounded-md shadow-sm block mt-1 w-full py-1.5 text-sm" placeholder="Enter project description (optional)">{{ old('description', $project->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-1" />
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end mt-6 gap-3">
                <a href="{{ route('admin.projects.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                    Cancel
                </a>
                <x-primary-button class="bg-[#19264bff] hover:bg-[#2a3a6b]">
                    {{ __('Update Project') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
@endsection
