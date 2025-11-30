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

    {{-- Project Financial Information --}}
    @if($projects && $projects->count() > 0)
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-slate-800 mb-4">Project Financial Details</h2>
        <p class="text-sm text-slate-600 mb-4">Select a project to view its financial details</p>
        
        {{-- Project Selector --}}
        <div class="mb-6">
            <label for="projectSelect" class="block text-sm font-medium text-slate-700 mb-2">Select Project:</label>
            <select id="projectSelect" class="w-full md:w-1/2 border border-slate-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="showProjectDetails(this.value)">
                <option value="">-- Select a Project --</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Project Details Container --}}
        @foreach($projects as $project)
        <div id="project-{{ $project->id }}" class="project-details" style="display: none;">
            <div class="border border-slate-200 rounded-lg p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">{{ $project->name }}</h3>
                        @if($project->key_employee_id == $user->id)
                            <span class="inline-block px-3 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full mt-2">
                                <i class="fas fa-user-tie mr-1"></i>You are the Key Employee
                            </span>
                        @endif
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                        {{ $project->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $project->status === 'completed' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $project->status === 'on_hold' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                    </span>
                </div>

                @if($project->description)
                <div class="mb-4 pb-4 border-b border-slate-100">
                    <p class="text-sm text-slate-600">{{ $project->description }}</p>
                </div>
                @endif

                {{-- Financial Summary Cards --}}
                <div class="grid md:grid-cols-3 gap-4 mb-6">
                    @if($project->key_employee_id == $user->id)
                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="text-xs text-green-600 font-semibold uppercase mb-1">Your Allocated Amount</div>
                        <div class="text-2xl font-bold text-green-700">Rs {{ number_format($project->key_employee_amount ?? 0, 2) }}</div>
                    </div>
                    @endif
                    
                    <div class="bg-red-50 rounded-lg p-4">
                        <div class="text-xs text-red-600 font-semibold uppercase mb-1">Amount Spent</div>
                        <div class="text-2xl font-bold text-red-700">Rs {{ number_format($project->amount_spent ?? 0, 2) }}</div>
                    </div>
                    
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="text-xs text-blue-600 font-semibold uppercase mb-1">Amount in Hand</div>
                        <div class="text-2xl font-bold text-blue-700">Rs {{ number_format($project->amount_in_hand ?? 0, 2) }}</div>
                    </div>
                </div>

                {{-- Detailed Breakdown --}}
                <div class="bg-slate-50 rounded-lg p-4">
                    <h4 class="font-semibold text-slate-800 mb-3">Financial Breakdown</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-slate-200">
                {{-- Detailed Breakdown --}}
                <div class="bg-slate-50 rounded-lg p-4">
                    <h4 class="font-semibold text-slate-800 mb-3">Financial Breakdown</h4>
                    <div class="space-y-3">
                        @if($project->key_employee_id == $user->id)
                        <div class="flex justify-between items-center py-2 border-b border-slate-200">
                            <span class="text-sm text-slate-600">Amount Allocated to You:</span>
                            <span class="font-semibold text-green-600">Rs {{ number_format($project->key_employee_amount ?? 0, 2) }}</span>
                        </div>
                        @endif
                        
                        <div class="flex justify-between items-center py-2 border-b border-slate-200">
                            <span class="text-sm text-slate-600">Total Amount Spent:</span>
                            <span class="font-semibold text-red-600">Rs {{ number_format($project->amount_spent ?? 0, 2) }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-slate-200">
                            <span class="text-sm text-slate-600">Amount in Hand (Cash):</span>
                            <span class="font-semibold text-blue-600">Rs {{ number_format($project->amount_in_hand ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>

                @if($project->keyEmployee)
                <div class="mt-4 pt-4 border-t border-slate-200">
                    <p class="text-xs text-slate-500">
                        <i class="fas fa-user-tie mr-1"></i>
                        Key Employee: <span class="font-semibold">{{ $project->keyEmployee->name }}</span>
                        ({{ $project->keyEmployee->email }})
                    </p>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <script>
    function showProjectDetails(projectId) {
        // Hide all project details
        const allProjects = document.querySelectorAll('.project-details');
        allProjects.forEach(project => {
            project.style.display = 'none';
        });
        
        // Show selected project details
        if (projectId) {
            const selectedProject = document.getElementById('project-' + projectId);
            if (selectedProject) {
                selectedProject.style.display = 'block';
            }
        }
    }
    </script>
    @endif

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
