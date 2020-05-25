<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommonTask extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'content', 'category', 'status'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_has_common_tasks', 'common_id', 'user_id')
                    ->withPivot('address', 'description', 'photo', 'up_at')
                    ->withTimestamps();
    }
}
