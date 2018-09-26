@extends('layouts.app')

@section('content')

    {{--//?search%5Bcode%5D=theme&search%5Blabel%5D=&search%5Bvalue%5D=--}}

    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            @if(isset($_GET['level']))
                <h1>{{ $_GET['level'] }}</h1>
            @else
                <h1>System Settings</h1>
            @endif
            <p>Settings here will effect the entire application</p>
            <a href="{{ route('admin.index') }}" class="btn btn-secondary"><em class="fa fa-angle-left"></em> Return to Admin</a>
            <div class="btn-group">
                <a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-plus-square-o" aria-hidden="true"></i> New Setting <span
                            class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    @foreach($types as $key => $type)
                        <li><a href="{{ url(config('settings.route').'/create?type='.$key) }}">{{ $type }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-dark">
                            <tr class="">
                                <th>Code</th>
                                <th>Type</th>
                                <th>Label</th>
                                <th>Value</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($settings as $setting)
                                <tr id="tr_{{ $setting->id }}" class="{{ $setting->hidden?'warning':'' }}">
                                    <td>{{ $setting->code }}</td>
                                    <td>{{ $setting->type }}</td>
                                    <td>{{ $setting->label }}</td>
                                    <td>{{ str_limit($setting->getOriginal('value'),50) }}</td>
                                    <td class="text-right">
                                        <a href="{{ url(config('settings.route') . '/' . $setting->id . '/edit') }}"
                                           class="text-primary">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <i class="fa fa-info-circle" aria-hidden="true"></i> No settings found.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                            {{--<tfoot>--}}
                            {{--<tr>--}}
                            {{--<td colspan="5" class="text-right">--}}
                            {{--{{ $settings->appends(\Request::except('page'))->links() }}--}}
                            {{--</td>--}}
                            {{--</tr>--}}
                            {{--</tfoot>--}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            $('[data-toggle=confirmation]').confirmation({
                rootSelector: '[data-toggle=confirmation]',
                onConfirm: function (event, element) {
                    element.trigger('confirm');
                }
                // other options
            });

            $(document).on('confirm', function (e) {
                var ele = e.target;
                e.preventDefault();

                $.ajax({
                    url: ele.href,
                    type: 'DELETE',
                    headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken},
                    success: function (data) {
                        if (data['success']) {
                            toastr.success(data['success'], 'Success');
                            $("#" + data['tr']).slideUp("slow");
                        } else if (data['error']) {
                            toastr.error(data['error'], 'Error');
                        } else {
                            toastr.error('Whoops Something went wrong!!', 'Error');
                        }
                    },
                    error: function (data) {
                        alert(data.responseText);
                    }
                });

                return false;
            });
        });
    </script>
@endsection