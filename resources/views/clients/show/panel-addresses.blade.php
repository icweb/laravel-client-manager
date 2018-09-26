@permission('create_client_address')
    <button type="button" id="add-address-button" class="btn btn-sm btn-primary"><em class="fa fa-plus"></em> Add</button>
@endpermission
@if($client->expired_address_count > 0 && auth()->user()->can('view_expired_client_addresses'))
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
                    <th>Address</th>
                    <th>Comments</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($client->addresses as $address)
                    @if(
                        ($address->expired && auth()->user()->can('view_expired_client_addresses'))
                        || (!$address->expired && auth()->user()->can('view_client_addresses'))
                    )
                        <tr data-expired="{{ $address->expired }}">
                            <td>{{ $address->type }}</td>
                            <td>
                                {!! $address->address !!}
                                <div>
                                    {!! $address->primary ? '<span class="badge badge-pill badge-primary small">Primary</span>' : '' !!}
                                </div>
                            </td>
                            <td>
                                @if(!$address->active)
                                    <span class="text-success">Active on {{ $address->active_at->format('m/d/Y') }}</span><br>
                                @elseif($address->expired)
                                    <span class="text-danger">Expired on {{ $address->expires_at->format('m/d/Y') }}</span><br>
                                @endif
                                {{ $address->comments }}
                            </td>
                            <td class="text-right">
                                @if(!$address->expired && auth()->user()->can('edit_client_address'))
                                    <a href="javascript:void(0)" class="edit-address-button" data-id="{{ $address->id }}">Edit</a>
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
@if(auth()->user()->can('create_client_address') || auth()->user()->can('edit_client_address'))
    <div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="addressModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <form action="" method="post" id="addressForm">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><span id="addressModalTitle"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        @include('partial-session-alerts')

                        @csrf
                        <input type="hidden" name="_method" value="POST" id="addressFormMethod">
                        <input type="hidden" name="addressId" value="" id="addressId">

                        <div class="form-row">

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="addressType">Address Type <span class="text-danger required"><b>*</b></span></label>
                                    <select name="addressType" id="addressType" class="form-control form-control-sm lock-on-edit" required>
                                        @foreach(\App\AppList::items('address_types') as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="addressPrimary">Primary Address? <span class="text-danger required"><b>*</b></span></label>
                                    <select name="addressPrimary" id="addressPrimary" class="form-control form-control-sm" required>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="addressLine1">Address Line 1 <span class="text-danger required"><b>*</b></span></label>
                                    <input type="text" class="form-control form-control-sm lock-on-edit" name="addressLine1" id="addressLine1" aria-describedby="addressLine1" placeholder="Address Line 1" value="{{ old('addressLine1') }}" required>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="addressLine1">Address Line 2</label>
                                    <input type="text" class="form-control form-control-sm lock-on-edit" name="addressLine2" id="addressLine2" aria-describedby="addressLine2" placeholder="Address Line 2" value="{{ old('addressLine2') }}">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="addressLine1">Address Line 3</label>
                                    <input type="text" class="form-control form-control-sm lock-on-edit" name="addressLine3" id="addressLine3" aria-describedby="addressLine3" placeholder="Address Line 3" value="{{ old('addressLine3') }}">
                                </div>
                            </div>

                            <div class="col-5">
                                <div class="form-group">
                                    <label for="city">City <span class="text-danger required"><b>*</b></span></label>
                                    <input type="text" class="form-control form-control-sm lock-on-edit" name="city" id="city" aria-describedby="city" placeholder="City" value="{{ old('city') }}" required>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="state">State <span class="text-danger required"><b>*</b></span></label>
                                    <input type="text" class="form-control form-control-sm lock-on-edit" name="state" id="state" aria-describedby="state" placeholder="State" value="{{ old('state') }}" required>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label for="zip">Zip Code <span class="text-danger required"><b>*</b></span></label>
                                    <input type="text" class="form-control form-control-sm lock-on-edit" name="zip" id="zip" aria-describedby="zip" placeholder="Zip Code" value="{{ old('zip') }}" required>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="addressActiveAt">Active At <span class="text-danger required"><b>*</b></span></label>
                                    <input type="date" class="form-control form-control-sm lock-on-edit" name="addressActiveAt" id="addressActiveAt" aria-describedby="addressActiveAt" required>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="addressExpiresAt">Expires At <span class="text-danger required"><b>*</b></span></label>
                                    <input type="date" class="form-control form-control-sm" name="addressExpiresAt" id="addressExpiresAt" aria-describedby="addressExpiresAt" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="addressComments"><b>Comments</b></label>
                                    <textarea name="addressComments" id="addressComments" cols="30" rows="5" class="form-control">{{ old('addressComments') }}</textarea>
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