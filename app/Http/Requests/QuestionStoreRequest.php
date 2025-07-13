<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'project_id' => 'required|integer|exists:projects,id',
            'question' => 'required|string|max:255',
            'limit_date' => 'required|date',
        ];
    }
    
    public function messages()
    {
        return [
            'title.required' => 'Аты міндетті түрде енгізілуі керек.',
            'project_id.required' => 'Жоба таңдалуы керек.',
            'question.required' => 'Сұрақ мазмұны міндетті түрде енгізілуі керек.',
            'limit_date.required' => 'Сұрақ мерзімі міндетті түрде енгізілуі керек.',
            'limit_date.date' => 'Сұрақ мерзімі дата болуы керек.',
        ];
    }
}
