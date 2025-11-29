<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyStore extends Model
{
    protected $table = 'company_store';
    
    protected $fillable = [
        'company_tool_id',
        'quantity',
        'unit_price',
        'purchase_date',
        'purchased_by',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'unit_price' => 'decimal:2',
    ];

    public function companyTool()
    {
        return $this->belongsTo(CompanyTool::class);
    }

    public function purchasedBy()
    {
        return $this->belongsTo(User::class, 'purchased_by');
    }
}
