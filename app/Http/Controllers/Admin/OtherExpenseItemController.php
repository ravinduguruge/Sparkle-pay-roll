<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OtherExpenseItem;
use Illuminate\Http\Request;

class OtherExpenseItemController extends Controller
{
    public function index()
    {
        $items = OtherExpenseItem::orderBy('name')->get();
        return view('admin.settings.other-expense-items', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:other_expense_items,name',
        ]);

        OtherExpenseItem::create([
            'name' => $request->name,
            'is_active' => true,
        ]);

        return redirect()->route('admin.other_expense_items.index')
            ->with('success', 'Expense item added successfully.');
    }

    public function update(Request $request, OtherExpenseItem $otherExpenseItem)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:other_expense_items,name,' . $otherExpenseItem->id,
        ]);

        $otherExpenseItem->update([
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.other_expense_items.index')
            ->with('success', 'Expense item updated successfully.');
    }

    public function destroy(OtherExpenseItem $otherExpenseItem)
    {
        $otherExpenseItem->delete();
        
        return redirect()->route('admin.other_expense_items.index')
            ->with('success', 'Expense item deleted successfully.');
    }
}
