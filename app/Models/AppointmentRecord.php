<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentRecord extends Model
{
    protected $fillable = [
        'user_id', 'station_id', 'status', 'appointment_time'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function station()
    {
        return $this->belongsTo(Station::class);
    }
}
