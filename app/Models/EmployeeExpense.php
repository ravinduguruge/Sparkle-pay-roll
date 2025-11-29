<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'type', 'expense_date', 'amount', 
        'description', 'year', 'month'
    ];

    protected $casts = [
        'expense_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
