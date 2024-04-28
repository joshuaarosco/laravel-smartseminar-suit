<?php

namespace App\Http\Controllers\Backoffice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Domain\Interfaces\Repositories\Backoffice\IEventsRepository;
use App\Domain\Interfaces\Repositories\Backoffice\ICertificatesRepository;

use Input, App, PDF;

class CertificateController extends Controller
{
    public function __construct(IEventsRepository $eventRepo, ICertificatesRepository $certRepo){
        $this->data = [];
        $this->certRepo = $certRepo;
        $this->eventRepo = $eventRepo;
    }
    
    public function genCert($id){
        $event = $this->eventRepo->findOrFail($id);

        $data['title'] = 'Certificate of Completion for '.$event->name.' Participants';
        // $data['certCat'] = $event->certCat;
        $key = array_rand(__('quotes'));
        $data['quote'] = __('quotes')[$key];
        $data['certificates'] = [];
        $check = $this->certRepo->fetch($id);
        if($check->count() == 0){
            $data['certificates'] = $this->certRepo->generateCertificate($event, $data['quote']);
        }else{
            $data['certificates'] = $this->certRepo->fetch($id);
        }

        $pdf = PDF::loadView('pdf.certificate.all', compact('data'))->setPaper('A4', 'landscape')->stream();
        
        return $pdf;
    }

    public function view($id){
        $certificate = $this->certRepo->getCertificate($id);
        if(!$certificate){
            return abort(404);
        }
        $data['title'] = 'Certificate of Completion for '.$certificate->event_name.' Participants';

        $data['certificate'] = $certificate;

        $pdf = PDF::loadView('pdf.certificate.view', compact('data'))->setPaper('A4', 'landscape')->stream();
        
        return $pdf;
    }
}
