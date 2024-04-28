<?php

namespace App\Http\Controllers\Backoffice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//Events
use App\Events\SendEmailEvent;

//Services & Repositories
use App\Domain\Interfaces\Services\Backoffice\ICRUDService;
use App\Domain\Interfaces\Repositories\Backoffice\IEventsRepository;
use App\Domain\Interfaces\Repositories\Backoffice\IFeedbacksRepository;

//Request Validator
use App\Http\Requests\Backoffice\EventRequest;

//Global Classes
use Input, DB;

class EventsController extends Controller
{
    //Do some magic
    public function __construct(IEventsRepository $repo, ICRUDService $CRUDservice, IFeedbacksRepository $feedbackRepo){
        $this->data = [];
        $this->repo = $repo;
        $this->feedbackRepo = $feedbackRepo;
        $this->CRUDservice = $CRUDservice;
        $this->data['title'] = 'Events';
        $this->data['event'] = null;
        $this->data['statuses'] = [
            'Pending',
            'Happening',
            'Completed',
        ];
        $this->data['categories'] = [
            1 => "Education Seminars and training workshop",
            2 => "On the Job Training",
            3 => "Healthcare Seminars & training",
            4 => "The Pre-Internship Seminar and Pinning ceremony",
            5 => "Government Seminars and Training program",
            6 => "Turning Point Youth Camp",
            7 => "Online webinar Training",
            8 => "Entrepreneurship and Start up basics",
            9 => "Capability building Seminar: Empowering Youth Leadership",
            10 => "Arts Seminar and Training",
            11 => "International Webinar on The role of women leaders in the new normal",
            12 => "Stress Management and Mental Health Awareness",
            13 => "Seminar workshop in Empowering coaches : Division - Wide Sports Training for all",
            14 => "Career opportunities in (specific field)",
            15 => "National leadership training workshop seminar",
            16 => "Product knowledge seminar",
            17 => "Fire & safety and gun safety seminar",
            18 => "Business orientation seminar and training program",
            19 => "Fire drill seminar and training program",
            20 => "Karate sports and Arnis training seminar"
        ];
    }

    public function index(){
        $this->data['events'] = $this->repo->fetchOnGoing();
        $this->data['calendar'] = [];
        $events = $this->repo->fetch()->where('start','!=',null)->where('end','!=',null);
        foreach($events as $index => $event){
            array_push($this->data['calendar'],[ 
                'title' => 'Event: '.$event->name, 
                'description' => 'Details : '.$event->details, 
                'start' => date('Y-m-d',strtotime($event->start)).'T'.date('H:i:s',strtotime($event->start)), 
                'end' =>  date('Y-m-d',strtotime($event->end)).'T'.date('H:i:s',strtotime($event->end))]);
        }
        $this->data['calendar'] = json_encode($this->data['calendar']);
        return view('backoffice.pages.events.index',$this->data);
    }

    public function create(){
        if(auth()->user()->type == 'participant'){
            return abort(404);
        }
        return view('backoffice.pages.events.create',$this->data);
    }

    public function store(EventRequest $request){
        $crudData = $this->repo->saveData($request);
        if(!$crudData){
            return redirect()->back();
        }
        return redirect()->route('backoffice.events.index');
    }
    
    public function edit($id){
        $this->data['event'] = $this->repo->findOrFail($id);
        if(!$this->data['event']){
            return abort(404);
        }
        return view('backoffice.pages.events.create',$this->data);
    }

    public function update(EventRequest $request){
        $crudData = $this->CRUDservice->save($request, $this->repo);
        return redirect()->route('backoffice.events.index');
    }

    public function cancel($id){
        $cancel = $this->CRUDservice->delete($id, $this->repo);
        return redirect()->route('backoffice.events.index');
    }

    public function view($id){
        $this->data['feedbacks'] = $this->feedbackRepo->fetchFeedbacks($id);
        $this->data['event'] = $this->repo->findOrFail($id);
        if(!$this->data['event']){
            return abort(404);
        }
        $attendance = $this->data['event']->attendance->pluck('user_id')->toArray();
        $this->data['attendance'] = false;
        if(in_array(auth()->user()->id,$attendance)){
            $this->data['attendance'] = true;
        }
        return view('backoffice.pages.events.view',$this->data);
    }

    public function attend($id){
        $this->data['event'] = $this->repo->attend($id);
        return redirect()->route('backoffice.events.index');
    }

    public function updateStatus($id, $status){
        $this->data['event'] = $this->repo->updateStatus($id, $status);
        return redirect()->back();
    }

    public function list(){
        $this->data['events'] = $this->repo->fetch();
        return view('backoffice.pages.events.list',$this->data);
    }

    public function completed(){
        $this->data['events'] = $this->repo->fetchCompleted();
        return view('backoffice.pages.events.list',$this->data);
    }
}
