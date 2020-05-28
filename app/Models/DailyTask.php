<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyTask extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'content', 'status'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_has_daily_tasks', 'daily_id', 'user_id');
    }

    public function dailyProcess()
    {
        return $this->hasMany(DailyProcess::class, 'daily_id', 'id');
    }
}
