<?php

namespace App\Http\Requests\Backoffice;

use Illuminate\Foundation\Http\FormRequest;

class NewPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'new_password' => "confirmed|min:8",
            'new_password_confirmation' => "required|min:8",
        ];
    }

    public function messages(){
        return [
            'new_password.confirmed' => "The password confirmation does not match.",
            'new_password_confirmation.different' => "The new password and password must be different."
        ];
    }
}
