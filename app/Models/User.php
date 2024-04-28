<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'name', 'email', 'password',
    // ];
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function participant(){
        return $this->hasOne('App\Models\Backoffice\Participant', 'user_id','id');
    }

    public function staff(){
        return $this->hasOne('App\Models\Backoffice\Staff', 'user_id','id');
    }

    public function certificate(){
        return $this->hasMany('App\Models\Backoffice\Certificate', 'user_id','id');
    }

    public function getAvatar(){
        if(auth()->user()->type == 'participant')
            return $this->participant->getAvatar();
        return 'vet-clinic/images/face0.jpg';
    }

    public function myCertificate($eventId){
        return $this->certificate->where('event_id', $eventId)->first();
    }
}
