<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "full_name" => 'required',
            "email" => 'required|email|unique:users,email',
            "password" => 'required|confirmed',
            "address.*" => 'required|array',
            "address.cep" => 'required|regex:/[0-9]{5}-?[0-9]{3}/',
            "address.state" => 'required',
            "address.number" => 'required',
            "address.city" => 'required',
            "address.neighborhood" => 'required',
            "address.street" => 'required',
        ];
    }
}
