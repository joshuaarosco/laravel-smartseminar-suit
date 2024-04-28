<?php

namespace App\Listeners;

use App\Mail\StaffCreation;
use App\Mail\AppointmentDetails;
use App\Mail\PatientCreation;
use App\Mail\TrackerNotification;
use App\Mail\AccountVerification;
use App\Mail\ForgotPassword;
use App\Events\SendEmailEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(SendEmailEvent $event)
    {
        switch ($event->type) {

            case 'staff_creation':
                    \Mail::to($event->data->email)->send(
                        new StaffCreation($event->data)
                    );
    
                    break;

            case 'account_verification':
                \Mail::to($event->data->email)->send(
                        new AccountVerification($event->data)
                    );
    
                break;

        
            case 'forgot_password':
                \Mail::to($event->data->email)->send(
                        new ForgotPassword($event->data)
                    );
    
                break;
            
            default:
                // code...
                break;
        }
    }
}
