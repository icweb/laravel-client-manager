<?php

namespace App\Http\Requests;

use App\AppList;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CreatesService extends FormRequest
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
                auth()->user()->can('create_client_service')
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
            'serviceComments'     => ['nullable', 'string'],
            'serviceActiveAt'     => ['required', 'date_format:Y-m-d', 'before:' . date('m/d/Y', strtotime($request->input('serviceExpiresAt')))],
            'serviceExpiresAt'    => ['required', 'date_format:Y-m-d', 'after:' . date('m/d/Y', strtotime($request->input('serviceActiveAt'))), 'after:' . date('m/d/Y', time())]
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
            ->redirectTo($this->getRedirectUrl() . '#addService');
    }
}
