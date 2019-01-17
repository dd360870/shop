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
                <table class="table table-bordered">
                    <tr>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Price</th>
                    </tr>
                    @if (empty($cart))
                        <h1>Nothing in your shopping cart :(</h1>
                    @else
                        @foreach ($cart as $c)
                            <tr>
                                <td>{{ $c->name }}</td>
                                <td>{{ $c->amount }}</td>
                                <td>{{ $c->price }}</td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
