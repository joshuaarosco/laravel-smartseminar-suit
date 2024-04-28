<?php

namespace App\Http\Controllers\Backoffice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use App\Domain\Interfaces\Repositories\Backoffice\IStaffsRepository;
use App\Domain\Interfaces\Repositories\Backoffice\IEventsRepository;
use App\Domain\Interfaces\Repositories\Backoffice\IFeedbacksRepository;
use App\Domain\Interfaces\Repositories\Backoffice\IParticipantsRepository;

use Input;

class DashboardController extends Controller
{
    public function __construct(IStaffsRepository $staffRepo,
                                IEventsRepository $eventRepo,
                                IFeedbacksRepository $feedbackRepo, 
                                IParticipantsRepository $participantRepo){
        $this->data = [];
        $this->staffRepo = $staffRepo;
        $this->eventRepo = $eventRepo;
        $this->feedbackRepo = $feedbackRepo;
        $this->participantRepo = $participantRepo;
    }
    
    public function index(){
        $this->data['events'] = $this->eventRepo->fetchOnGoing();
        $this->data['staffs'] = $this->staffRepo->fetch();

        $this->data['staffCount'] = $this->data['staffs']->count();
        $this->data['participantCount'] = $this->participantRepo->fetch()->count();
        $this->data['feedbackCount'] = $this->feedbackRepo->fetch()->count();
        $this->data['eventCount'] = $this->eventRepo->fetch()->count();
        $this->data['attendedCount'] = $this->eventRepo->fetchAttended(auth()->user()->id)->count();
    	return view('backoffice.pages.dashboard.index', $this->data);
    }

    public function downloadReport($start, $end){
        $availedServices = $this->recordRepo->getAvailedServices($start, $end);
        $fileName = 'EarningSummaryReport-'.$start.'-'.$end.'.csv';
        $services = $availedServices;

        $headers = array(
            "Content-type"        => "application/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Date', 'Patient Name', 'Services', 'Price');

        $callback = function() use($services, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $totalPayments = 0;

            foreach ($services as $service) {
                $row['Date']         = $service->date;
                $row['Patient Name'] = $service->record->patient->user->name;
                $row['Services']     = $service->service->name;
                $row['Price']        = $service->payment;
                $totalPayments+=$service->payment;

                fputcsv($file, array($row['Date'], $row['Patient Name'], $row['Services'], $row['Price']));
            }

            fputcsv($file, array('Total Payment', '', '', $totalPayments));

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    
    public function viewReport(){
        $start = Input::get('_start');
        $end = Input::get('_end');
        $availedServices = $this->recordRepo->getAvailedServices($start, $end);
        $totalPayments = 0;
        $services = [];
        foreach ($availedServices as $service) {
            $totalPayments += $service->payment;
            array_push($services,[  
                'date'         => $service->date,
                'patient_name' => $service->record->patient->user->name,
                'service'      => $service->service->name,
                'price'        => $service->payment,
            ]);
        }
        $data['datas'] = ['services' => $services, 'total_payments' => $totalPayments];
        return response()->json($data, 200); 
    }

    public function viewAppointment(){
        $start = Input::get('_start');
        $end = Input::get('_end');
        $appointments = $this->eventRepo->getAppointments($start, $end);
        $appts = [];
        foreach ($appointments as $appointment) {
            array_push($appts,[  
                'start'        => $appointment->start,
                'end'          => $appointment->end,
                'patient_name' => $appointment->patient->user->name,
                'service'      => $appointment->service->name,
                'status'       => $appointment->status,
            ]);
        }
        $data['datas'] = ['appointments' => $appts];
        return response()->json($data, 200); 
    }

    public function downloadAppointments($start, $end){
        $appointments = $this->eventRepo->getAppointments($start, $end);
        $fileName = 'AppointmentSummaryReport-'.$start.'-'.$end.'.csv';

        $headers = array(
            "Content-type"        => "application/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Patient Name', 'Service', 'Start', 'End', 'Status');

        $callback = function() use($appointments, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($appointments as $appointment) {
                
                $row['Patient Name'] = $appointment->patient->user->name;
                $row['Service']      = $appointment->service->name;
                $row['Start']        = $appointment->start;
                $row['End']          = $appointment->end;
                $row['Status']       = $appointment->status;

                fputcsv($file, array($row['Patient Name'], $row['Service'], $row['Start'], $row['End'], $row['Status']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
