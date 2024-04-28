<?php

namespace App\Repositories\Backoffice;
use App\Domain\Interfaces\Repositories\Backoffice\IFeedbacksRepository;

use App\Services\ImageUploader as UploadLogic;
use App\Models\Backoffice\Feedback as Model;
use App\Models\User;
use DB, Str, Input;

class FeedbacksRepository extends Model implements IFeedbacksRepository
{

    public function fetch(){
        if(Input::has('event_id') AND Input::get('event_id') != null){
            return self::where('event_id', Input::get('event_id'))->orderBy('created_at','DESC')->get();
        }
        if(Input::has('search') AND Input::get('search') != null){
            $exploded = explode(" ",str_replace(' & ',' ',Input::get('search')));
            $query = self::query();
            foreach ($exploded as $key => $value) {
                $query->where('comment', 'like', "%{$value}%");
            }
            return $query->get();
        }
        return self::all();
    }

    public function saveData($request){
        DB::beginTransaction();
        try {
            $feedback = new self;
            $feedback->event_id = $request->event_id;
            $feedback->user_id = auth()->user()->id;
            $feedback->comment = $request->comment;

            $feedback->save();

            DB::commit();

            return $feedback;
        } catch (\Exception $e) {
             DB::rollback();
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

    public function fetchFeedbacks($id){
        return self::where('event_id', $id)->orderBy('created_at','DESC')->paginate(10);
    }
}
