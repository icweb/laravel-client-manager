<?php

namespace App\Http\Requests;

use App\AppList;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SearchesClients extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('search_clients');
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
            'searchFirstName'     => ['nullable', 'string', 'max:191'],
            'searchLastName'      => ['nullable', 'string', 'max:191'],
            'searchChartNumber'   => ['nullable', 'integer'],
            'searchGender'        => ['nullable', Rule::in(AppList::items('genders'))],
            'searchBirthday'      => ['nullable', 'date_format:Y-m-d'],
        ];
    }
}
