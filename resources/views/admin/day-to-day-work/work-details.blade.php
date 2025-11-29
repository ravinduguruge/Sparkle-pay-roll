@extends('layouts.master')

@section('title', 'Day-to-Day Work Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Day-to-Day Work Details</h1>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 mb-6 rounded-md">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Tabs -->
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button onclick="showTab('pending')" id="pending-tab" class="tab-button active border-[#19264bff] text-[#19264bff] whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Pending Approvals
                    @if(isset($pending) && $pending->count() > 0)
                        <span class="bg-red-100 text-red-600 ml-2 py-0.5 px-2 rounded-full text-xs font-semibold">{{ $pending->count() }}</span>
                    @endif
                </button>
                <button onclick="showTab('approved')" id="approved-tab" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Approved Work
                </button>
            </nav>
        </div>
    </div>

    <!-- Pending Work Table -->
    <div id="pending-content" class="tab-content">
        <div class="bg-white rounded-lg shadow-md overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#19264bff]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Work Partners</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Project</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Travel Start</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Site On</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Site Out</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Travel End</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Hours</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Daily Expenses</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 overflow-x-auto">
                    @forelse($pending as $work)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($work->work_date)->format('M d, Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $work->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $work->user->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $partners = is_array($work->work_partners) ? $work->work_partners : [];
                                @endphp
                                @if(count($partners) > 0)
                                    <div class="text-xs text-gray-700">
                                        @foreach($partners as $partnerId)
                                            @php
                                                $partner = \App\Models\User::find($partnerId);
                                            @endphp
                                            @if($partner)
                                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded-full mb-1">{{ $partner->name }}</span><br>
                                            @endif
                                        @endforeach
                                        <span class="text-xs text-gray-500">({{ count($partners) }} workers)</span>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">Solo</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $work->project->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $work->travel_start_time ? \Carbon\Carbon::parse($work->travel_start_time)->format('h:i A') : '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $work->site_on_time ? \Carbon\Carbon::parse($work->site_on_time)->format('h:i A') : '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $work->site_out_time ? \Carbon\Carbon::parse($work->site_out_time)->format('h:i A') : '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $work->travel_end_time ? \Carbon\Carbon::parse($work->travel_end_time)->format('h:i A') : 'Not completed' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($work->total_hours)
                                    <div class="text-sm font-medium text-gray-900">{{ number_format($work->total_hours, 2) }} hrs</div>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-red-600">Rs {{ number_format($work->expenses->sum('amount'), 2) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs truncate">{{ $work->description ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($work->travel_end_time)
                                    <form action="{{ route('admin.work_approvals.approve', $work->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-check-circle"></i> Approve
                                        </button>
                                    </form>
                                @else
                                    <span class="text-yellow-600 text-xs">In Progress</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-clipboard-check text-4xl mb-3"></i>
                                <p>No pending work entries.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Approved Work Table -->
    <div id="approved-content" class="tab-content hidden">
        <div class="bg-white rounded-lg shadow-md overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#19264bff]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Work Partners</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Project</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Travel Start</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Site On</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Site Out</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Travel End</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Hours</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Daily Expenses</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($approved as $work)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($work->work_date)->format('M d, Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $work->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $work->user->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $partners = is_array($work->work_partners) ? $work->work_partners : [];
                                @endphp
                                @if(count($partners) > 0)
                                    <div class="text-xs text-gray-700">
                                        @foreach($partners as $partnerId)
                                            @php
                                                $partner = \App\Models\User::find($partnerId);
                                            @endphp
                                            @if($partner)
                                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded-full mb-1">{{ $partner->name }}</span><br>
                                            @endif
                                        @endforeach
                                        <span class="text-xs text-gray-500">({{ count($partners) }} workers)</span>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">Solo</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $work->project->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $work->travel_start_time ? \Carbon\Carbon::parse($work->travel_start_time)->format('h:i A') : '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $work->site_on_time ? \Carbon\Carbon::parse($work->site_on_time)->format('h:i A') : '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $work->site_out_time ? \Carbon\Carbon::parse($work->site_out_time)->format('h:i A') : '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $work->travel_end_time ? \Carbon\Carbon::parse($work->travel_end_time)->format('h:i A') : '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($work->total_hours, 2) }} hrs</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-red-600">Rs {{ number_format($work->expenses->sum('amount'), 2) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs truncate">{{ $work->description ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Approved
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-clipboard-check text-4xl mb-3"></i>
                                <p>No approved work entries yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-[#19264bff]', 'text-[#19264bff]');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-content').classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById(tabName + '-tab');
    activeTab.classList.add('active', 'border-[#19264bff]', 'text-[#19264bff]');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
}
</script>
@endsection
