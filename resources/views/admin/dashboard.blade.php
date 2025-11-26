@extends('layouts.master')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold text-slate-800 mb-6">Admin Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow p-5">
            <div class="text-xs uppercase text-slate-500 font-semibold">Total Employees</div>
            <div class="text-3xl font-bold text-slate-800 mt-2">{{ $employeesCount }}</div>
        </div>
        <div class="bg-white rounded-2xl shadow p-5">
            <div class="text-xs uppercase text-slate-500 font-semibold">Total Projects</div>
            <div class="text-3xl font-bold text-slate-800 mt-2">{{ $projectsCount }}</div>
        </div>
        <div class="bg-white rounded-2xl shadow p-5">
            <div class="text-xs uppercase text-slate-500 font-semibold">Pending Work</div>
            <div class="text-3xl font-bold text-orange-600 mt-2">{{ $pendingWorks }}</div>
        </div>
        <div class="bg-white rounded-2xl shadow p-5">
            <div class="text-xs uppercase text-slate-500 font-semibold">Total Net Salary</div>
            <div class="text-3xl font-bold text-green-600 mt-2">Rs. {{ number_format($totalNetSalary, 2) }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-white rounded-2xl shadow p-5">
            <h2 class="font-semibold text-lg mb-4">Quick Actions</h2>
            <div class="space-y-2">
                <a href="{{ route('admin.employees.index') }}" class="block px-4 py-3 bg-indigo-50 hover:bg-indigo-100 rounded-lg text-indigo-700 font-medium transition">
                    <i class="fas fa-users mr-2"></i> Manage Employees
                </a>
                <a href="{{ route('admin.projects.index') }}" class="block px-4 py-3 bg-blue-50 hover:bg-blue-100 rounded-lg text-blue-700 font-medium transition">
                    <i class="fas fa-project-diagram mr-2"></i> Manage Projects
                </a>
                <a href="{{ route('admin.work_approvals.index') }}" class="block px-4 py-3 bg-orange-50 hover:bg-orange-100 rounded-lg text-orange-700 font-medium transition">
                    <i class="fas fa-clipboard-check mr-2"></i> Approve Work Entries
                </a>
                <a href="{{ route('admin.salary.index') }}" class="block px-4 py-3 bg-green-50 hover:bg-green-100 rounded-lg text-green-700 font-medium transition">
                    <i class="fas fa-money-bill-wave mr-2"></i> Salary Management
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-5">
            <h2 class="font-semibold text-lg mb-4">System Overview</h2>
            <div class="space-y-3 text-sm text-slate-600">
                <p><strong>Employees:</strong> {{ $employeesCount }} active employees in the system</p>
                <p><strong>Projects:</strong> {{ $projectsCount }} projects being tracked</p>
                <p><strong>Pending Approvals:</strong> {{ $pendingWorks }} work entries awaiting approval</p>
                <p><strong>Outstanding Salary:</strong> Rs. {{ number_format($totalNetSalary, 2) }} to be paid</p>
            </div>
        </div>
    </div>
</div>
@endsection
