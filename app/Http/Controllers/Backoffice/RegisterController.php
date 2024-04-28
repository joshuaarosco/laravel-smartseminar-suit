<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Requests\Backoffice\RegisterRequest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Logic\GeneralLogic as Logic;
use App\Models\User;
use Auth;

//Events
use App\Events\SendEmailEvent;

//Services & Repositories
use App\Domain\Interfaces\Services\Backoffice\ICRUDService;
use App\Domain\Interfaces\Repositories\Backoffice\IParticipantsRepository;

class RegisterController extends Controller
{
    //do some magic
    public function __construct(Logic $logic, IParticipantsRepository $repo, ICRUDService $CRUDservice) {
        $this->repo = $repo;
        $this->logic = $logic;
        $this->CRUDservice = $CRUDservice;
		$this->middleware('backoffice.guest', ['except' => "logout"]);
	}

	public function register() {
		return view('backoffice.auth.register');
	}

	public function authenticate(RegisterRequest $request) {
        $crudData = $this->CRUDservice->save($request, $this->repo);
        Auth::loginUsingId($crudData->id);
        // if($crudData){
        //     event(new SendEmailEvent($crudData,'account_verification'));
        // }

        // session()->flash('notification-status', "info");
        // session()->flash('notification-msg', 'Account Verification has been sent to your email. Please click the link to verify your account.');

        return redirect()->route('backoffice.auth.login');
	}

    public function verify($username){
        $crudData = User::where('username', $username)->first();
        $crudData->email_verified_at = date('Y-m-d h:i:s');
        $crudData->save();
        Auth::loginUsingId($crudData->id);
        return redirect()->route('backoffice.auth.login');
    }
}
