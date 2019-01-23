@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            @if (isset($alert) || $alert=Session::get('alert', false))
                @component('components.alert', ['type' => $alert['type']])
                    {{ $alert['message'] }}
                @endcomponent
            @endif
            <div class="border-bottom border-left border-right p-3" style="text-align:right;">
                @if ($orders->isEmpty())
                    <h1 style="text-align:center;">No orders.</h1>
                @else
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Merchandises</th>
                                <th>Time</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($orders as $o)
                            <tr>
                                <td>
                                    @foreach ($o->items as $item)
                                        {{ $item->merchandise->name.'*'.$item->amount }}<br>
                                    @endforeach
                                </td>
                                <td>{{ $o->created_at }}</td>
                                <td>{{ $o->total }}</td>
                                <td>{{ $o->status }}</td>
                                <td><a class="btn btn-secondary" role="button" href="/order/{{ $o->id }}">detail</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
</script>    
@endsection