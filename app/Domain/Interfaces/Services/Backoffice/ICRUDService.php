<?php

namespace App\Domain\Interfaces\Services\Backoffice;

interface ICRUDService
{

    public function save($request, $repo);

    public function delete($id, $repo);

}
