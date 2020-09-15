<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use SoftDeletes;

    protected $guard_name = 'api';

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'password', 'age', 'open_id', 'wx_session_key', 'category_id', 'department_id', 'integral', 'sign_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function category()
    {
        return $this->belongsTo(UserCategory::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function dailyTask()
    {
        return $this->belongsToMany(DailyTask::class,'user_has_daily_tasks','user_id','daily_id');
    }

    public function dailyProcess()
    {
        return $this->hasMany(DailyProcess::class);
    }

    public function commonTask()
    {
        return $this->belongsToMany(CommonTask::class,'user_has_common_tasks','user_id','common_id')
                    ->withPivot('id','address', 'description', 'photo', 'up_at')
                    ->withTimestamps();
    }

    public function message()
    {
        return $this->belongsToMany(Message::class,'user_has_messages','user_id','message_id')->withPivot('status');
    }

    public function event()
    {
        return $this->hasMany(Event::class);
    }

    public function sign()
    {
        return $this->hasMany(Sign::class);
    }

    public function handoverSender()
    {
        return $this->hasMany(HandoverRecord::class, 'sender_user', 'id');
    }

    public function handoverRecipient()
    {
        return $this->hasMany(HandoverRecord::class, 'recipient_user', 'id');
    }

    public function appointmentRecord()
    {
        return $this->hasMany(AppointmentRecord::class);
    }
}
