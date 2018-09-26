@extends('layouts.app')

@section('content')
    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <h1>Admin > Manage Roles</h1>
            <p>Manage the user roles and the associated permissions for this site</p>
            <a class="btn btn-secondary" href="{{ route('admin.index') }}"><em class="fa fa-angle-left"></em> Return</a>
           @permission('admin_add_roles')
                <button class="btn btn-primary" type="button" id="create-role-button"><em class="fa fa-plus"></em> New Role</button>
           @endpermission
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                @if(session('success'))
                    <div class="alert alert-success fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ session('success') }}
                    </div>
                @endif
            </div>
            <div class="col">
                <div class="card">
                    <div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-dark">
                                <tr>
                                    <th>Unique ID</th>
                                    <th>Display Name</th>
                                    <th>Description</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($roles as $role)
                                    <tr>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->display_name }}</td>
                                        <td>{{ $role->description }}</td>
                                        <td class="text-right">
                                            @permission('admin_edit_roles')
                                                <a href="javascript:void(0)" class="edit-role-button" data-id="{{ $role->id }}">Edit</a>
                                            @endpermission
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(auth()->user()->can('admin_add_roles') || auth()->user()->can('admin_edit_roles'))
        <div class="modal fade" id="addRoleModal" tabindex="-1" role="dialog" aria-labelledby="addRoleModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <form action="" id="roleForm" method="post">
                <div class="modal-dialog modal-full" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"><span id="roleModalTitle"></span></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            @include('partial-session-alerts')

                            @csrf
                            <input type="hidden" name="_method" id="roleFormMethod">
                            <input type="hidden" name="id" id="roleRecordId">

                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="roleId"><b>Unique ID</b> <span class="text-danger required"><b>*</b></span></label>
                                        <input name="roleId" id="roleId" class="form-control form-control-sm" value="{{ old('roleId') }}" placeholder="Unique ID" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="roleName"><b>Display Name</b> <span class="text-danger required"><b>*</b></span></label>
                                        <input name="roleName" id="roleName" class="form-control form-control-sm" value="{{ old('roleName') }}" placeholder="Display Name" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="roleDescription"><b>Description</b> <span class="text-danger required"><b>*</b></span></label>
                                        <input name="roleDescription" id="roleDescription" class="form-control form-control-sm" value="{{ old('roleDescription') }}" placeholder="Description" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <h4>Permissions</h4>
                                    Check the permitted permissions that should be assigned to this role
                                    <br><br>
                                    {{--<input type="checkbox" class="permissionCheckbox" id="checkAllPermissions" name="checkAllPermissions"> Check all--}}
                                </div>

                                @foreach($permissions as $item)
                                    <div class="{{ $item['col'] }} permission-col">
                                        <b>{{ $item['title'] }}</b>
                                        <table class="table no-mb table-sm">
                                            <tbody>
                                            @foreach($item['items'] as $permission)
                                                <tr>
                                                    <td style="width:30px">
                                                        <input type="checkbox" class="permissionCheckbox" id="{{ $permission['name'] }}" name="permission_{{ $permission['name'] }}">
                                                    </td>
                                                    <td class="permission-name text-secondary">
                                                        <b>{{ $permission['display_name'] }}</b><br>
                                                    </td>
                                                    <td class="small">{{ $permission['description'] }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach
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
@endsection

@section('footer')
    <script type="text/javascript">

        var Settings = {

            showAddRoleModal: function(hasOldSession){

                $('#roleForm').attr('action', '{{ route('admin.roles.store') }}');
                $('#roleModalTitle').html('Add Role');
                $('#roleFormMethod').val('POST');

                if(hasOldSession)
                {
                    $('#roleId').val('{{ old('roleId', '') }}');
                    $('#roleName').val('{{ old('roleName', '') }}');
                    $('#roleDescription').val('{{ old('roleDescription', '') }}');
                }
                else
                {
                    $('.alert').hide();
                    $('#roleId').val('');
                    $('#roleName').val('');
                    $('#roleDescription').val('');
                    $('.permissionCheckbox').prop('checked', false);
                }



                $('#addRoleModal').modal('show');

            },

            showEditRoleModal: function(id, hasOldSession){

                $.post('{{ route('admin.roles.show') }}', {id: id, _token: '{{ csrf_token() }}'})
                    .done(function(d){



                        $('#roleForm').attr('action', '{{ route('admin.roles.update') }}');
                        $('#roleModalTitle').html('Edit Role');
                        $('#roleFormMethod').val('PUT');

                        $('.permissionCheckbox').prop('checked', false);

                        if(hasOldSession)
                        {
                            $('#roleId').val('{{ old('roleId') }}');
                            $('#roleName').val('{{ old('roleName') }}');
                            $('#roleDescription').val('{{ old('roleDescription') }}');
                        }
                        else
                        {
                            $('.alert').hide();
                            $('#roleId').val(d['role']['name']);
                            $('#roleName').val(d['role']['display_name']);
                            $('#roleDescription').val(d['role']['description']);
                        }

                        $('#roleRecordId').val(id);

                        for(var x = 0; x < d['permissions'].length; x++)
                        {
                            $('[name=permission_' + d['permissions'][x] + ']').prop('checked', true);
                        }

                        $('#addRoleModal').modal('show');

                    });

            },

            createRolesAndPermissionsButtonListeners: function(){

                $('#create-role-button').click(Settings.showAddRoleModal.bind(null, false));

                $('.edit-role-button').click(function(){

                    Settings.showEditRoleModal($(this).attr('data-id'), false);

                });

            },

            createCheckAllListener: function(){

                $('#checkAllPermissions').on('change', function(){

                    if($(this).prop('checked'))
                    {
                        Settings.checkAllPermissions(true);
                    }
                    else
                    {
                        Settings.checkAllPermissions(false);
                    }

                });

            },

            checkAllPermissions: function(check){

                $('.permissionCheckbox').prop('checked', check);

            },

            created: function(){

                this.createRolesAndPermissionsButtonListeners();
                this.createCheckAllListener();

                if(window.location.hash === '#addRole')
                {
                    this.showAddRoleModal(true);
                }
                else if(window.location.hash === '#editRole')
                {
                    @if(old('id'))
                        Settings.showEditRoleModal('{{ old('id') }}', true);
                    @endif
                }

            }

        };

        (function(){

            Settings.created();

        })();

    </script>
@stop

