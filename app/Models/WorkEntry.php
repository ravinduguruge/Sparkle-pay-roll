<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class WorkEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'project_id', 'work_date',
        'travel_start_time', 'site_on_time', 'site_out_time', 'travel_end_time',
        'work_partners', 'total_hours',
        'description', 'partners', 'status',
    ];

    protected $dates = ['work_date', 'travel_start_time', 'site_on_time', 'site_out_time', 'travel_end_time'];

    protected $casts = [
        'work_partners' => 'array',
    ];

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

