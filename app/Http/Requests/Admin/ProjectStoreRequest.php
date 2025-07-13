<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProjectStoreRequest extends FormRequest
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
            'project_type' => 'required',
            'invest_sum' => 'required',
            'people_count' => 'required|integer',
            'curator_id' => 'required|string',
            'file' => 'required|file|max:2048',
        ];
    }
}
