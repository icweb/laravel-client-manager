<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests\CreatesPhones;
use App\Http\Requests\EditsPhones;
use App\Http\Requests\GetsPhones;
use App\Phone;
use Illuminate\Support\Facades\DB;

class PhonesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatesPhones  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatesPhones $request, $id)
    {
        $client = Client::findOrFail($id);

        if($request->input('phonePrimary')) $client->phones()->update(['primary' => 0]); //TODO move to event listener created

        $client->phones()->create([
            'author_id'         => auth()->user()->id,
            'type'              => $request->input('phoneType'),
            'primary'           => $request->input('phonePrimary'),
            'contact_time'      => $request->input('phoneContactTime'),
            'phone'             => $request->input('phoneNumber'),
            'comments'          => $request->input('phoneComments'),
            'active_at'         => date('Y-m-d H:i:s', strtotime($request->input('phoneActiveAt'))),
            'expires_at'        => date('Y-m-d H:i:s', strtotime($request->input('phoneExpiresAt'))),
        ]);

        return redirect()
            ->route('clients.show', [$client->id, 'phones'])
            ->with('success', 'Your changes were saved');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EditsPhones  $request
     * @return \Illuminate\Http\Response
     */
    public function update(EditsPhones $request)
    {
        $phone = Phone::findOrFail($request->input('phoneId'));

        if($request->input('phonePrimary') && !$phone->primary) $phone->client->phones()->update(['primary' => 0]); //TODO move to event listener created

        if(!$phone->expired)
        {
            $phone->update([
                'primary'       => $request->input('phonePrimary'),
                'comments'      => $request->input('phoneComments'),
                'contact_time'  => $request->input('phoneContactTime'),
                'expires_at'    => date('Y-m-d H:i:s', strtotime($request->input('phoneExpiresAt'))),
            ]);
        }

        return redirect()
            ->route('clients.show', [$phone->client->id, 'phones'])
            ->with('success', 'Your changes were saved');
    }

    /**
     * Display the specified resource from a ajax request.
     *
     * @param GetsPhones $request
     * @return \Illuminate\Http\Response
     */
    public function show(GetsPhones $request)
    {
        return Phone::select('id', 'type', 'primary', 'contact_time', 'phone', 'comments', DB::raw('DATE_FORMAT(active_at, "%Y-%m-%d") as activeAt, DATE_FORMAT(expires_at, "%Y-%m-%d") as expiresAt'))->findOrFail($request->input('id'));
    }
}
