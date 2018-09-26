<?php

namespace App\Http\Controllers;

use App\CaseNote;
use App\Client;
use App\Http\Requests\CreatesCaseNotes;
use App\Http\Requests\GetsCaseNotes;

class CaseNotesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatesCaseNotes  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatesCaseNotes $request, $id)
    {
        $client = Client::findOrFail($id);

        $client->caseNotes()->create([
            'author_id'         => auth()->user()->id,
            'comments'          => $request->input('caseNoteComment')
        ]);

        return redirect()
            ->route('clients.show', [$client->id, 'notes'])
            ->with('success', 'Your changes were saved');
    }
}
