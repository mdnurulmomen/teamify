<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PlayerRequest extends FormRequest
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
            'name' => 'required|string',
            'position' => [
                'required', 'string',
                Rule::in(['defender', 'midfielder', 'forward']),
            ],
            'playerSkills' => 'required|array|min:1',
            'playerSkills.*.skill' => [
                'required', 'string',
                Rule::in(['defense', 'attack', 'speed', 'strength', 'stamina']),
            ],
            'playerSkills.*.value' => [
                'nullable', 'integer', 'min:0'
            ],
        ];
    }
}
