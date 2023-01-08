<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class TeamRequest extends FormRequest
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
            '*.position' => [
                'required', 'string',
                Rule::in(['defender', 'midfielder', 'forward']),
            ],

            '*.mainSkill' => [
                'required', 'string',
                Rule::in(['defense', 'attack', 'speed', 'strength', 'stamina']),
            ],

            '*.numberOfPlayers' => 'required|integer|min:1',
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
            '*.position' => [
                'required' => 'Player position field is required',
                'string' => 'The :attribute should be a string',
                'in' => 'Player positon is invalid (allowed:defender, midfielder, forward)'
            ],

            '*.mainSkill' => [
                'required' => 'The :attribute field is required',
                'string' => 'The :attribute should be a string',
                'in' => 'The :attribute is invalid (allowed:defense, attack, speed, strength, stamina)'
            ],

            '*.numberOfPlayers' => [
                'required' => 'The :attribute field is required',
                'integer' => 'The :attribute should be an integer',
                'min' => 'The minimum value of :attribute is 0',
            ]
        ];
    }
}
