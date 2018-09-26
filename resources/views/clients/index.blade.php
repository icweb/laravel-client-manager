@extends('layouts.app')

@section('content')
    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <h1>Search Clients</h1>
            <p>Enter at least one field below to search for a client, or select the  New Client button to create a new record.</p>
            @permission('create_clients')
                <button class="btn btn-primary" type="button" id="add-client-button" role="button"><em class="fa fa-user-plus"></em> New Client</button>
            @endpermission
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">

                @include('partial-session-alerts')

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('clients.search') }}" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="searchFirstName">First Name</label>
                                        <input type="text" class="form-control" id="searchFirstName" name="searchFirstName" aria-describedby="searchFirstName" placeholder="Enter First Name" value="{{ old('searchFirstName') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="searchLastName">Last Name</label>
                                        <input type="text" class="form-control" id="searchLastName" name="searchLastName" aria-describedby="searchLastName" placeholder="Enter Last Name" value="{{ old('searchLastName') }}">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="searchBirthday">Date of Birth</label>
                                        <input type="date" class="form-control" id="searchBirthday" name="searchBirthday" aria-describedby="searchBirthday" placeholder="Enter Birthday">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="searchChartNumber">Chart Number</label>
                                        <input type="text" class="form-control" id="searchChartNumber" name="searchChartNumber" aria-describedby="searchChartNumber" placeholder="Enter Chart Number" value="{{ old('searchChartNumber') }}">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="searchGender">Gender</label>
                                        <select name="searchGender" id="searchGender" class="form-control">
                                            <option disabled selected>Select a Gender</option>
                                            @foreach(\App\AppList::items('genders') as $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 text-right">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @if(session('clients'))
                    <div class="alert alert-success fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ count(session('clients')) }} result(s) found
                    </div>
                @endif

               @if(session('clients') && count(session('clients')) > 0)
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-dark">
                                <tr>
                                    <th>Chart Number</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Birthday</th>
                                    <th>Gender</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach(session('clients') as $client)
                                    <tr>
                                        <td>{{ $client->chart_number }}</td>
                                        <td>{{ $client->legal_first_name }}</td>
                                        <td>{{ $client->legal_last_name }}</td>
                                        <td>{{ $client->date_of_birth->format('Y-m-d') }}</td>
                                        <td>{{ $client->gender }}</td>
                                        <td class="text-right">
                                            <a href="{{ route('clients.show', [$client->id, 'alerts']) }}">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
               @endif

            </div>
        </div>
    </div>
    @permission('create_clients')
        <div class="modal fade" id="addClientModal" tabindex="-1" role="dialog" aria-labelledby="addClientModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <form action="{{ route('clients.store') }}" method="post">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Client</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            @include('partial-session-alerts')

                            @csrf

                            <div class="form-row">

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="firstName">First Name <span class="text-danger required"><b>*</b></span></label>
                                        <input type="text" class="form-control" name="firstName" id="firstName" aria-describedby="firstName" placeholder="Enter First Name" value="{{ old('firstName') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="lastName">Last Name <span class="text-danger required"><b>*</b></span></label>
                                        <input type="text" class="form-control" name="lastName" id="lastName" aria-describedby="lastName" placeholder="Enter Last Name" value="{{ old('lastName') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="birthday">Birthday <span class="text-danger required"><b>*</b></span></label>
                                        <input type="date" class="form-control" name="birthday" id="birthday" aria-describedby="birthday" placeholder="Enter Birthday" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="gender">Gender <span class="text-danger required"><b>*</b></span></label>
                                        <select name="gender" id="gender" name="genders" class="form-control" required>
                                            <option selected disabled>Select One</option>
                                            @foreach(\App\AppList::items('genders') as $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary"><em class="fa fa-save"></em> Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endpermission
@endsection

@section('footer')
    <script type="text/javascript">

        var Clients = {

            showAddClientModal: function(hasOldSession){

                $('#addClientModal').modal('show');

                if(!hasOldSession)
                {
                    $('.alert').hide();
                    $('#firstName').val('');
                    $('#lastName').val('');
                    $('#birthday').val('');
                    $('#chartNumber').val('');
                }

            },

            createAddClientButtonListener: function(){

                $('#add-client-button').click(Clients.showAddClientModal.bind(null, false));

            },

            created: function(){

                this.createAddClientButtonListener();

                if(window.location.hash === '#addClient')
                {
                    Clients.showAddClientModal(true);
                }

                @if(old('gender'))
                    $('#gender').val('{{ old('gender') }}');
                @endif

                @if(old('birthday'))
                    $('#birthday').val('{{ date('Y-m-d', strtotime(old('birthday'))) }}');
                @endif

            }

        };

        (function(){

            Clients.created();

        })();

    </script>
@stop

