<?php

namespace App\Models\Backoffice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certificate extends Model
{
    use SoftDeletes;

    protected $table = 'certificates';

    protected $guarded = [];

    public function event(){
        return $this->hasOne('App\Models\Backoffice\Event', 'category_id','id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id','id');
    }
}
