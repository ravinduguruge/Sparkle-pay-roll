<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\WorkEntry;
use App\Models\WorkExpense;
use App\Models\Project as ProjectModel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WorkEntryController extends Controller
{
    public function jobIn(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
        ]);

        $user = Auth::user();
        $now = Carbon::now();

        // ensure no other open job
        $existingOpen = WorkEntry::where('user_id', $user->id)
            ->whereNull('job_out_time')
            ->first();

        if ($existingOpen) {
            return back()->withErrors('You already have an open job today.');
        }

        WorkEntry::create([
            'user_id'      => $user->id,
            'project_id'   => $request->project_id,
            'work_date'    => $now->toDateString(),
            'job_in_time'  => $now,
            'status'       => 'pending', // until admin approves
        ]);

        return back()->with('success', 'Job In recorded successfully.');
    }

    public function showJobOutForm(WorkEntry $workEntry)
    {
        $this->authorizeEntry($workEntry);

        if ($workEntry->job_out_time) {
            abort(403, 'Already job out.');
        }

        return view('employee.job_out_form', compact('workEntry'));
    }

    public function jobOut(Request $request, WorkEntry $workEntry)
    {
        $this->authorizeEntry($workEntry);

        if ($workEntry->job_out_time) {
            return redirect()->route('employee.dashboard')
                ->withErrors('You already job out this work.');
        }

        $request->validate([
            'description' => 'required|string',
            'partners'    => 'nullable|string',
            'expense_description.*' => 'nullable|string',
            'expense_amount.*'      => 'nullable|numeric|min:0',
        ]);

        $now = Carbon::now();
        
        $workEntry->description = $request->description;
        $workEntry->partners    = $request->partners;
        $workEntry->job_out_time = $now;
        
        // Calculate total hours worked
        $jobInTime = Carbon::parse($workEntry->job_in_time);
        $totalHours = $jobInTime->diffInMinutes($now) / 60; // Convert minutes to hours
        $workEntry->total_hours = round($totalHours, 2);
        
        $workEntry->save();

        // expenses (optional, multiple rows)
        $descs = $request->expense_description ?? [];
        $amounts = $request->expense_amount ?? [];

        foreach ($descs as $idx => $desc) {
            $desc = trim($desc);
            $amount = $amounts[$idx] ?? null;

            if ($desc !== '' && $amount !== null && $amount > 0) {
                WorkExpense::create([
                    'work_entry_id' => $workEntry->id,
                    'description'   => $desc,
                    'amount'        => $amount,
                ]);

                // subtract from project remaining budget
                $project = $workEntry->project;
                $project->remaining_budget -= $amount;
                $project->save();
            }
        }

        return redirect()->route('employee.dashboard')
            ->with('success', 'Job Out completed. Waiting for admin approval.');
    }

    protected function authorizeEntry(WorkEntry $workEntry)
    {
        if ($workEntry->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
