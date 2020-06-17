<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HandoverRecord extends Model
{
    protected $fillable = [
        'sender_user', 'recipient_user', 'content', 'status', 'read_time'
    ];

    public function sendUser()
    {
        return $this->belongsTo(User::class, 'sender_user', 'id');
    }

    public function recipientUser()
    {
        return $this->belongsTo(User::class, 'recipient_user', 'id');
    }
}
