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
            <div class="border-bottom border-left border-right p-3" style="text-align:right; overflow:auto;">
                <div style="overflow: auto;">
                    <a role="button" class="btn btn-secondary mb-3" style="float:left;" href="/order">
                        <i class="material-icons align-middle">
                            arrow_back
                        </i>
                        <span class="align-middle" style="font-size: 1.1em;">My Orders</span>
                    </a>
                </div>
                <h2 style="text-align:center;">商品清單</h2>
                <table class="table table-bordered" style="max-width:500px; margin:auto;">
                    <thead>
                        <tr>
                            <th>Merchandises</th>
                            <th>Product ID</th>
                            <th>Amount/Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($Order->items as $i)
                        <tr>
                            <td>{{ $i->merchandiseInventory->merchandise->name }}</td>
                            <td>{{ $i->product_id }}</td>
                            <td>{{ $i->amount.'*'.$i->price }}</td>
                            <td>{{ $i->price * $i->amount }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <h2 style="text-align:center;">寄送資訊</h2>
                <table class="table table-bordered" style="max-width:500px; margin:auto;">
                    <tbody>
                        <tr>
                            <th>配送方式</th>
                            <td>{{ Config::get('constants.delivery_method')[$Order->delivery_method] }}</td>
                        </tr>
                        <tr>
                            <th>收件人：</th>
                            <td>{{ $Order->delivery_name }}</td>
                        </tr>
                        <tr>
                            <th>電話：</th>
                            <td>{{ $Order->delivery_phone }}</td>
                        </tr>
                        <tr>
                            <th>地址：</th>
                            <td>{{ $Order->delivery_address }}</td>
                        </tr>
                    </tbody>
                </table>
                <h2 style="text-align:center;">付款資訊</h2>
                <table class="table table-bordered" style="max-width:500px; margin:auto;">
                    <tbody>
                        <tr>
                            <th>付款方法：</th>
                            <td>{{ Config::get('constants.payment_method')[$Order->payment_method] }}</td>
                        </tr>
                        <tr>
                            <th>付款狀態：</th>
                            <td>{{ Config::get('constants.payment_status')[$Order->payment_status] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
</script>    
@endsection