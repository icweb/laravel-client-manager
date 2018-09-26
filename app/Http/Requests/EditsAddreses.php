<?php

namespace App\Http\Requests;

use App\AppList;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class EditsAddreses extends FormRequest
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
                auth()->user()->can('edit_client_address')
                && in_array($this->route('client'), auth()->user()->clientIds())
            );
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
            'addressId'         => ['required', 'integer'],
            'addressPrimary'    => ['required', 'boolean'],
            'addressComments'   => ['nullable', 'string'],
            'addressExpiresAt'  => ['required', 'date_format:Y-m-d']
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
            ->redirectTo($this->getRedirectUrl() . '#editAddress');
    }
}
