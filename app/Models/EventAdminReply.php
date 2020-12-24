<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventAdminReply extends Model
{
    protected $fillable = [
        'event_id', 'event_reply_id', 'reply',
    ];

    public function eventReplies()
    {
        return $this->belongsTo(EventReply::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
