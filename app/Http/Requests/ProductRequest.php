<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|string|in:active,inactive,pending',
        ];
    }

    /**
     * Get the validation messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'price.required' => 'O campo preço é obrigatório.',
            'price.numeric' => 'O preço deve ser um número.',
            'status.required' => 'O campo status é obrigatório.',
            'status.in' => 'O status deve ser um dos seguintes: active, inactive, pending.',
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
