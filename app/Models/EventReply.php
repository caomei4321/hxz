<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class EventReply extends Model
{
    protected $fillable = [
        'event_id', 'reply', 'img'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}