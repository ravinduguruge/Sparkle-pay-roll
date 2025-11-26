<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('keyEmployee')->orderBy('name')->get();
        return view('admin.projects.projects-details', compact('projects'));
    }

    public function create()
    {
        $employees = User::where('role', 'employee')->orderBy('name')->get();
        return view('admin.projects.projects-add', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'key_employee_id' => 'required|exists:users,id',
            'total_budget' => 'required|numeric|min:0',
            'advance_payment' => 'nullable|numeric|min:0',
            'key_employee_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,completed,on_hold',
        ]);

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'key_employee_id' => $request->key_employee_id,
            'total_budget' => $request->total_budget,
            'advance_payment' => $request->advance_payment ?? 0,
            'key_employee_amount' => $request->key_employee_amount ?? 0,
            'remaining_budget' => $request->total_budget,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function edit(Project $project)
    {
        $employees = User::where('role', 'employee')->orderBy('name')->get();
        return view('admin.projects.projects-manage', compact('project', 'employees'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'key_employee_id' => 'required|exists:users,id',
            'total_budget' => 'required|numeric|min:0',
            'advance_payment' => 'nullable|numeric|min:0',
            'new_key_employee_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,completed,on_hold',
        ]);

        $advancePayment = $request->advance_payment ?? 0;
        $budgetDifference = $request->total_budget - $project->total_budget;
        $advanceDifference = $advancePayment - ($project->advance_payment ?? 0);
        
        // Add new amount to existing key employee amount
        $newAmount = $request->new_key_employee_amount ?? 0;
        $totalKeyEmployeeAmount = ($project->key_employee_amount ?? 0) + $newAmount;
        
        $project->update([
            'name' => $request->name,
            'description' => $request->description,
            'key_employee_id' => $request->key_employee_id,
            'total_budget' => $request->total_budget,
            'advance_payment' => $advancePayment,
            'key_employee_amount' => $totalKeyEmployeeAmount,
            'remaining_budget' => $project->remaining_budget + $budgetDifference - $advanceDifference,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('admin.projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}
