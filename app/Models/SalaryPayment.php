<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalaryPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'salary_month_id', 'paid_date',
        'description', 'salary_amount', 'allowance_amount',
    ];

    public function salaryMonth()
    {
        return $this->belongsTo(SalaryMonth::class);
    }
}

