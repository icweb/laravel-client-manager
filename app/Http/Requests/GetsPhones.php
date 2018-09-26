<?php

namespace App\Http\Requests;

use App\Phone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class GetsPhones extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param Request $request
     * @return bool
     */
    public function authorize(Request $request)
    {
        $phone = Phone::findOrFail($request->input('id'));

        return
            (
                auth()->user()->can('edit_client_phone')
                && in_array($phone->client->id, auth()->user()->clientIds())
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
