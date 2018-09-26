<?php

namespace App\Http\Controllers;

use App\Address;
use App\Client;
use App\Http\Requests\CreatesAddresses;
use App\Http\Requests\EditsAddreses;
use App\Http\Requests\GetsAddress;
use Illuminate\Support\Facades\DB;

class AddressesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatesAddresses  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatesAddresses $request, $id)
    {
        $client = Client::findOrFail($id);

        if($request->input('addressPrimary')) $client->addresses()->update(['primary' => 0]); //TODO move to event listener created

        $client->addresses()->create([
            'author_id'         => auth()->user()->id,
            'type'              => $request->input('addressType'),
            'primary'           => $request->input('addressPrimary'),
            'address_line_1'    => $request->input('addressLine1'),
            'address_line_2'    => $request->input('addressLine2'),
            'address_line_3'    => $request->input('addressLine3'),
            'city'              => $request->input('city'),
            'state'             => $request->input('state'),
            'zip_code'          => $request->input('zip'),
            'comments'          => $request->input('addressComments'),
            'active_at'         => date('Y-m-d H:i:s', strtotime($request->input('addressActiveAt'))),
            'expires_at'        => date('Y-m-d H:i:s', strtotime($request->input('addressExpiresAt'))),
        ]);

        return redirect()
            ->route('clients.show', [$client->id, 'addresses'])
            ->with('success', 'Your changes were saved');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EditsAddreses  $request
     * @return \Illuminate\Http\Response
     */
    public function update(EditsAddreses $request)
    {
        $address = Address::findOrFail($request->input('addressId'));

        if($request->input('addressPrimary') && !$address->primary) $address->client->addresses()->update(['primary' => 0]); //TODO move to event listener created

        if(!$address->expired)
        {
            $address->update([
                'primary'     => $request->input('addressPrimary'),
                'comments'    => $request->input('addressComments'),
                'expires_at'  => date('Y-m-d H:i:s', strtotime($request->input('addressExpiresAt'))),
            ]);
        }

        return redirect()
            ->route('clients.show', [$address->client->id, 'addresses'])
            ->with('success', 'Your changes were saved');
    }

    /**
     * Display the specified resource from a ajax request.
     *
     * @param GetsAddress $request
     * @return \Illuminate\Http\Response
     */
    public function show(GetsAddress $request)
    {
        return Address::select('id', 'type', 'primary', 'address_line_1', 'address_line_2', 'address_line_3', 'city', 'state', 'zip_code', 'comments', DB::raw('DATE_FORMAT(active_at, "%Y-%m-%d") as activeAt, DATE_FORMAT(expires_at, "%Y-%m-%d") as expiresAt'))->findOrFail($request->input('id'));
    }
}
