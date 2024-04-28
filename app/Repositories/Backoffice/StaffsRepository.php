<?php

namespace App\Repositories\Backoffice;
use App\Domain\Interfaces\Repositories\Backoffice\IStaffsRepository;

use App\Services\ImageUploader as UploadLogic;
use App\Models\Backoffice\Staff as Model;
use App\Models\User;
use DB, Str, Input;

class StaffsRepository extends Model implements IStaffsRepository
{

    public function fetch(){
        if(Input::has('search') AND Input::get('search') != null){
            $exploded = explode(" ",str_replace(' & ',' ',Input::get('search')));
            $query = self::query();
            foreach ($exploded as $key => $value) {
                $query->where('fname', 'like', "%{$value}%")
                ->orWhere('lname', 'like', "%{$value}%");
            }
            return $query->get();
        }
        return self::all();
    }

    public function saveData($request){
        DB::beginTransaction();
        try {
            $user = User::find($request->user_id);
            $staff = $user? $this->where('user_id', $user->id)->first() : null;
            
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
            $user->type = 'staff';
            $user->email = $request->email;

            $user->save();

            $staff = self::find($request->id)? : new self;

            $staff->user_id = $user->id;
            $staff->fname = $request->fname;
            $staff->lname = $request->lname;
            
            if($request->hasFile('file')){
                $upload = UploadLogic::upload($request->file,'storage/staff');
                $staff->path = $upload["path"];
                $staff->directory = $upload["directory"];
                $staff->filename = $upload["filename"];
            }

            $staff->save();

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

    public function serviceTypes($id){
        return ServiceType::where('service_id', $id)->get();
    }

    public function saveType($request){
        DB::beginTransaction();
        try {
            $serviceType = ServiceType::find($request->type_id)? : new ServiceType;

            $serviceType->service_id = $request->service_id;
            $serviceType->type = $request->type;
            $serviceType->price = $request->price;
            $serviceType->description = $request->description;

            $serviceType->save();

            DB::commit();

            return $serviceType;
        } catch (\Exception $e) {
             DB::rollback();
             return false;
        }
    }

    public function findServiceType($id){
        $data = ServiceType::find($id);
        if(!$data){
            return false;
        }
        return $data;
    }

    public function deleteType($id){
        DB::beginTransaction();
        try {
            ServiceType::destroy($id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }
}
