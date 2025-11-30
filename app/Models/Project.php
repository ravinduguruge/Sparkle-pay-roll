<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'key_employee_id', 'total_budget',
        'advance_payment', 'key_employee_amount', 'remaining_budget', 
        'amount_spent', 'amount_in_hand', 'status',
    ];

    public function keyEmployee()
    {
        return $this->belongsTo(User::class, 'key_employee_id');
    }

    public function workEntries()
    {
        return $this->hasMany(WorkEntry::class);
    }
}
