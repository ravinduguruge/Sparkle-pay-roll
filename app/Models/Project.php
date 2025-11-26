<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'key_employee_id', 'budget_amount',
        'remaining_budget', 'description',
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
