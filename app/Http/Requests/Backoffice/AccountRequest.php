<?php

namespace App\Http\Requests\Backoffice;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = auth()->user();
        return [
            //
            'fname' => "required",
            'lname' => "required",
            'email' => "required|unique:users,email,{$user->id}",
            'username' => "required|unique:users,username,{$user->id}",
            'contact_number' => "required",
            'birthdate' => "required",
            'address' => "required",
        ];
    }
}
