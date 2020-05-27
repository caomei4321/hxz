<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class DailyProcess extends Model
{
    protected $fillable = [
        'user_id', 'daily_id', 'address', 'description', 'photo'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dailyTask()
    {
        return $this->belongsTo(DailyTask::class, 'daily_id');
    }
}
