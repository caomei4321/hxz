<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class CommonProcess extends Model
{
    protected $table = 'user_has_common_tasks';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commonTask()
    {
        return $this->belongsTo(CommonTask::class, 'common_id');
    }
}
