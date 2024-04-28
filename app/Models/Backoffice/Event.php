<?php

namespace App\Models\Backoffice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $table = 'events';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $guarded = [];

    public function attendance(){
        return $this->hasMany('App\Models\Backoffice\Attendance', 'event_id','id');
    }

    public function certCat(){
        return $this->belongsTo('App\Models\Backoffice\CertificateCategory', 'category_id','id');
    }
}
