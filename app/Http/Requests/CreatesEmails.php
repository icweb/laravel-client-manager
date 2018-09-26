<?php

namespace App\Http\Requests;

use App\AppList;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CreatesEmails extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return
            (
                auth()->user()->can('create_client_email')
                && in_array($this->route('client'), auth()->user()->clientIds())
            );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param Request $request
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'emailType'         => ['required', Rule::in(AppList::items('email_types'))],
            'emailPrimary'      => ['required', 'boolean'],
            'emailAddress'      => ['required', 'max:191'],
            'emailComments'     => ['nullable', 'string'],
            'emailActiveAt'     => ['required', 'date_format:Y-m-d', 'before:' . date('m/d/Y', strtotime($request->input('emailExpiresAt')))],
            'emailExpiresAt'    => ['required', 'date_format:Y-m-d', 'after:' . date('m/d/Y', strtotime($request->input('emailActiveAt'))), 'after:' . date('m/d/Y', time())]
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
            ->redirectTo($this->getRedirectUrl() . '#addEmail');
    }
}
