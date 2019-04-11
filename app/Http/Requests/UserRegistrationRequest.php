<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegistrationRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'forename' => 'nullable|string|max:191',
            'surname' => 'nullable|string|max:191',
            'email' => 'required|email|max:191|unique:users,email,' . $this->id . ',id',
            'class' => 'nullable|string|max:191',
            'end_date_study' => 'nullable|date_format:Y-m-d',
            'role' => 'required|exists:roles,id'
        ];
    }
}
