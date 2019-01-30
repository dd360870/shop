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
            <div class="border-bottom border-left border-right p-3">
                <form id="checkout-form" action="/shopping-cart/confirm" method="POST" >
                    @csrf
                    {{-- Delivery Method --}}
                    <div class="form-group border-top pt-2">
                        <label>Delivery Method</label>
                        <div class="form-check">
                            <input class="form-check-input @if ($errors->has('delivery_method')) is-invalid @endif"
                                type="radio" name="delivery_method" value="D"
                                @if (old('delivery_method')=='D') checked @endif>
                            <label class="form-check-label">宅配</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input @if ($errors->has('delivery_method')) is-invalid @endif"
                                type="radio" name="delivery_method" value="S"
                                @if (old('delivery_method')=='S') checked @endif>
                            <label class="form-check-label">7-11取貨</label>
                        </div>
                    </div>
                    {{-- Pay Method --}}
                    <div class="form-group border-top pt-2">
                        <label>Pay Method</label>
                        <div class="form-check">
                            <input class="form-check-input @if ($errors->has('payment_method')) is-invalid @endif"
                                type="radio" name="payment_method" value="D"
                                @if (old('payment_method')=='D') checked @endif>
                            <label class="form-check-label">
                                貨到付款
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input @if ($errors->has('payment_method')) is-invalid @endif"
                                type="radio" name="payment_method" value="C"
                                @if (old('payment_method')=='C') checked @endif>
                            <label class="form-check-label">
                                Credit Card
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input @if ($errors->has('payment_method')) is-invalid @endif"
                                type="radio" name="payment_method" value="L"
                                @if (old('payment_method')=='L') checked @endif>
                            <label class="form-check-label">
                                LINE PAY
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input @if ($errors->has('payment_method')) is-invalid @endif"
                                type="radio" name="payment_method" value="S"
                                @if (old('payment_method')=='S') checked @endif>
                            <label class="form-check-label">
                                7-11取貨付款
                            </label>
                        </div>
                    </div>
                    {{-- Delivery Address --}}
                    <div class="form-group border-top pt-2">
                        <label>Name</label>
                        <input type="text" class="form-control {{ $errors->has('delivery_name') ? 'is-invalid' : null }}"
                            name="delivery_name" value="{{ old('delivery_name') }}">
                        <div class="invalid-feedback">{{ $errors->first('delivery_name') }}</div>
                    </div>
                    
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="number" class="form-control {{ $errors->has('delivery_phone') ? 'is-invalid' : null }}"
                            name="delivery_phone" value="{{ old('delivery_phone') }}">
                        <div class="invalid-feedback">{{ $errors->first('delivery_phone') }}</div>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" class="form-control {{ $errors->has('delivery_address') ? 'is-invalid' : null }}"
                            name="delivery_address" value="{{ old('delivery_address') }}">
                        <div class="invalid-feedback">{{ $errors->first('delivery_address') }}</div>
                    </div>
                    <button type="submit" class="btn btn-primary">Next Step</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js" integrity="sha256-vhi8Tw3fBc+L8T6WvxZ/rmdB0AwBqVDtxc8rkK/Vuhc=" crossorigin="anonymous"></script>
<script type="text/javascript">
$(document).ready(function() {
    jQuery.validator.addMethod("cellphone", function(value, element) {
        return this.optional(element) || /^09[0-9]{8}$/.test(value);
    }, "請輸入正確的電話號碼");
    $("#checkout-form").validate({
        rules: {
            // no quoting necessary
            payment_method: {
                required: true,
            },
            delivery_method:{
                required: true,
            },
            delivery_name: {
                required: true,
            },
            delivery_phone: {
                required: true,
                cellphone: true,
            },
            delivery_address: "required",
        },
        errorPlacement: function(error, element) {
            element.next('.invalid-feedback').html(error);
        },
        validClass: "is-valid",
        errorClass: "is-invalid",
    });
});
</script>    
@endsection