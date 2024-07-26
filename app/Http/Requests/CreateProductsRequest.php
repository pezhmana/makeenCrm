<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductsRequest extends FormRequest
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
            'description'=>'required|nullable|min:3',
            'price'=>'required|min:0|integer',
           'discount_price'=>'required|min:0'

        ];
    }
    public function attributes(): array
    {
        return array(
            'name'=>' name',
            'description'=>'description ',
            'price'=>'price',
            'discount_price'=>' discount_price'

        );

    }
}
