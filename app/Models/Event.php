<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'user_id', 'category', 'status', 'address', 'description', 'img'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(EventReply::class);
    }

    public function adminReplies()
    {
        return $this->hasMany(EventAdminReply::class);
    }
}
