<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkExpense extends Model
{
    use HasFactory;

    protected $fillable = ['work_entry_id', 'description', 'amount'];

    public function workEntry()
    {
        return $this->belongsTo(WorkEntry::class);
    }
}

