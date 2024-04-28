<?php

namespace App\Models\Backoffice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{
    use SoftDeletes;

    protected $table = 'feedbacks';
    
    protected $guarded = [];
    
    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id','id');
    }

    public function event(){
        return $this->belongsTo('App\Models\Backoffice\Event', 'event_id','id');
    }

}
