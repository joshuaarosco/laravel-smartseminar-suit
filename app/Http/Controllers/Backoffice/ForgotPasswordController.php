<?php

namespace App\Http\Controllers\Backoffice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Logic\GeneralLogic as Logic;
use App\Models\User;

use App\Http\Requests\Backoffice\NewPasswordRequest;

//Events
use App\Events\SendEmailEvent;

use Auth, Str;

class ForgotPasswordController extends Controller
{
    //do some magic
    public function __construct(Logic $logic, User $user) {
        $this->user = $user;
        $this->logic = $logic;
		$this->middleware('backoffice.guest', ['except' => "logout"]);
	}

	public function forgotPass(){
		return view('backoffice.auth.forgotpass');
	}

    public function resetLink(Request $request){
        $checkUser = User::where('email', $request->email)->first();
        if($checkUser){
            $token = Str::random(8);
            $checkUser->remember_token = $token;
            $checkUser->save();

            event(new SendEmailEvent($checkUser,'forgot_password'));

            session()->flash('notification-status','success');
            session()->flash('notification-msg',"The password reset link has been sent, please check your email.");
            return redirect()->back();
        }
        session()->flash('notification-status','warning');
        session()->flash('notification-msg',"Email address doesn't exist.");
        return redirect()->back();
    }

    public function resetPass($token){
        $checkUser = User::where('remember_token', $token)->first();
        if($checkUser){
            $this->data['user'] = $checkUser; 
            return view('backoffice.auth.resetpass', $this->data);
        }
        session()->flash('notification-status','warning');
        session()->flash('notification-msg',"Password reset link has been expired.");
        return redirect()->route('backoffice.auth.forgotPass');
    }

    public function updatePass(NewPasswordRequest $request){
        $checkUser = User::where('remember_token', $request->token)->first();
        if($checkUser){
            $checkUser->remember_token = null;
            $checkUser->password = bcrypt($request->new_password);
            $checkUser->save();

            session()->flash('notification-status','success');
            session()->flash('notification-msg',"Password has been successfully updated, please login.");
            return redirect()->route('backoffice.auth.login');
        }
        session()->flash('notification-status','warning');
        session()->flash('notification-msg',"Password reset link has been expired.");
        return redirect()->route('backoffice.auth.forgotPass');
    }
}