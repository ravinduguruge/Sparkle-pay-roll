@extends('layouts.master')

@section('title', 'Manage Employees')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Manage Employees</h1>
        <a href="{{ route('admin.employees.create') }}" class="bg-[#19264bff] hover:bg-[#2a3a6b] text-white px-6 py-2 rounded-md text-sm font-medium transition-colors duration-200">
            + Add Employee
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow overflow-hidden">
        @if($employees->isEmpty())
            <div class="p-8 text-center text-slate-500">
                <p>No employees found. <a href="">Add new employee</a></p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-[#19264bff]">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Normal Rate</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">OT Rate</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @foreach($employees as $employee)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-800">
                                    {{ $employee->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                    {{ $employee->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                    Rs. {{ number_format($employee->normal_hour_rate, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                    Rs. {{ number_format($employee->ot_hour_rate, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.employees.edit', $employee) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.employees.destroy', $employee) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this employee?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
