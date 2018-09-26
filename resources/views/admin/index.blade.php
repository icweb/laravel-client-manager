@extends('layouts.app')

@section('content')
    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <h1>Admin</h1>
            <p>Settings here will effect the entire application</p>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header bg-dark text-white">Roles & Permission</div>
                    <div class="card-body">
                        <a href="{{ route('admin.roles.index') }}">Manage Roles</a><br>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-header bg-dark text-white">Site Settings</div>
                    <div class="card-body">
                        <a href="/settings?search%5Bcode%5D=theme_&search%5Blabel%5D=&search%5Bvalue%5D=&level=Theme%20Settings" id="create-role-button">Manage Theme</a><br>
                        <a href="/settings?search%5Bcode%5D=mail_&search%5Blabel%5D=&search%5Bvalue%5D=&level=Mail%20Settings" id="create-role-button">Manage Email Settings</a><br>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script type="text/javascript">

        var Settings = {

            created: function(){


            }

        };

        (function(){

            Settings.created();

        })();

    </script>
@stop

