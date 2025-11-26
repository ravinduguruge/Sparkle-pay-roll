<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkEntry;

class WorkApprovalController extends Controller
{
    public function index()
    {
        $pending = WorkEntry::with(['user', 'project'])
            ->where('status', 'pending')
            ->orderByDesc('work_date')
            ->get();

        $approved = WorkEntry::with(['user', 'project'])
            ->where('status', 'approved')
            ->orderByDesc('work_date')
            ->limit(50)
            ->get();

        return view('admin.work_approvals.index', compact('pending', 'approved'));
    }

    public function approve(WorkEntry $workEntry)
    {
        $workEntry->status = 'approved';
        $workEntry->save();

        return back()->with('success', 'Work entry approved.');
    }
}
