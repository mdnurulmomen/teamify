<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PlayerRequest extends FormRequest
{
    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;

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
                'required', 'string', 'distinct',
                Rule::in(['defense', 'attack', 'speed', 'strength', 'stamina']),
            ],
            'playerSkills.*.value' => [
                'required', 'integer', 'min:0'
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
            'name.required' => 'The :attribute field is missing',
            'name.*' => 'Invalid value for :attribute: :input',   // string

            'position.required' => 'The :attribute field is missing',
            // 'in' => 'The :attribute must be one of the following types: :values',
            'position.*' => 'Invalid value for :attribute: :input',     // string / in

            'playerSkills.required' => 'The :attribute field is missing',
            'playerSkills.array' => 'The :attribute should be an array',
            'playerSkills.min' => 'The :attribute should have at least one skill',
            'playerSkills.*' => 'Invalid value for :attribute: :input',

            'playerSkills.*.skill.required' => 'The :attribute field is missing',
            'playerSkills.*.skill.distinct' => 'The :attribute field is duplicate',
            // 'playerSkills.*.skill.string' => 'The :attribute should be a string',
            // 'playerSkills.*.skill.in' => 'The :attribute is invalid (allowed:defense, attack, speed, strength, stamina)',
            'playerSkills.*.skill.*' => 'Invalid value for :attribute: :input',     // string / in

            'playerSkills.*.value.required' => 'The :attribute field is missing',
            // 'playerSkills.*.value.integer' => 'The :attribute should be an integer',
            // 'playerSkills.*.value.min' => 'The minimum value of :attribute is 0',
            'playerSkills.*.value.*' => 'Invalid value for :attribute: :input',
        ];
    }
}
