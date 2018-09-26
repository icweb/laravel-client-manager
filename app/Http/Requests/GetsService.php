<?php

namespace App\Http\Requests;

use App\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class GetsService extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param Request $request
     * @return bool
     */
    public function authorize(Request $request)
    {
        $service = Service::findOrFail($request->input('id'));

        return
            (
                auth()->user()->can('edit_client_service')
                && in_array($service->client->id, auth()->user()->clientIds())
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
