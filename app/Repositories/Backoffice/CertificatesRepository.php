<?php

namespace App\Repositories\Backoffice;
use App\Domain\Interfaces\Repositories\Backoffice\ICertificatesRepository;

use App\Services\ImageUploader as UploadLogic;
use App\Models\Backoffice\Certificate as Model;
use App\Models\Backoffice\AvailedService;
use App\Models\User;
use DB, Str;

class CertificatesRepository extends Model implements ICertificatesRepository
{

    public function fetch($id){
        return self::where('event_id', $id)->get();
    }

    public function generateCertificate($event, $quote){
        DB::beginTransaction();
        try {
            $attandanceList = $event->attendance;
            $certificates = [];
            foreach($attandanceList as $index => $attendance){
                $certificate_id = $event->id.$attendance->user_id.date('mdy').$index;
                $data = new self;
                $data->event_id = $event->id;
                $data->user_id = $attendance->user_id;
                $data->certificate_id = $certificate_id;
                $data->user_name = $attendance->user->name;
                $data->event_name = $event->name;
                $data->quote = $quote;
                $data->date = $event->start?date('Y-m-d',strtotime($event->start)):null;
                $data->category = $event->certCat->name;
                $data->directory = $event->certCat->directory;
                $data->filename = $event->certCat->filename;
                $data->save();
                array_push($certificates, $data);
            }

            DB::commit();
            
            return $certificates;
            
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function getCertificate($id){
        return self::where('certificate_id', $id)->first();
    }
}
