<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyStore;
use App\Models\CompanyTool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompanyStoreController extends Controller
{
    public function index()
    {
        $storeItems = CompanyStore::with(['companyTool', 'purchasedBy'])
            ->orderBy('purchase_date', 'desc')
            ->get();
        
        // Get aggregated inventory with current stock
        $inventory = CompanyStore::select('company_tool_id', 
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('AVG(unit_price) as avg_price'))
            ->groupBy('company_tool_id')
            ->with('companyTool')
            ->get();
        
        $companyTools = CompanyTool::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.store.index', compact('storeItems', 'inventory', 'companyTools'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_tool_id' => 'required|exists:company_tools,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        CompanyStore::create([
            'company_tool_id' => $request->company_tool_id,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'purchase_date' => $request->purchase_date,
            'purchased_by' => Auth::id(),
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.company_store.index')
            ->with('success', 'Item added to company store successfully.');
    }

    public function destroy(CompanyStore $companyStore)
    {
        $companyStore->delete();
        
        return redirect()->route('admin.company_store.index')
            ->with('success', 'Store entry deleted successfully.');
    }
}
