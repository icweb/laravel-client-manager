@permission('create_client_phone')
    <button type="button" id="add-phone-button" class="btn btn-sm btn-primary"><em class="fa fa-plus"></em> Add</button>
@endpermission
@if($client->expired_phone_count > 0 && auth()->user()->can('view_expired_client_phones'))
    <button type="button" class="btn btn-sm btn-secondary show-expired-button">Toggle Expired</button>
@endif
<br><br>
<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th>Type</th>
                <th>Contact</th>
                <th>Phone</th>
                <th>Comments</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($client->phones as $phone)
                @if(
                    ($phone->expired && auth()->user()->can('view_expired_client_phones'))
                    || (!$phone->expired && auth()->user()->can('view_client_phones'))
                )
                    <tr data-expired="{{ $phone->expired }}">
                        <td>{{ $phone->type }}</td>
                        <td>{{ $phone->contact_time }}</td>
                        <td>
                            {!! $phone->phone !!}
                            <div>
                                {!! $phone->primary ? '<span class="badge badge-pill badge-primary small">Primary</span>' : '' !!}
                            </div>
                        </td>
                        <td>
                            @if(!$phone->active)
                                <span class="text-success">Active on {{ $phone->active_at->format('m/d/Y') }}</span><br>
                            @elseif($phone->expired)
                                <span class="text-danger">Expired on {{ $phone->expires_at->format('m/d/Y') }}</span><br>
                            @endif
                            {{ $phone->comments }}
                        </td>
                        <td class="text-right">
                            @if(!$phone->expired && auth()->user()->can('edit_client_phone'))
                                <a href="javascript:void(0)" class="edit-phone-button" data-id="{{ $phone->id }}">Edit</a>
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@if(auth()->user()->can('create_client_phone') || auth()->user()->can('edit_client_phone'))
    <div class="modal fade" id="phoneModal" tabindex="-1" role="dialog" aria-labelledby="phoneModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <form action="" method="post" id="phoneForm">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><span id="phoneModalTitle"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        @include('partial-session-alerts')

                        @csrf
                        <input type="hidden" name="_method" value="POST" id="phoneFormMethod">
                        <input type="hidden" name="phoneId" value="" id="phoneId">

                        <div class="form-row">

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="phoneType">Phone Type <span class="text-danger required"><b>*</b></span></label>
                                    <select name="phoneType" id="phoneType" class="form-control form-control-sm lock-on-edit" required>
                                        @foreach(\App\AppList::items('phone_types') as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="phonePrimary">Primary Phone? <span class="text-danger required"><b>*</b></span></label>
                                    <select name="phonePrimary" id="phonePrimary" class="form-control form-control-sm" required>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="phoneNumber">Phone <span class="text-danger required"><b>*</b></span></label>
                                    <input type="text" class="form-control form-control-sm lock-on-edit" name="phoneNumber" id="phoneNumber" aria-describedby="phoneNumber" placeholder="Phone Number" value="{{ old('phoneNumber') }}" required>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="phoneContactTime">Contact Times <span class="text-danger required"><b>*</b></span></label>
                                    <select name="phoneContactTime" id="phoneContactTime" class="form-control form-control-sm" required>
                                        @foreach(\App\AppList::items('contact_times') as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="phoneActiveAt">Active At <span class="text-danger required"><b>*</b></span></label>
                                    <input type="date" class="form-control form-control-sm lock-on-edit" name="phoneActiveAt" id="phoneActiveAt" aria-describedby="phoneActiveAt" required>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="phoneExpiresAt">Expires At <span class="text-danger required"><b>*</b></span></label>
                                    <input type="date" class="form-control form-control-sm" name="phoneExpiresAt" id="phoneExpiresAt" aria-describedby="phoneExpiresAt" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="phoneComments"><b>Comments</b></label>
                                    <textarea name="phoneComments" id="phoneComments" cols="30" rows="5" class="form-control">{{ old('phoneComments') }}</textarea>
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