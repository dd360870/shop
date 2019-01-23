@extends('admin.base')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            @if(isset($alert) || $alert = Session::get('alert', false))
                @component('components.alert', ['type' => $alert['type']])
                    {{ $alert['message'] }}
                @endcomponent
            @endif
            <div>
                <div class="border-bottom border-left border-right p-3">
                    <table class="table table-bordered">
                        <thead>
                            <th>User</th>
                            <th>Merchandises</th>
                            <th>Paid</th>
                            <th>Status</th>
                            <th>Created Time</th>
                        </thead>
                        <tbody>
                            @foreach ($orders as $o)
                                <tr>
                                    <td>{{ $o->user->email }}</td>
                                    <td>
                                        @foreach ($o->items as $i)
                                            {{ $i->merchandise->name.'('.sprintf('%04d', $i->merchandise->id).') * '.$i->amount }}<br>
                                        @endforeach
                                    </td>
                                    <td style="font-weight:bold;">
                                        NT$ {{ $o->total }}
                                        <span style="color:gray; font-size:1.5em;"> / </span>
                                        {{ ([
                                            'L'=>'LINE PAY',
                                            'D'=>'貨到付款',
                                            'S'=>'7-11取貨付款',
                                            'C'=>'信用卡',
                                            ])[$o->pay_method] }}
                                        <span style="color:gray; font-size:1.5em;"> / </span>
                                        <span style="color:{{ $o->paid ? '#3C3' : '#C22' }};">{{ $o->paid ? 'PAID' : 'NOT PAID'}}</span>
                                    </td>
                                    <td>{{ $o->status }}</td>
                                    <td>{{ $o->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

<script type="text/javascript">
    
</script>

@endsection
