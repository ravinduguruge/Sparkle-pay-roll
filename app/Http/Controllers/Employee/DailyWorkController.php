<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\WorkEntry;
use App\Models\WorkExpense;
use App\Models\Vehicle;
use App\Models\OtherExpenseItem;
use App\Models\CompanyTool;
use App\Models\CompanyStore;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DailyWorkController extends Controller
{
    public function submitDailyWork(Request $request)
    {
        $user = Auth::user();

        // Check permissions
        if (!$user->can_manage_work && $user->role !== 'admin') {
            return redirect()->route('employee.dashboard')
                ->withErrors('You do not have permission to submit daily work.');
        }

        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'work_date' => 'required|date',
            'travel_start_time' => 'required|date',
            'site_on_time' => 'required|date',
            'site_out_time' => 'required|date',
            'travel_end_time' => 'required|date',
            'description' => 'required|string',
            'work_partners' => 'nullable|array',
            'work_partners.*' => 'exists:users,id',
        ]);

        DB::beginTransaction();

        try {
            // Calculate total hours (site_on_time to site_out_time)
            $siteOn = Carbon::parse($request->site_on_time);
            $siteOut = Carbon::parse($request->site_out_time);
            $totalHours = abs($siteOn->diffInMinutes($siteOut)) / 60;

            // Create work entry
            $workEntry = WorkEntry::create([
                'user_id' => $user->id,
                'project_id' => $request->project_id,
                'work_date' => $request->work_date,
                'travel_start_time' => $request->travel_start_time,
                'site_on_time' => $request->site_on_time,
                'site_out_time' => $request->site_out_time,
                'travel_end_time' => $request->travel_end_time,
                'work_partners' => json_encode($request->work_partners ?? []),
                'total_hours' => round($totalHours, 2),
                'description' => $request->description . "\n\n" . ($request->summary_notes ?? ''),
                'status' => 'pending',
            ]);

            // Process Refreshments
            $refreshmentTypes = $request->refreshment_type ?? [];
            $refreshmentAmounts = $request->refreshment_amount ?? [];

            foreach ($refreshmentTypes as $index => $type) {
                if (!empty($type) && !empty($refreshmentAmounts[$index]) && $refreshmentAmounts[$index] > 0) {
                    WorkExpense::create([
                        'work_entry_id' => $workEntry->id,
                        'expense_type' => 'refreshment',
                        'item_name' => $type,
                        'amount' => $refreshmentAmounts[$index],
                    ]);
                }
            }

            // Process Vehicles
            $vehicleIds = $request->vehicle_id ?? [];
            $vehicleDistances = $request->vehicle_distance ?? [];
            $busAmounts = $request->bus_amount ?? [];

            foreach ($vehicleIds as $index => $vehicleId) {
                if (!empty($vehicleId)) {
                    $vehicle = Vehicle::find($vehicleId);
                    if ($vehicle) {
                        $amount = 0;

                        if ($vehicle->type === 'bus') {
                            // Bus - use direct ticket amount
                            $amount = $busAmounts[$index] ?? 0;
                        } elseif ($vehicle->type === 'company') {
                            // Company vehicle - calculate from distance
                            $distance = $vehicleDistances[$index] ?? 0;
                            $amount = $distance * $vehicle->fuel_rate_per_km;
                        }

                        if ($amount > 0) {
                            WorkExpense::create([
                                'work_entry_id' => $workEntry->id,
                                'expense_type' => 'vehicle',
                                'vehicle_id' => $vehicleId,
                                'distance_km' => $vehicleDistances[$index] ?? null,
                                'amount' => $amount,
                            ]);
                        }
                    }
                }
            }

            // Process Other Expenses
            $otherExpenseItemIds = $request->other_expense_item_id ?? [];
            $newOtherExpenseItems = $request->new_other_expense_item ?? [];
            $otherExpenseAmounts = $request->other_expense_amount ?? [];

            foreach ($otherExpenseItemIds as $index => $itemId) {
                if (!empty($itemId) && !empty($otherExpenseAmounts[$index]) && $otherExpenseAmounts[$index] > 0) {
                    if ($itemId === 'new') {
                        // Create new item
                        $newItemName = $newOtherExpenseItems[$index] ?? '';
                        if (!empty($newItemName)) {
                            $newItem = OtherExpenseItem::firstOrCreate(['name' => $newItemName]);
                            $itemId = $newItem->id;
                        } else {
                            continue;
                        }
                    }

                    WorkExpense::create([
                        'work_entry_id' => $workEntry->id,
                        'expense_type' => 'other',
                        'other_expense_item_id' => $itemId,
                        'amount' => $otherExpenseAmounts[$index],
                    ]);
                }
            }

            // Process Purchases (Company Tools) - Only if user has permission
            if ($user->can_add_purchases || $user->role === 'admin') {
                $companyToolIds = $request->company_tool_id ?? [];
                $newCompanyTools = $request->new_company_tool ?? [];
                $purchaseQuantities = $request->purchase_quantity ?? [];
                $purchaseUnitPrices = $request->purchase_unit_price ?? [];
                $purchaseAmounts = $request->purchase_amount ?? [];

                foreach ($companyToolIds as $index => $toolId) {
                    if (!empty($toolId)) {
                        $quantity = $purchaseQuantities[$index] ?? 1;
                        $unitPrice = $purchaseUnitPrices[$index] ?? 0;
                        $totalAmount = $purchaseAmounts[$index] ?? 0;
                        
                        if ($totalAmount <= 0) {
                            continue;
                        }
                        
                        if ($toolId === 'new') {
                            // Create new tool (not from store)
                            $newToolName = $newCompanyTools[$index] ?? '';
                            if (!empty($newToolName)) {
                                $newTool = CompanyTool::firstOrCreate(['name' => $newToolName]);
                                $toolId = $newTool->id;
                            } else {
                                continue;
                            }
                        } else {
                            // Tool from store - deduct from inventory
                            $storeItem = CompanyStore::where('company_tool_id', $toolId)
                                ->where('quantity', '>', 0)
                                ->orderBy('purchase_date', 'asc') // FIFO
                                ->first();
                            
                            if ($storeItem) {
                                $quantityToDeduct = min($quantity, $storeItem->quantity);
                                $storeItem->quantity -= $quantityToDeduct;
                                $storeItem->save();
                                
                                // If more quantity needed, deduct from next batch
                                $remainingQuantity = $quantity - $quantityToDeduct;
                                while ($remainingQuantity > 0) {
                                    $nextItem = CompanyStore::where('company_tool_id', $toolId)
                                        ->where('quantity', '>', 0)
                                        ->orderBy('purchase_date', 'asc')
                                        ->first();
                                    
                                    if (!$nextItem) {
                                        break; // No more stock
                                    }
                                    
                                    $deduct = min($remainingQuantity, $nextItem->quantity);
                                    $nextItem->quantity -= $deduct;
                                    $nextItem->save();
                                    $remainingQuantity -= $deduct;
                                }
                            }
                        }

                        WorkExpense::create([
                            'work_entry_id' => $workEntry->id,
                            'expense_type' => 'purchase',
                            'company_tool_id' => $toolId,
                            'quantity' => $quantity,
                            'amount' => $totalAmount,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('employee.dashboard')
                ->with('success', 'Daily work submitted successfully! Waiting for admin approval.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors('Error submitting work: ' . $e->getMessage());
        }
    }
}
