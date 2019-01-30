@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-8 border-bottom border-left border-right p-3">
            @if (isset($alert) || $alert=Session::get('alert', false))
                @component('components.alert', ['type' => $alert['type']])
                    {{ $alert['message'] }}
                @endcomponent
            @endif
            <h2>確認訂單</h2>
            <table class="table table-bordered" style="text-align:right;">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cart as $m)
                        <tr>
                            <td>{{ $m->name }}</td>
                            <td>
                                {{ $m->buyAmount }}
                                @if (! $m->stockEnough)
                                    <span style="color:red; font-size:0.7em">庫存數量不足</span>
                                @endif
                            </td>
                            <td>{{ $m->price }}</td>
                        </tr>    
                    @endforeach
                    <tr><td colspan="3">總價： NT$ {{ $total }}</td></tr>
                </tbody>
            </table>
            <table class="table">
                <tbody>
                    <tr>
                        <th>付款方式</th>
                        <td>
                            @switch($payment_method)
                                @case('D')
                                    貨到付款
                                    @break
                                @case('C')
                                    信用卡
                                    @break
                                @case('L')
                                    LINEPAY
                                    @break
                                @case('S')
                                    7-11貨到付款
                                    @break
                                @default
                            @endswitch
                        </td>
                    </tr>
                    <tr>
                        <th>配送方式</th>
                        <td>
                            @switch($delivery_method)
                                @case('S')
                                    7-11取貨
                                    @break
                                @case('D')
                                    宅配
                                    @break
                                @default
                            @endswitch
                        </td>
                    </tr>
                    <tr>
                        <th>姓名 : </th>
                        <td>{{ $delivery_name }}</td>
                    </tr>
                    <tr>
                        <th>連絡電話 : </th>
                        <td>{{ $delivery_phone }}</td>
                    </tr>
                    <tr>
                        <th>地址 : </th>
                        <td>{{ $delivery_address }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="">
                <form action="/shopping-cart/checkout" method="POST" style="text-align:right;">
                    @csrf
                    <div style="display:none;">
                        <input name="payment_method" value="{{ $payment_method }}">
                        <input name="delivery_method" value="{{ $delivery_method }}">
                        <input name="delivery_name" value="{{ $delivery_name }}">
                        <input name="delivery_phone" value="{{ $delivery_phone }}">
                        <input name="delivery_address" value="{{ $delivery_address }}">
                    </div>
                    <button type="submit" class="btn btn-primary" style="display:inline;">Confirm</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
/*$("#myform").validate({
    rules: {
        // no quoting necessary
        name: "required",
        // quoting necessary!
        "user[email]": "email",
        // dots need quoting, too!
        "user.address.street": "required"
    }
});*/
</script>    
@endsection