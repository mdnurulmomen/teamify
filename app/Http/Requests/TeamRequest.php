<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class TeamRequest extends FormRequest
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
    * Configure the validator instance.
    *
    * @param  \Illuminate\Validation\Validator  $validator
    * @return void
    */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            foreach ($this->all() as $requirementKey => $requirement) {

                foreach ($this->all() as $requirementNewKey => $requirementNew) {

                    if ($requirementKey != $requirementNewKey && $requirement['position'] == $requirementNew['position'] && $requirement['mainSkill'] == $requirementNew['mainSkill']) {

                        $validator->errors()->add("$requirementNewKey.position", "Duplicate value for position: ".$requirementNew['position']." and mainSkill: ".$requirementNew['mainSkill']);

                    }

                }

            }

        });
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            '*.position.required' => 'The :attribute field is missing',
            // 'string' => 'The :attribute should be a string',
            // 'in' => 'The :attribute must be one of the following types: :values',
            '*.position.*' => 'Invalid value for :attribute: :input',      // string / in

            '*.mainSkill.required' => 'The :attribute field is missing',
            // 'string' => 'The :attribute should be a string',
            // 'in' => 'The :attribute must be one of the following types: :values',
            '*.mainSkill.*' => 'Invalid value for :attribute: :input',      // string / in

            '*.numberOfPlayers.required' => 'The :attribute field is missing',
            // 'integer' => 'The :attribute should be an integer',
            // 'min' => 'The minimum value of :attribute is 0',
            '*.numberOfPlayers.*' => 'Invalid value for :attribute: :input'     // integer / min
        ];
    }
}
