<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
     public function rules()
    {
        return [
            'email' => ['required','string','email','unique:users,email'],
            'password' => ['required']
        ];
    }


    public function messages()
    {
        return [
            'required' => '* campo obrigatório.',
            'string' => '* campo aceita apenas caracteres.',
            'email' => '* campo aceita apenas e-mail.',
            'unique' => '* Conta de usuário já existe.'
        ];
    }
}
