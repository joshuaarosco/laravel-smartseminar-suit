<?php

namespace App\Http\Requests\Backoffice;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
            'name' => "required",
            'details' => "required",
            'category_id' => "required",
            'start' => "required",
            'end' => "required",
            'status' => "required",
        ];
        
    }
    
    public function messages(){
		return [
            'category_id.required' => "Event Category is required.",
		];
	}
}
