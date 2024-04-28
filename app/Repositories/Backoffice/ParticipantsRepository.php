<?php

namespace App\Repositories\Backoffice;
use App\Domain\Interfaces\Repositories\Backoffice\IParticipantsRepository;

use App\Logic\ImageUploader as UploadLogic;
use App\Models\Backoffice\Participant as Model;
use App\Models\User;
use DB, Str, Input;

class ParticipantsRepository extends Model implements IParticipantsRepository
{

    public function fetch(){
        if(Input::has('search') AND Input::get('search') != null){
            $exploded = explode(" ",str_replace(' & ',' ',Input::get('search')));
            $query = self::query();
            foreach ($exploded as $key => $value) {
                $query->where('fname', 'like', "%{$value}%")
                ->orWhere('lname', 'like', "%{$value}%")
                ->orWhere('address', 'like', "%{$value}%");
            }
            return $query->get();
        }
        return self::all();
    }

    public function newPatients(){
        return self::whereDay('created_at', now()->day)->get();
    }

    public function saveData($request){
        DB::beginTransaction();
        try {
            $user = User::find($request->user_id);
            $participant = $user? $this->where('user_id', $user->id)->first() : null;
            
            $password = Str::random(8);

            if($request->has('password')){
                $password = $request->password;
            }
            
            //set and save password if theres no user fetch
            if(!$user){
                $user = new User;
                $user->password = bcrypt($password);
            }

            $user->name = $request->fname.' '.$request->lname;
            $user->username = $request->email;
            $user->type = 'participant';
            $user->email = $request->email;
            $user->contact_number = $request->contact_number;

            $user->save();

            if(!$participant){
                $participant = new self;
                $participant->user_id = $user->id;
            }

            $participant->fname = $request->fname;
            // $participant->mname = $request->mname;
            $participant->lname = $request->lname;
            $participant->age = $request->age;
            // $participant->birthdate = $request->birthdate;
            $participant->gender = $request->gender?$request->gender:$participant->gender;
            $participant->address = $request->address;
            
            if($request->hasFile('file')){
                $upload = UploadLogic::upload($request->file,'storage/participant');
                $participant->path = $upload["path"];
                $participant->directory = $upload["directory"];
                $participant->filename = $upload["filename"];
            }
            
            $participant->save();

            DB::commit();
            $user->password = $password;

            return $user;
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

    public function fetchPatient($user_id){
        $data = self::where('user_id', $user_id)->first();
        if(!$data){
            return false;
        }
        return $data;
    }
}
