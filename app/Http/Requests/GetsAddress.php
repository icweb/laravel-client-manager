<?php

namespace App\Http\Requests;

use App\Address;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class GetsAddress extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param Request $request
     * @return bool
     */
    public function authorize(Request $request)
    {
        $address = Address::findOrFail($request->input('id'));

        return
            (
                auth()->user()->can('edit_client_address')
                && in_array($address->client->id, auth()->user()->clientIds())
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
            'id' => ['required', 'integer'],
        ];
    }
}
