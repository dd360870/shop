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
            <div class="border-bottom border-left border-right p-3" id="cart-table" style="text-align:right;">
                @if (empty($cart))
                    <h1 style="text-align: center;">Nothing in your shopping cart :(</h1>
                @else
                    <table class="table table-bordered" style="text-align:right;">
                        <tr>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                        @foreach ($cart as $c)
                            <tr>
                                <td>{{ $c->name }}</td>
                                <td>
                                    <form method="POST" action="/shopping-cart">
                                        <input name="amount" type="text" class="form-control setInput" value="{{ $c->buyAmount }}" style="text-align:center;">
                                        <input name="product_id" value="{{ $c->product_id }}" style="display:none;">
                                        <input name="method" value="set" style="display:none;">
                                    </form>
                                    @if (!$c->stockEnough)
                                        <span style="color:red; font-size:0.7em">庫存數量不足</span>
                                    @endif
                                </td>
                                <td>{{ $c->price }}</td>
                                <td>{{ $c->price * $c->buyAmount }}</td>
                                <td>
                                    <form method="POST" class="deleteForm">
                                        <input name="product_id" value="{{ $c->product_id }}" style="display:none;">
                                        <input name="method" value="set" style="display:none;">
                                        <input name="amount" value="0" style="display:none;">
                                        <button type="submit" style="background-color:transparent; border:transparent; margin:0; padding:0;">
                                            <i class="material-icons" style="color:#C22; cursor:pointer;">delete</i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="5" class="border-right-0">總價： NT$ {{ $total }}</td>
                        </tr>
                    </table>
                    <a href="/shopping-cart/checkout" role="button" class="btn btn-primary" style="">Checkout</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
$('#cart-table').on('change','input.setInput',function(e) {
    submitForm(e, $(this.form));
});
$('#cart-table').on('submit','form.deleteForm',function(e) {
    submitForm(e, $(this));
});
function submitForm(e, form) {
    var url = form.attr('action');

    $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(), // serializes the form's elements.
        success: function(data)
        {
            updateCart();
            refreshCart();
            alert(data); // show response from the php script.
        },
        error: function(jqXHR, textStatus, errorThrown ) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
    e.preventDefault(); // avoid to execute the actual submit of the form.
}
function updateCart() {
    $.ajax({
        type: "GET",
        url: '/shopping-cart/detail',
        //data: form.serialize(), // serializes the form's elements.
        success: function(data)
        {
            console.log(data); // show response from the php script.
            let str = '';
            if(data.count > 0) {
                str = '<table class="table table-bordered" style="text-align:right;">\
                        <tr><th>Name</th>\
                            <th>Amount</th>\
                            <th>Price</th>\
                            <th>Total</th>\
                            <th></th></tr>';
                data.detail.forEach(e => {
                    str += '<tr><td>'+e.name+'</td><td>'
                        +'<form method="POST" action="/shopping-cart">\
                            <input name="amount" type="text" class="form-control setInput" value="'+e.buyAmount+'" style="text-align:center;">\
                            <input name="product_id" value="'+e.product_id+'" style="display:none;">\
                            <input name="method" value="set" style="display:none;">\
                            </form>'+(e.stockEnough?'':'<span style="color:red; font-size:0.7em">庫存數量不足</span>')+'</td><td>'
                        +e.price+'</td><td>'
                        +e.price * e.buyAmount+'</td><td>'
                        +'<form method="POST" class="deleteForm">\
                            <input name="product_id" value="'+e.product_id+'" style="display:none;">\
                            <input name="method" value="set" style="display:none;">\
                            <input name="amount" value="0" style="display:none;">\
                            <button type="submit" style="background-color:transparent; border:transparent; margin:0; padding:0;">\
                                <i class="material-icons" style="color:#C22; cursor:pointer;">delete</i>\
                            </button>\
                        </form></td></tr>';
                });
                str += '<tr><td colspan="5">總價： NT$ '+data.total+'</td></tr></table>';
                str += '<a href="/shopping-cart/checkout" role="button" class="btn btn-primary" style="text-align:right;">Checkout</a>';
            } else {
                str = '<h1 style="text-align: center;">Nothing in your shopping cart :(</h1>';
            }
            $('#cart-table').html(str);
        }
    });
    
}
/*$('.btn-amount-minus').click(function(event) {
    let input = $(event.target).parent().next()[0];
    let id = $(input).next('input')[0].value;
    console.log(id);
    input.value -= 1;
});

$('.btn-amount-add').click(function(event) {
    let input = $(event.target).parent().prev()[0];
    console.log(input);
    let id = $(input).next('input')[0].value;
    console.log(id);
    input.value += 1;
});*/
</script>    
@endsection