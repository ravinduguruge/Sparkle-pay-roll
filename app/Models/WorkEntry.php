<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class WorkEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'project_id', 'work_date',
        'job_in_at', 'job_out_at',
        'job_description', 'partners', 'status',
    ];

    protected $dates = ['work_date', 'job_in_at', 'job_out_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function expenses()
    {
        return $this->hasMany(WorkExpense::class);
    }
}

