<?php

namespace App\Http\Requests;

use App\AppList;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class EditsRoles extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('admin_edit_roles');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param $request
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'id'                => ['required', 'string', 'max:191'],
            'roleId'          => ['required', 'string', 'max:191', Rule::unique('roles', 'name')->ignore($request->input('roleId'), 'name')],
            'roleName'        => ['required', 'string', 'max:191'],
            'roleDescription' => ['required', 'string', 'max:191'],
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * We are overriding this so we can set a url hash value
     * to change the active panel to edit instead of show
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl() . '#editRole');
    }
}
