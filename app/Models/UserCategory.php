<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCategory extends Model
{
//    use SoftDeletes;

    protected $fillable = [
        'name'
    ];

    //public $timestamps = false;

    public function users()
    {
        return $this->hasMany(User::class, 'category_id');
    }
}
