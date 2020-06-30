<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manual extends Model
{
    protected $fillable = [
        'id', 'title', 'detail', 'sort'
    ];
}
