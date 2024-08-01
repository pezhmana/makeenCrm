<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditTeacherRequest extends FormRequest
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
            'name'=> 'min:1|unique:teachers,name',
            'description'=>'min:1|max:255',
            'telegram'=>'string|digits:11',
            'message'=>'|digits:11',
        ];
    }
}
