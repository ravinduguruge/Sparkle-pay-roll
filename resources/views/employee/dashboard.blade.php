@extends('layouts.master')

@section('content')
<div class="p-6">
    <h1 class="text-3xl font-bold text-slate-800 mb-6">Employee Dashboard</h1>

    <div class="grid gap-6 md:grid-cols-3">
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

        {{-- Today status --}}
        <div class="bg-white rounded-2xl shadow p-5">
            <div class="text-xs uppercase text-slate-500 font-semibold">Today</div>
            @if($openWorkEntry)
                <p class="mt-2 text-sm text-slate-700">
                    Job In at: {{ $openWorkEntry->job_in_at->format('h:i A') }}<br>
                    Project: <span class="font-semibold">{{ $openWorkEntry->project->name }}</span>
                </p>
                <a href="{{ route('employee.job_out_form', $openWorkEntry) }}"
                   class="mt-3 inline-flex items-center px-4 py-2 rounded-full bg-rose-600 text-white text-xs font-semibold">
                    Job Out
                </a>
            @else
                <form action="{{ route('employee.job_in') }}" method="POST" class="mt-3 space-y-3">
                    @csrf
                    <div>
                        <label class="text-xs text-slate-600">Select Project</label>
                        <select name="project_id" class="mt-1 w-full border rounded-xl px-3 py-2 text-sm">
                            <option value="">-- Choose project --</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="inline-flex items-center px-4 py-2 rounded-full bg-indigo-600 text-white text-xs font-semibold">
                        Job In (Now)
                    </button>
                    <p class="text-[11px] text-slate-500">
                        Job in time will automatically use the current date and time.
                    </p>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
