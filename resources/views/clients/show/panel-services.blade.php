@permission('create_client_service')
    <button type="button" id="add-service-button" class="btn btn-sm btn-primary"><em class="fa fa-plus"></em> Add</button>
@endpermission
@if($client->expired_services_count > 0 && auth()->user()->can('view_expired_client_services'))
    <button type="button" class="btn btn-sm btn-secondary show-expired-button">Toggle Expired</button>
@endif
<br><br>
<div class="card">
    <div>
        <div class="table-responsive">
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th>Service</th>
                    <th>Comments</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($client->services as $service)
                    @if(
                        ($service->expired && auth()->user()->can('view_expired_client_services'))
                        || (!$service->expired && auth()->user()->can('view_client_services'))
                    )
                        <tr data-expired="{{ $service->expired }}">
                            <td>{{ $service->service->item_title }}</td>
                            <td>
                                @if(!$service->active)
                                    <span class="text-success">Active on {{ $service->active_at->format('m/d/Y') }}</span><br>
                                @elseif($service->expired)
                                    <span class="text-danger">Expired on {{ $service->expires_at->format('m/d/Y') }}</span><br>
                                @endif
                                {{ $service->comments }}
                            </td>
                            <td class="text-right">
                                @if(!$service->expired && auth()->user()->can('edit_client_service'))
                                    <a href="javascript:void(0)" class="edit-service-button" data-id="{{ $service->id }}">Edit</a>
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
@if(auth()->user()->can('create_client_service') || auth()->user()->can('edit_client_service'))
    <div class="modal fade" id="serviceModal" tabindex="-1" role="dialog" aria-labelledby="serviceModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <form action="" method="post" id="serviceForm">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><span id="serviceModalTitle"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        @include('partial-session-alerts')

                        @csrf
                        <input type="hidden" name="_method" value="POST" id="serviceFormMethod">
                        <input type="hidden" name="serviceId" value="" id="serviceId">

                        <div class="form-row">

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="serviceName">Service <span class="text-danger required"><b>*</b></span></label>
                                    <select name="serviceName" id="serviceName" class="form-control form-control-sm lock-on-edit" required>
                                        @foreach(\App\AppList::items('client_services') as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="serviceActiveAt">Active At <span class="text-danger required"><b>*</b></span></label>
                                    <input type="date" class="form-control form-control-sm lock-on-edit" name="serviceActiveAt" id="serviceActiveAt" aria-describedby="serviceActiveAt" required>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="serviceExpiresAt">Expires At <span class="text-danger required"><b>*</b></span></label>
                                    <input type="date" class="form-control form-control-sm" name="serviceExpiresAt" id="serviceExpiresAt" aria-describedby="serviceExpiresAt" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="serviceComments"><b>Comments</b></label>
                                    <textarea name="serviceComments" id="serviceComments" cols="30" rows="5" class="form-control">{{ old('serviceComments') }}</textarea>
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