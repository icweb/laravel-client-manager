@extends('layouts.app')

@section('content')
    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <h1>Home</h1>
            {{--<p>Enter at least one field below to search for a client, or select New Client.</p>--}}
            {{--<a class="btn btn-success" href="#" role="button">New Client</a>--}}
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">

                @include('partial-session-alerts')

                <div class="card">
                    <div class="card-header bg-dark text-white">Recent Clients</div>
                    <div>
                        <ul class="list-group">
                            <li class="list-group-item"><a href="">#3001212 - John Smith - 12/31/1969</a></li>
                            <li class="list-group-item"><a href="">#3001212 - John Smith - 12/31/1969</a></li>
                            <li class="list-group-item"><a href="">#3001212 - John Smith - 12/31/1969</a></li>
                            <li class="list-group-item"><a href="">#3001212 - John Smith - 12/31/1969</a></li>
                            <li class="list-group-item"><a href="">#3001212 - John Smith - 12/31/1969</a></li>
                            <li class="list-group-item"><a href="">#3001212 - John Smith - 12/31/1969</a></li>
                            <li class="list-group-item"><a href="">#3001212 - John Smith - 12/31/1969</a></li>
                            <li class="list-group-item"><a href="">#3001212 - John Smith - 12/31/1969</a></li>
                            <li class="list-group-item"><a href="">#3001212 - John Smith - 12/31/1969</a></li>
                            <li class="list-group-item"><a href="">#3001212 - John Smith - 12/31/1969</a></li>
                            <li class="list-group-item"><a href="">#3001212 - John Smith - 12/31/1969</a></li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
