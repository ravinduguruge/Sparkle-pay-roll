<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::where('role', 'employee')->orderBy('name')->get();
        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'normal_hour_rate' => 'required|numeric|min:0',
            'ot_hour_rate'     => 'required|numeric|min:0',
        ]);

        User::create([
            'name'  => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role'  => 'employee',
            'normal_hour_rate' => $request->normal_hour_rate,
            'ot_hour_rate'     => $request->ot_hour_rate,
        ]);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee created.');
    }

    public function edit(User $employee)
    {
        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, User $employee)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'normal_hour_rate' => 'required|numeric|min:0',
            'ot_hour_rate'     => 'required|numeric|min:0',
        ]);

        $employee->update($request->only('name', 'email', 'normal_hour_rate', 'ot_hour_rate'));

        return redirect()->route('admin.employees.index')->with('success', 'Employee updated.');
    }

    public function destroy(User $employee)
    {
        $employee->delete();
        return back()->with('success', 'Employee deleted.');
    }
}

