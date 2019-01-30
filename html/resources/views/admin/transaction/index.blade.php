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
                    @foreach ($orders as $o)
                        <div class="card mb-3">
                            <div class="card-header" style="display:none;">
                                Featured
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered" style="max-width:49%; display:inline-table">
                                    <thead>
                                        <tr>
                                            <th>商品</th>
                                            <th>ID</th>
                                            <th>數量</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($o->items as $i)
                                            <tr>
                                                <td>{{ $i->merchandiseInventory->merchandise->name }}</td>
                                                <td>{{ $i->product_id }}</td>
                                                <td>{{ $i->amount }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <table class="table table-bordered" style="max-width:49%; display:inline-table;">
                                    <thead>
                                    </thead>
                                    <tbody>
                                        <tr><th>付款方式</th><td>{{ Config::get('constants.pay_method')[$o->pay_method] }}</td></tr>
                                        <tr><th>付款狀態</th><td style="color:white; background-color:{{ $o->paid ? 'olive':'tomato' }}">{{ $o->paid ? '已付款':'待付款' }}</td></tr>
                                        <tr><th>寄送方式</th><td>{{ Config::get('constants.delivery_method')[$o->delivery_method] }}</td></tr>
                                        <tr><th>收件人姓名</th><td>{{ $o->delivery_name }}</td></tr>
                                        <tr><th>收件人電話</th><td>{{ $o->delivery_phone }}</td></tr>
                                        <tr><th>收件人地址</th><td>{{ $o->delivery_address }}</td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">Footer</div>
                        </div>
                    @endforeach
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
