<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProjectUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'country' => 'required|string',
            'audan_id' => 'required|integer',
            'project_type' => 'required|string',
            'invest_sum' => 'required|numeric',
            'people_count' => 'required|integer',
            'phone' => 'required|string',
            'curator' => 'required|string',
        ];
    }
}
