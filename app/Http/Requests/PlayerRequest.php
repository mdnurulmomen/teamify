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

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name' => [
                'required' => 'Player name is required',
                '*' => 'Invalid string for player name'
            ],

            'position' => [
                'required' => 'Player position field is required',
                'in' => 'Player positon is invalid (allowed:defender, midfielder, forward)',
                '*' => 'Invalid value for position'
            ],

            'playerSkills' => [
                'required' => 'The :attribute field is required',
                'array' => 'Player skill should be an array',
                'min' => 'The :attribute should have at least one element',
            ],

            'playerSkills.*.skill' => [
                'required' => 'The :attribute field is required',
                'string' => 'The :attribute should be a string',
                'in' => 'The :attribute is invalid (allowed:defense, attack, speed, strength, stamina)',
            ],

            'playerSkills.*.value' => [
                'integer' => 'The :attribute should be an integer',
                'min' => 'The minimum value of :attribute is 0',
            ],
        ];
    }
}
