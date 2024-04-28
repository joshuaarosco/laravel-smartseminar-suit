<?php

namespace App\Models\Backoffice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CertificateCategory extends Model
{
    use SoftDeletes;

    protected $table = 'certificate_categories';

    protected $guarded = [];

    public function getTemplate(){
        return $this->directory.'/'.$this->filename;
    }
}