<?php

namespace App\Http\Controllers;

use App\Client;
use App\Email;
use App\Http\Requests\CreatesEmails;
use App\Http\Requests\EditsEmails;
use App\Http\Requests\GetsEmails;
use Illuminate\Support\Facades\DB;

class EmailsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatesEmails  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatesEmails $request, $id)
    {
        $client = Client::findOrFail($id);

        if($request->input('emailPrimary')) $client->emails()->update(['primary' => 0]); //TODO move to event listener created

        $client->emails()->create([
            'author_id'  => auth()->user()->id,
            'type'       => $request->input('emailType'),
            'primary'    => $request->input('emailPrimary'),
            'email'      => $request->input('emailAddress'),
            'comments'   => $request->input('emailComments'),
            'active_at'  => date('Y-m-d H:i:s', strtotime($request->input('emailActiveAt'))),
            'expires_at' => date('Y-m-d H:i:s', strtotime($request->input('emailExpiresAt'))),
        ]);

        return redirect()
            ->route('clients.show', [$client->id, 'emails'])
            ->with('success', 'Your changes were saved');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EditsEmails  $request
     * @return \Illuminate\Http\Response
     */
    public function update(EditsEmails $request)
    {
        $email = Email::findOrFail($request->input('emailId'));

        if($request->input('emailPrimary') && !$email->primary) $email->client->emails()->update(['primary' => 0]); //TODO move to event listener created

        if(!$email->expired)
        {
            $email->update([
                'primary'    => $request->input('emailPrimary'),
                'comments'   => $request->input('emailComments'),
                'expires_at' => date('Y-m-d H:i:s', strtotime($request->input('emailExpiresAt'))),
            ]);
        }

        return redirect()
            ->route('clients.show', [$email->client->id, 'emails'])
            ->with('success', 'Your changes were saved');
    }

    /**
     * Display the specified resource from a ajax request.
     *
     * @param GetsEmails $request
     * @return \Illuminate\Http\Response
     */
    public function show(GetsEmails $request)
    {
        return Email::select('id', 'type', 'primary', 'verified', 'email', 'comments', DB::raw('DATE_FORMAT(active_at, "%Y-%m-%d") as activeAt, DATE_FORMAT(expires_at, "%Y-%m-%d") as expiresAt'))->findOrFail($request->input('id'));
    }
}
