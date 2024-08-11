<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditUsersRequest extends FormRequest
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
            'phone' =>'digits:11',
            'birth_date'=>'date',
            'name'=>'min:3|max:20',
            'last_name'=>'min:3|max:20',
            'email'=>'email',
//          'password' => ['min:8','regex:/[a-z]/','regex:/[A-Z]/','regex:/[0-9]/','regex:/^[A-Za-z0-9]+$/']


        ];
    }
}
