<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTeacherRequest extends FormRequest
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
            'name'=> 'required|min:3|unique:teachers,name',
            'expertise'=>'required',
            'description'=>'required|min:1|max:255',
            'instagram'=>'required',
            'telegram'=>'required|string',
            'message'=>'required|min:1|max:255',
        ];
    }
    public function attributes(): array
    {
        return array(
            'name'=>'name',
            'expertise'=>'expertise',
            'description'=>'description',
            'instagram'=>' instagram',
            'telegram'=>' telegram',
            'message'=>' message'

        );

    }
}
