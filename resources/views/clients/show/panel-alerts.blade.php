<div>
    <h4>Alerts</h4>
    <a href="#" class="btn btn-sm btn-success"><em class="fa fa-plus"></em> Add</a>
    <br><br>
    <div class="card">
        <div>
            @if(count($client->alerts))
                <table class="table">
                    @foreach($client->alerts as $item)
                        <tr>
                            <td class="text-warning" style="width: 30px"><b><em class="fa fa-{{ $item['icon'] }}"></em></b></td>
                            <td class="text-warning"><b>{{ $item['title'] }}</b></td>
                            <td>{{ $item['message'] }}</td>
                            <td class="text-right">
                                @if($item['fixIt'])
                                    <a href="{{ $item['fixIt'] }}">Fix</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            @else
                All clear!
            @endif
        </div>
    </div>
</div>