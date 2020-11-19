<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    protected $guard_name = 'admin';

    protected $fillable = [
        'name', 'phone', 'password', 'department_id'
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
