<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalaryMonth extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'year', 'month',
        'monthly_salary', 'paid_amount',
        'allowance_total', 'remaining_amount',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(SalaryPayment::class);
    }

    public function recalcRemaining(): void
    {
        $this->remaining_amount = $this->monthly_salary
            - $this->paid_amount
            - $this->allowance_total;
        $this->save();
    }
}

