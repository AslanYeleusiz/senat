<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class UserSaveRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    
    public function rules()
    {
        $rules = [
            'fio' => 'required|min:3|max:255',
            'job_title' => 'required|max:255',
            'phone' => 'required|unique:users,phone',
            'real_password' => 'required|min:6',
            'audan_id' => 'required',
            'role_id' => 'required',
        ];
        if (!empty($this->input('id'))) {
            $rules['iin'] = 'required|digits:12|unique:users,' . $this->input('id');
        } else {
            $rules['iin']    = 'required|unique:users|digits:12';
        }
        return $rules;
    }
}
