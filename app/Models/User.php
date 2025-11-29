<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'normal_hour_rate',
        'ot_hour_rate',
        'can_manage_work',
        'can_add_purchases',
    ];



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'can_manage_work' => 'boolean',
            'can_add_purchases' => 'boolean',
        ];
    }

    public function workEntries()
    {
        return $this->hasMany(WorkEntry::class);
    }

    public function salaryMonths()
    {
        return $this->hasMany(SalaryMonth::class);
    }

    public function projectsAsKey()
    {
        return $this->hasMany(Project::class, 'key_employee_id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    
    
}
