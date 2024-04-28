<?php

namespace App\Http\Controllers\Backoffice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//Events
use App\Events\SendEmailEvent;

//Services & Repositories
use App\Domain\Interfaces\Services\Backoffice\ICRUDService;
use App\Domain\Interfaces\Repositories\Backoffice\IParticipantsRepository;
use App\Domain\Interfaces\Repositories\Backoffice\IEventsRepository;

//Request Validator
use App\Http\Requests\Backoffice\PatientsRequest;

//Global Classes
use Input;

class ParticipantsController extends Controller
{
    //Do some magic
    public function __construct(
        IParticipantsRepository $repo, 
        ICRUDService $CRUDservice, 
        IEventsRepository $eventRepo){
        $this->data = [];
        $this->repo = $repo;
        $this->CRUDservice = $CRUDservice;
        $this->eventRepo = $eventRepo;
        $this->data['title'] = 'Participants';
        $this->data['participant'] = null;
    }

    public function index(){
        $this->data['participants'] = $this->repo->fetch();
        return view('backoffice.pages.participants.index',$this->data);
    }

    public function create(){
        return view('backoffice.pages.participants.create',$this->data);
    }

    public function store(PatientsRequest $request){
        $crudData = $this->CRUDservice->save($request, $this->repo);
        if($crudData){
            event(new SendEmailEvent($crudData,'participant_creation'));
        }
        return redirect()->route('backoffice.participants.view',$crudData->participant->id);
    }

    public function view($id){
        $this->data['participant'] = $this->repo->findOrFail($id);
        if(!$this->data['participant'] OR (!in_array(auth()->user()->type, ['admin', 'super_user','staff']) AND $this->data['participant']->id != auth()->user()->participant->id)){
            session()->flash('notification-status', "danger");
            session()->flash('notification-msg', __('msg.not_found'));
            return abort(404);
        }
        $this->data['events'] = $this->eventRepo->fetchAttended($this->data['participant']->user->id);
        return view('backoffice.pages.participants.view', $this->data);
    }

    public function edit($id){
        $this->data['participant'] = $this->repo->findOrFail($id);
        if(!$this->data['participant'] OR (!in_array(auth()->user()->type, ['admin', 'super_user']) AND $this->data['participant']->id != auth()->user()->participant->id)){
            session()->flash('notification-status', "danger");
            session()->flash('notification-msg', __('msg.not_found'));
            return redirect()->route('backoffice.participants.index');
        }
        return view('backoffice.pages.participants.edit', $this->data);
    }

    public function update($id, PatientsRequest $request){
        $crudData = $this->CRUDservice->save($request, $this->repo);
        return redirect()->route('backoffice.participants.view',$crudData->participant->id);
    }
}
