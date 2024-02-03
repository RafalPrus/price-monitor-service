<?php

namespace App\Http\Requests\Guest;

use Illuminate\Foundation\Http\FormRequest;
use Laravel\Fortify\Fortify;

class LoginRequestForm extends FormRequest
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
            Fortify::username() => 'required|string',
            'password' => 'required|string',
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'email' => [
                'description' => 'User email',
                'example' => 'example@example.com'
            ],
            'password' => [
                'description' => 'User password',
                'example' => 'some_password',
            ],
        ];
    }
}
