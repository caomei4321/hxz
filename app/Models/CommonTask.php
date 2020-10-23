<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommonTask extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'content', 'category', 'status','created_at','update_at'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_has_common_tasks', 'common_id', 'user_id')
                    ->withPivot('id','address', 'description', 'photo', 'up_at')
                    ->withTimestamps();
    }
}
