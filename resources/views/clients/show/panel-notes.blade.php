@permission('create_client_notes')
    <button type="button" id="add-note-button" class="btn btn-sm btn-primary"><em class="fa fa-plus"></em> Add</button>
@endpermission
<br><br>
<div class="card">
    <div>
        <div class="table-responsive">
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th>Author</th>
                    <th>Note</th>
                    <th>Created</th>
                </tr>
                </thead>
                <tbody>
                @foreach($client->caseNotes as $note)
                    @if(auth()->user()->can('view_client_notes'))
                        <tr>
                            <td>{{ $note->author->name }}</td>
                            <td>{!! nl2br($note->comments) !!}</td>
                            <td>{{ $note->created_at->format('m/d/Y h:i a') }}</td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@permission('create_client_notes')
<div class="modal fade" id="notesModal" tabindex="-1" role="dialog" aria-labelledby="notesModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <form action="" method="post" id="notesForm">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><span id="notesModalTitle"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    @include('partial-session-alerts')

                    @csrf

                    <div class="form-row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="caseNoteComment"><b>Comments</b></label>
                                <textarea name="caseNoteComment" id="caseNoteComment" cols="30" rows="5" class="form-control">{{ old('caseNoteComment') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success"><em class="fa fa-save"></em> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endpermission