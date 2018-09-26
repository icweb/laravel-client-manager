<?php

namespace App\Http\Controllers;

use App\Address;
use App\AppList;
use App\Client;
use App\ClientRecordView;
use App\Http\Requests\CreatesClients;
use App\Http\Requests\EditsDemographics;
use App\Http\Requests\SearchesClients;
use App\Http\Requests\ViewsClientPage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('clients.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatesClients  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatesClients $request)
    {
        $client = Client::create([
            'author_id'         => auth()->user()->id,
            'chart_number'      => Client::getNextChartNumber(),
            'legal_first_name'  => $request->input('firstName'),
            'legal_last_name'   => $request->input('lastName'),
            'gender'            => $request->input('gender'),
            'date_of_birth'     => strtotime($request->input('birthday')),
        ]);

        return redirect()
            ->route('clients.show', [$client->id, 'alerts'])
            ->with('success', 'Your changes were saved');
    }

    /**
     * Display the specified resource.
     *
     * @param  ViewsClientPage $request
     * @param  int  $id
     * @param  string  $panel
     * @return \Illuminate\Http\Response
     */
    public function show(ViewsClientPage $request, $id, $panel)
    {
        $client = Client::findOrFail($id);

        if(!in_array($panel, ['alerts', 'demographics', 'phones','addresses', 'emails', 'services', 'notes']))
        {
            $panel = 'alerts';
        }

        ClientRecordView::log($client, $panel);

        return view('clients.show')
            ->with(['client' => $client, 'panel' => $panel]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EditsDemographics  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditsDemographics $request, $id)
    {
        $client = Client::findOrFail($id);

        $client->update([
            'legal_prefix'      => $request->input('legalPrefix'),
            'legal_first_name'  => $request->input('legalFirstName'),
            'legal_middle_name' => $request->input('legalMiddleName'),
            'legal_last_name'   => $request->input('legalLastName'),
            'legal_suffix'      => $request->input('legalSuffix'),
            'birth_prefix'      => $request->input('birthPrefix'),
            'birth_first_name'  => $request->input('birthFirstName'),
            'birth_middle_name' => $request->input('birthMiddleName'),
            'birth_last_name'   => $request->input('birthLastName'),
            'birth_suffix'      => $request->input('birthSuffix'),
            'gender'            => $request->input('gender'),
            'ssn'               => $request->input('ssn'),
            'comments'          => $request->input('comments'),
            'birthday'          => date('Y-m-d H:i:s', strtotime($request->input('birthday'))),
        ]);

        return redirect()
            ->route('clients.show', [$client->id, 'demographics'])
            ->with('success', 'Your changes were saved');
    }

    /**
     * Searches for a specified resource.
     *
     * @param SearchesClients $request
     * @return \Illuminate\Http\Response
     */
    public function search(SearchesClients $request)
    {
        $clients = Client::select('id', 'legal_first_name', 'legal_last_name', 'date_of_birth', 'chart_number', 'gender');

        if(!empty($request->input('searchFirstName'))) $clients = $clients->where('legal_first_name', 'LIKE', '%' . $request->input('searchFirstName') . '%');
        if(!empty($request->input('searchLastName'))) $clients = $clients->where('legal_last_name', 'LIKE', '%' . $request->input('searchLastName') . '%');
        if(!empty($request->input('searchBirthday'))) $clients = $clients->where('date_of_birth', 'LIKE', '%' . $request->input('searchBirthday') . '%');
        if(!empty($request->input('searchGender'))) $clients = $clients->where('gender', 'LIKE', $request->input('searchGender'));
        if(!empty($request->input('searchChartNumber'))) $clients = $clients->where('chart_number', 'LIKE', '%' . $request->input('searchChartNumber') . '%');

        $clients = $clients->get();

        return redirect()->route('clients.index')->with(['clients' => $clients]);
    }
}
