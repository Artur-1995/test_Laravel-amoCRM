<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LinkRequest extends FormRequest
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
            'id' => 'required|string',
            'leadId' => 'required|string',
        ];
    }

    /**
     * Get the messages for rules.
     *
     * @return array
     */
    public function messages() {
        return [
            'id.required' => 'Отсутствует id контакта.',
            'leadId.required' => 'Отсутствует id сделки.',
        ];
    }
}
