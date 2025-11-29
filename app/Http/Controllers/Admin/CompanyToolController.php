<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyTool;
use Illuminate\Http\Request;

class CompanyToolController extends Controller
{
    public function index()
    {
        $tools = CompanyTool::orderBy('name')->get();
        return view('admin.settings.company-tools', compact('tools'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:company_tools,name',
            'price' => 'required|numeric|min:0',
        ]);

        CompanyTool::create([
            'name' => $request->name,
            'price' => $request->price,
            'is_active' => true,
        ]);

        return redirect()->route('admin.company_tools.index')
            ->with('success', 'Company tool added successfully.');
    }

    public function update(Request $request, CompanyTool $companyTool)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:company_tools,name,' . $companyTool->id,
            'price' => 'required|numeric|min:0',
        ]);

        $companyTool->update([
            'name' => $request->name,
            'price' => $request->price,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.company_tools.index')
            ->with('success', 'Company tool updated successfully.');
    }

    public function destroy(CompanyTool $companyTool)
    {
        // Check if this tool is used in any work expenses
        $usageCount = $companyTool->workExpenses()->count();
        
        if ($usageCount > 0) {
            return redirect()->route('admin.company_tools.index')
                ->with('error', "Cannot delete '{$companyTool->name}' because it has been used in {$usageCount} work expense(s). You can deactivate it instead.");
        }
        
        $companyTool->delete();
        
        return redirect()->route('admin.company_tools.index')
            ->with('success', 'Company tool deleted successfully.');
    }
}
