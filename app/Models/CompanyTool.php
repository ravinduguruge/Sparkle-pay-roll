<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyTool extends Model
{
    protected $fillable = [
        'name',
        'price',
        'is_active',
    ];

    public function workExpenses()
    {
        return $this->hasMany(WorkExpense::class);
    }
}
