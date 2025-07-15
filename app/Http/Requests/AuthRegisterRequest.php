<?php

namespace App\Http\Requests;

use App\Rules\FullNameRule;
use Illuminate\Foundation\Http\FormRequest;

class AuthRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required','string',new FullNameRule],
            'email' => ['required','email','unique:users,email'],
            'password' => ['required']
        ];
    }


    public function messages()
    {
        return [
            'required' => '* campo obrigatório.',
            'email' => '* campo aceita apenas e-mail.',
        ];
    }
}
