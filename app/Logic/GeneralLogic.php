<?php

namespace App\Logic;
use Illuminate\Http\Request;

class GeneralLogic
{
    //Do some magic
    public function loginLogic(Request $request, $redirect_uri = NULL){
        $username = $request->get('username');
        $password = $request->get('password');
        $remember = $request->get('remember_me');

        $field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if (auth()->attempt([$field => $username, 'password' => $password], $remember)) {
            session()->flash('notification-status', "success");
            session()->flash('notification-msg', "Welcome back!");

            if($redirect_uri AND session()->has($redirect_uri)){
                return redirect( session()->get($redirect_uri) );
            }

            return redirect()->back();
        }

        session()->flash('notification-status', "warning");
        session()->flash('notification-msg', "Invalid username or password.");

        return redirect()->back();
    }

    public function logoutLogic(){
        auth()->logout();
        return redirect()->back();
    }

    public function redirectUser($type){
        session()->flash('notification-status','warning');
        session()->flash('notification-msg',"You don't have enough access.");
        switch ($type) {
            case 'super_user':
                return redirect()->route('backoffice.index');
            break;

            case 'patient':
                return redirect()->route('backoffice.index');
            break;

            case 'admin':
                return redirect()->route('backoffice.index');
            break;

            default:
                return redirect('/');
            break;
        }
    }

    public function numberFormat($number){
        
    }
}
