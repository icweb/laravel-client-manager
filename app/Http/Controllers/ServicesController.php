<?php

namespace App\Http\Controllers;

use App\AppList;
use App\Client;
use App\Http\Requests\CreatesService;
use App\Http\Requests\EditsService;
use App\Http\Requests\GetsService;
use App\Service;
use Illuminate\Support\Facades\DB;

class ServicesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatesService  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatesService $request, $id)
    {
        $client = Client::findOrFail($id);
        $service = AppList::where('item_title', $request->input('serviceName'))->first();

        if(count($service))
        {
            $client->services()->create([
                'author_id'  => auth()->user()->id,
                'service_id' => $service->id,
                'comments'   => $request->input('serviceComments'),
                'active_at'  => date('Y-m-d H:i:s', strtotime($request->input('serviceActiveAt'))),
                'expires_at' => date('Y-m-d H:i:s', strtotime($request->input('serviceExpiresAt'))),
            ]);
        }

        return redirect()
            ->route('clients.show', [$client->id, 'services'])
            ->with('success', 'Your changes were saved');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EditsService  $request
     * @return \Illuminate\Http\Response
     */
    public function update(EditsService $request)
    {
        $service = Service::findOrFail($request->input('serviceId'));

        if(!$service->expired)
        {
            $service->update([
                'comments'   => $request->input('serviceComments'),
                'expires_at' => date('Y-m-d H:i:s', strtotime($request->input('serviceExpiresAt'))),
            ]);
        }

        return redirect()
            ->route('clients.show', [$service->client->id, 'services'])
            ->with('success', 'Your changes were saved');
    }

    /**
     * Display the specified resource from a ajax request.
     *
     * @param GetsService $request
     * @return \Illuminate\Http\Response
     */
    public function show(GetsService $request)
    {
        return Service::select('id', 'service_id', 'comments', DB::raw('DATE_FORMAT(active_at, "%Y-%m-%d") as activeAt, DATE_FORMAT(expires_at, "%Y-%m-%d") as expiresAt'))->findOrFail($request->input('id'));
    }
}
