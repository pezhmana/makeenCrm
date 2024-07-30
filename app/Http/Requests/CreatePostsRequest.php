<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostsRequest extends FormRequest
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
            'name'=>'required|min:3',
            'short'=>'required',
            'description'=>'required|max:255',
            'time'=>'required|integer'
        ];
    }
    public function attributes():array
    {
        return array(
        'name'=>'name',
            'short'=>'short',
            'description'=>'description',
            'time'=>'time'
);
    }
}
