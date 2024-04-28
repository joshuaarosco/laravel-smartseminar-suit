<?php

namespace App\Http\Controllers\Backoffice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//Events
use App\Events\SendEmailEvent;

//Services & Repositories
use App\Domain\Interfaces\Services\Backoffice\ICRUDService;
use App\Domain\Interfaces\Repositories\Backoffice\IEventsRepository;

//Request Validator

//Global Classes
use Input;

class AttendanceController extends Controller
{
    //Do some magic
    public function __construct(ICRUDService $CRUDservice, IEventsRepository $eventRepo){
        $this->data = [];
        $this->eventRepo = $eventRepo;
        $this->CRUDservice = $CRUDservice;
        $this->data['title'] = 'Attendance';
        $this->data['attendance'] = null;
    }

    public function index(){
        $this->data['events'] =  $this->eventRepo->fetch();
        return view('backoffice.pages.attendance.index',$this->data);
    }

    public function participants($id){
        $this->data['event'] = $this->eventRepo->findOrFail($id);
        if(!$this->data['event']){
            return abort(404);
        }
        $this->data['participants'] = $this->data['event']->attendance;
        return view('backoffice.pages.attendance.participants',$this->data);
    }
}
