@permission('create_client_email')
    <button type="button" id="add-email-button" class="btn btn-sm btn-primary"><em class="fa fa-plus"></em> Add</button>
@endpermission
@if($client->expired_email_count > 0 && auth()->user()->can('view_expired_client_emails'))
    <button type="button" class="btn btn-sm btn-secondary show-expired-button">Toggle Expired</button>
@endif
<br><br>
<div class="card">
    <div>
        <div class="table-responsive">
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th>Type</th>
                    <th>Email</th>
                    <th>Comments</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($client->emails as $email)
                    @if(
                        ($email->expired && auth()->user()->can('view_expired_client_emails'))
                        || (!$email->expired && auth()->user()->can('view_client_emails'))
                    )
                        <tr data-expired="{{ $email->expired }}">
                            <td>{{ $email->type }}</td>
                            <td>
                                {!! $email->email !!}
                                <div>
                                    {!! $email->primary ? '<span class="badge badge-pill badge-primary small">Primary</span>' : '' !!}
                                </div>
                            </td>
                            <td>
                                @if(!$email->active)
                                    <span class="text-success">Active on {{ $email->active_at->format('m/d/Y') }}</span><br>
                                @elseif($email->expired)
                                    <span class="text-danger">Expired on {{ $email->expires_at->format('m/d/Y') }}</span><br>
                                @endif
                                {{ $email->comments }}
                            </td>
                            <td class="text-right">
                                @if(!$email->expired && auth()->user()->can('edit_client_email'))
                                    <a href="javascript:void(0)" class="edit-email-button" data-id="{{ $email->id }}">Edit</a>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@if(auth()->user()->can('create_client_email') || auth()->user()->can('edit_client_email'))
    <div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="emailModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <form action="" method="post" id="emailForm">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><span id="emailModalTitle"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        @include('partial-session-alerts')

                        @csrf
                        <input type="hidden" name="_method" value="POST" id="emailFormMethod">
                        <input type="hidden" name="emailId" value="" id="emailId">

                        <div class="form-row">

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="emailType">Email Type <span class="text-danger required"><b>*</b></span></label>
                                    <select name="emailType" id="emailType" class="form-control form-control-sm lock-on-edit" required>
                                        @foreach(\App\AppList::items('email_types') as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="emailPrimary">Primary Email? <span class="text-danger required"><b>*</b></span></label>
                                    <select name="emailPrimary" id="emailPrimary" class="form-control form-control-sm" required>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="emailAddress">Email Address <span class="text-danger required"><b>*</b></span></label>
                                    <input type="email" class="form-control form-control-sm lock-on-edit" name="emailAddress" id="emailAddress" aria-describedby="emailAddress" placeholder="Email Address" value="{{ old('emailAddress') }}" required>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="emailActiveAt">Active At <span class="text-danger required"><b>*</b></span></label>
                                    <input type="date" class="form-control form-control-sm lock-on-edit" name="emailActiveAt" id="emailActiveAt" aria-describedby="emailActiveAt" required>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="emailExpiresAt">Expires At <span class="text-danger required"><b>*</b></span></label>
                                    <input type="date" class="form-control form-control-sm" name="emailExpiresAt" id="emailExpiresAt" aria-describedby="emailExpiresAt" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="emailComments"><b>Comments</b></label>
                                    <textarea name="emailComments" id="emailComments" cols="30" rows="5" class="form-control">{{ old('emailComments') }}</textarea>
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
@endif