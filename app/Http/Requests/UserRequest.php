<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
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
        //Recuperar o ID do usuario enviado na URL
        $userId = $this->route('user');
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . ($userId ? $userId->id : null),
            'password' => 'required|min:7'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function messages(): array
    {
        return [

            //e-mails
            'name.required' => 'Campo nome é obrigatório!',
            'email.required' => 'campo e-mail é obrigatório!',
            'email.email' => 'Necessario enviar um e-mail válido!',
            'email.unique' => 'O e-mail já está cadastrado!',

            // senhas
            'password.required' => 'Campo senha é obrigatório',
            'password.min' => 'Senha com no minimo :min caracteres!',
        ];
    }

    protected function failedValidation(validator $validator)
    {
        throw new HttpResponseException(response()->Json([
            'status' => false,
            'erros' => $validator->errors(),
        ], 422)); // O servidor recebeu os dados do cliente mas a validaão dos erros de validação não deixaram a operação ser concluida.
    }

}
