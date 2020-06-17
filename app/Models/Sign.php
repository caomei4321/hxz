<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sign extends Model
{
    protected $fillable = [
        'user_id', 'type', 'sign_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
