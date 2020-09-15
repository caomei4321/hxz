<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $fillable = [
        'name'
    ];

    public function appointmentRecord()
    {
        return $this->hasMany(AppointmentRecord::class, 'station_id');
    }
}
