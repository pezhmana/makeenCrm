<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use function PHPUnit\TestFixture\func;
use function Webmozart\Assert\Tests\StaticAnalysis\digits;

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
            'name' => 'required|min:8',
            'phone' => 'required|digits:11|unique:users,phone',
            'password' => ['required', 'min:8', 'regex/[a-z]/', 'regex/[0-9]', 'regex/^[A-Za-z0-9/m]+$/']
        ];
    }


    public function attributes(): array
    {
        return [
            'name' => 'نام کاربری',
            'phone' => 'شماره موبایل',
            'password' => ' پسورد'

        ];

    }
}
