<?php

namespace App\Http\Requests;

use App\AppList;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CreatesAddresses extends FormRequest
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
                auth()->user()->can('create_client_address')
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
            'addressType'       => ['required', Rule::in(AppList::items('address_types'))],
            'addressPrimary'    => ['required', 'boolean'],
            'addressLine1'      => ['required', 'max:191'],
            'addressLine2'      => ['nullable', 'max:191'],
            'addressLine3'      => ['nullable', 'max:191'],
            'city'              => ['required', 'max:191'],
            'state'             => ['required', 'max:191'],
            'zip'               => ['required', 'max:191'],
            'addressComments'   => ['nullable', 'string'],
            'addressActiveAt'   => ['required', 'date_format:Y-m-d', 'before:' . date('m/d/Y', strtotime($request->input('addressExpiresAt')))],
            'addressExpiresAt'  => ['required', 'date_format:Y-m-d', 'after:' . date('m/d/Y', strtotime($request->input('addressActiveAt'))), 'after:' . date('m/d/Y', time())]
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
            ->redirectTo($this->getRedirectUrl() . '#addAddress');
    }
}
