<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_entry_id', 
        'expense_type', 
        'item_name', 
        'vehicle_id', 
        'distance_km', 
        'other_expense_item_id', 
        'company_tool_id',
        'quantity',
        'amount'
    ];

    public function workEntry()
    {
        return $this->belongsTo(WorkEntry::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function otherExpenseItem()
    {
        return $this->belongsTo(OtherExpenseItem::class);
    }

    public function companyTool()
    {
        return $this->belongsTo(CompanyTool::class);
    }
}

