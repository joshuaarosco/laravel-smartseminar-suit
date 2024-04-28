<?php

namespace App\Repositories\Backoffice;
use App\Domain\Interfaces\Repositories\Backoffice\IAccountRepository;

use App\Logic\ImageUploader as UploadLogic;
use App\Models\User as Model;
use App\Models\Backoffice\Alumni;
use DB, Str;

class AccountRepository extends Model implements IAccountRepository
{

    public function fetch($id){
        return $this->find($id);
    }
}
