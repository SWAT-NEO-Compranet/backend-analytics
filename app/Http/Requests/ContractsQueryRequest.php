<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContractsQueryRequest extends FormRequest
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
            'name' => ['required', 'exists:contracts,acronyms'],
            'interval' => ['sometimes', 'in:1,3,6,12,24,36,48,60,72,84,96,108,120,132']
        ];
    }
}
