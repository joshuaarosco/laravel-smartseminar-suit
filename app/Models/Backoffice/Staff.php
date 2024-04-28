<?php

namespace App\Models\Backoffice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use SoftDeletes;

    protected $table = 'staffs';
    
    protected $guarded = [];

    public function getAvatar(){
        if($this->filename!='' AND $this->directory!=''){
            return $this->directory.'/'.$this->filename;
        }
        return 'vet-clinic/images/face0.jpg';
    }

    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id','id');
    }

}
