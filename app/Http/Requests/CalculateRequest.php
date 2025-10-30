<?php

namespace App\Http\Requests;

use App\Exceptions\InvalidDataException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CalculateRequest extends FormRequest
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
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'formula' => ['required', 'string', 'regex:/\[OMIE_MD\]/'],
        ];
    }

    /**
     * Get the custom error messages for the validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'start_date.required' => 'falta start_date',
            'start_date.date' => 'start_date debe ser una fecha válida',
            'start_date.before_or_equal' => 'start_date debe ser anterior o igual a end_date',

            'end_date.required' => 'falta end_date',
            'end_date.date' => 'end_date debe ser una fecha válida',
            'end_date.after_or_equal' => 'end_date debe ser posterior o igual a start_date',

            'formula.required' => 'falta formula',
            'formula.string' => 'formula debe ser una cadena de texto',
            'formula.regex' => 'la formula debe contener siempre el segmento [OMIE_MD]',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes()
    {
        return [
            'start_date' => 'fecha de inicio',
            'end_date' => 'fecha de fin',
            'formula' => 'fórmula',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @return void
     *
     * @throws \App\Exceptions\InvalidDataException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();

        $message = implode(', ', $errors);

        throw new InvalidDataException($message);
    }
}
