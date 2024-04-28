<?php

use Illuminate\Database\Seeder;
use App\Models\User as UserModel;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //do some magic
        $user = new UserModel;
        $user->name = 'Super User';
        $user->username = 'admin';
        $user->type = 'super_user';
        $user->email = 'admin@projectx.com';
        $user->email_verified_at = Carbon::now();
        $user->password = bcrypt('password');
        $user->created_at = date('Y-m-d');
        $user->updated_at = date('Y-m-d');
        $user->save();
    }
}
