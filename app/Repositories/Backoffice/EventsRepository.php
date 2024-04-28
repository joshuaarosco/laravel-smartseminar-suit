<?php

namespace App\Repositories\Backoffice;
use App\Domain\Interfaces\Repositories\Backoffice\IEventsRepository;

use App\Services\ImageUploader as UploadLogic;
use App\Models\Backoffice\Event as Model;
use App\Models\User;
use App\Models\Backoffice\Attendance;
use DB, Str, Carbon, Input;

class EventsRepository extends Model implements IEventsRepository
{

    public function fetch(){
        if(Input::has('search') AND Input::get('search') != null){
            $exploded = explode(" ",str_replace(' & ',' ',Input::get('search')));
            $query = self::query();
            foreach ($exploded as $key => $value) {
                $query->where('name', 'like', "%{$value}%")
                ->orWhere('details', 'like', "%{$value}%");
            }
            return $query->get();
        }
        return self::orderBy('start','DESC')->get();
    }

    public function fetchOnGoing(){
        return self::where('status','!=','Completed')->orderBy('start','ASC')->get();
    }

    public function fetchCompleted(){
        $attendance = Attendance::where('user_id', auth()->user()->id)->pluck('event_id');
        return self::where('status','Completed')->whereIn('id', $attendance)->orderBy('start','DESC')->get();
    }

    public function fetchAttended($userId){
        $attendance = Attendance::where('user_id', $userId)->pluck('event_id');
        return self::whereIn('id', $attendance)->orderBy('start', 'DESC')->get();
    }

    public function count(){
        return self::where('patient_id',auth()->user()->patient->id)->whereIn('status', ['Pending', 'Scheduled'])->count();
    }

    public function scheduledEvents(){
        return self::where('status', '!=','Pending')->get();
    }

    public function saveData($request){
        DB::beginTransaction();
        try {
            $event = self::find($request->id)? : new self;

            $event->name = $request->name;
            $event->category_id = $request->category_id;
            $event->details = $request->details;
            $event->status = $request->status?$request->status:'Pending';
            $event->start = $request->start?date('Y-m-d H:i:s',strtotime($request->start)):null;
            $event->end = $request->end?date('Y-m-d H:i:s',strtotime($request->end)):null;
            
            $event->save();

            DB::commit();
            
            session()->flash('notification-status', "primary");
            session()->flash('notification-msg', __('msg.save_success'));
            return $event;
            
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('notification-status', "danger");
            session()->flash('notification-msg', __('msg.error'));
            return false;
        }
    }

    public function findOrFail($id){
        $data = self::find($id);
        if(!$data){
            return false;
        }
        return $data;
    }

    public function deleteData($id){
        DB::beginTransaction();
        try {
            self::destroy($id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function attend($id){
        $attendance = Attendance::where('event_id', $id)->where('user_id', auth()->user()->id)->first()? : new Attendance;
        $attendance->user_id = auth()->user()->id;
        $attendance->event_id = $id;
        $attendance->save();
    }

    public function updateStatus($id, $status){
        $event = self::where('id', $id)->first();
        $event->status = $status;
        $event->save();
    }
}
