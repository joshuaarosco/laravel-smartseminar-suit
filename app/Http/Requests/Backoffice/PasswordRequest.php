<?php

namespace App\Http\Requests\Backoffice;

use Illuminate\Foundation\Http\FormRequest;

class PasswordRequest extends FormRequest
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
        return [
            //
            'old_password' => "required",
            'new_password' => "confirmed|min:8",
            'new_password_confirmation' => "required|min:8|different:old_password",
        ];
    }

    public function messages(){
        return [
            'new_password.confirmed' => "The password confirmation does not match.",
            'new_password_confirmation.different' => "The new password and password must be different."
        ];
    }
}
