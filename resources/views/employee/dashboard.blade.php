@extends('layouts.master')

@section('content')
<div class="p-6">
    <h1 class="text-3xl font-bold text-slate-800 mb-6">Employee Dashboard</h1>

    {{-- Summary Cards --}}
    <div class="grid gap-6 md:grid-cols-3 mb-6">
        {{-- Net salary card --}}
        <div class="bg-white rounded-2xl shadow p-5">
            <div class="text-xs uppercase text-slate-500 font-semibold">Net Salary</div>
            <div class="mt-2 text-3xl font-bold text-slate-800">
                Rs. {{ number_format($netSalary, 2) }}
            </div>
            <p class="mt-1 text-xs text-slate-500">
                Total remaining salaries to be received.
            </p>
        </div>

        {{-- Hour rates --}}
        <div class="bg-white rounded-2xl shadow p-5">
            <div class="text-xs uppercase text-slate-500 font-semibold">Rates</div>
            <div class="mt-3 flex justify-between text-sm">
                <div>
                    <div class="text-slate-500 text-xs">Normal Hour</div>
                    <div class="font-semibold text-slate-800">
                        Rs. {{ number_format($user->normal_hour_rate, 2) }}
                    </div>
                </div>
                <div>
                    <div class="text-slate-500 text-xs">OT Hour</div>
                    <div class="font-semibold text-slate-800">
                        Rs. {{ number_format($user->ot_hour_rate, 2) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Permissions --}}
        <div class="bg-white rounded-2xl shadow p-5">
            <div class="text-xs uppercase text-slate-500 font-semibold">Permissions</div>
            <div class="mt-3 space-y-2 text-sm">
                <div class="flex items-center">
                    <span class="w-3 h-3 rounded-full {{ $user->can_manage_work ? 'bg-green-500' : 'bg-red-500' }} mr-2"></span>
                    <span class="text-slate-700">Manage Work</span>
                </div>
                <div class="flex items-center">
                    <span class="w-3 h-3 rounded-full {{ $user->can_add_purchases ? 'bg-green-500' : 'bg-red-500' }} mr-2"></span>
                    <span class="text-slate-700">Add Purchases</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-slate-800 mb-4">Quick Actions</h2>
        <div class="grid md:grid-cols-2 gap-4">
            @if($user->can_manage_work || $user->role === 'admin')
            <a href="{{ route('employee.daily_work') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                <div class="flex-shrink-0 w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="font-semibold text-slate-800">Submit Daily Work</h3>
                    <p class="text-sm text-slate-600">Add today's work entry and expenses</p>
                </div>
            </a>
            @endif

            <a href="{{ route('employee.salary.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
                <div class="flex-shrink-0 w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="font-semibold text-slate-800">View My Salary</h3>
                    <p class="text-sm text-slate-600">Check salary details and payments</p>
                </div>
            </a>
        </div>
    </div>

    {{-- Recent Work History --}}
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-slate-800 mb-4">Recent Work Entries</h2>
        
        @if($recentWork && $recentWork->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-3 px-4 text-sm font-semibold text-slate-700">Date</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-slate-700">Project</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-slate-700">Hours</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-slate-700">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentWork as $work)
                    <tr class="border-b hover:bg-slate-50">
                        <td class="py-3 px-4 text-sm">{{ \Carbon\Carbon::parse($work->work_date)->format('M d, Y') }}</td>
                        <td class="py-3 px-4 text-sm">{{ $work->project->name }}</td>
                        <td class="py-3 px-4 text-sm">{{ number_format($work->total_hours, 2) }} hrs</td>
                        <td class="py-3 px-4">
                            @if($work->status === 'approved')
                                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Approved</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Pending</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-slate-600 text-sm">No work entries yet. Submit your first daily work entry!</p>
        @endif
    </div>
</div>
@endsection
