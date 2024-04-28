<?php

namespace App\Http\Controllers\Backoffice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//Events
use App\Events\SendEmailEvent;

//Services & Repositories
use App\Domain\Interfaces\Services\Backoffice\ICRUDService;
use App\Domain\Interfaces\Repositories\Backoffice\IStaffsRepository;
use App\Domain\Interfaces\Repositories\Backoffice\IParticipantsRepository;

//Request Validator
use App\Http\Requests\Backoffice\AccountRequest;
use App\Http\Requests\Backoffice\PasswordRequest;

use Illuminate\Support\Facades\Hash;
use App\Models\User;

//Global Classes
use Input, Auth;

class AccountController extends Controller
{
    //Do some magic
    public function __construct(ICRUDService $CRUDservice, IStaffsRepository $staffRepo, IParticipantsRepository $participantRepo){
        $this->data = [];
        $this->CRUDservice = $CRUDservice;

        $this->staffRepo = $staffRepo;
        $this->participantRepo = $participantRepo;
        
        $this->data['title'] = 'Account';
        $this->data['account'] = null;
    }

    public function index(){
        if(!auth()->check())
            return abort(404);
        $account = null;
        // if(auth()->user()->type == 'participant'){
        //     $account = $this->participantRepo->findOrFail(auth()->user()->participant->id);
        // }
        // if(auth()->user()->type == 'staff'){
        //     $account = $this->staffRepo->findOrFail(auth()->user()->staff->id);
        // }

        $this->data['account'] = $account;
        return view('backoffice.pages.account.index',$this->data);
    }

    public function save(AccountRequest $request){
        if(auth()->user()->type == 'participant'){
            $this->CRUDservice->save($request, $this->participantRepo);
        }
        if(auth()->user()->type == 'staff'){
            $this->CRUDservice->save($request, $this->staffRepo);
        }
        return redirect()->back();
    }

    public function updatePassword(PasswordRequest $request){
        $user = auth()->user();
        if (Hash::check($request->old_password, $user->password)) { 
            $user->fill([
                'password' => Hash::make($request->new_password)
            ])->save();

            session()->flash('notification-status', "primary");
            session()->flash('notification-msg', "Password successfuly changed!");
         
            return redirect()->back();
         
        } else {
             
            session()->flash('notification-status', "danger");
            session()->flash('notification-msg', "Old password does not match!");

            return redirect()->back();
        }
    }

}
