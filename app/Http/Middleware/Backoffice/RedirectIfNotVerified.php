<?php

namespace App\Http\Middleware\Backoffice;

use Illuminate\Contracts\Auth\Guard;
use App\Logic\GeneralLogic;
use Closure;

class RedirectIfNotVerified
{

    /**
    * The Guard implementation.
    *
    * @var Guard
    */
    protected $auth;

    /**
    * Create a new filter instance.
    *
    * @param  Guard  $auth
    * @return void
    */
    public function __construct(Guard $auth,GeneralLogic $logic)
    {
        $this->auth = $auth;
        $this->logic = $logic;
    }

    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */
    public function handle($request, Closure $next)
    {
        if ($this->auth->user()->email_verified_at == null) {
            $this->auth->logout();
            session()->flash('notification-status', "info");
            session()->flash('notification-msg', 'Account Verification has been sent to your email. Please verify your account first.');
            return redirect()->route('backoffice.auth.login');
        }

        return $next($request);
    }

}
