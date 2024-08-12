<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required_if:type,up|max:15',
            'last_name'=>'required_if:type,up|max:15',
            'code'=>'required_if:type,received|size:5',
            'phone' =>'Required|digits:11',
//            'password' => ['required','min:8','regex:/[a-z]/','regex:/[A-Z]/','regex:/[0-9]/','regex:/^[A-Za-z0-9]+$/']
        ];
    }

}
