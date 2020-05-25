<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected  $fillable = [
        'title', 'content'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_has_messages','message_id','user_id')->withPivot('status');
    }
}
