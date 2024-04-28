<?php

namespace App\Services\Backoffice;
use App\Domain\Interfaces\Services\Backoffice\ICRUDService;

class CRUDService implements ICRUDService
{
    public function __construct(){

    }

    public function save($request, $repo){
        $save = $repo->saveData($request);
        if($save){
            session()->flash('notification-status', "primary");
            session()->flash('notification-msg', __('msg.save_success'));
            return $save;
        }
        session()->flash('notification-status', "danger");
        session()->flash('notification-msg', __('msg.error'));
        return false;
    }

    public function delete($id, $repo){
        $delete = $repo->deleteData($id);
        if($delete){
            session()->flash('notification-status', "primary");
            session()->flash('notification-msg', __('msg.delete_success'));
            return redirect()->back();
        }
        session()->flash('notification-status', "danger");
        session()->flash('notification-msg', __('msg.error'));
        return redirect()->back();
    }
}
