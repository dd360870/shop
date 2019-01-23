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
                <div>
                    <a role="button" class="btn btn-secondary mb-3" style="float:left;" href="/order">
                        <i class="material-icons align-middle">
                            arrow_back
                        </i>
                        <span class="align-middle" style="font-size: 1.1em;">My Orders</span>
                    </a>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Merchandises</th>
                            <th>Amount/Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($Order->items as $i)
                        <tr>
                            <td>{{ $i->merchandise->name }}</td>
                            <td>{{ $i->amount.'*'.$i->price }}</td>
                            <td>{{ $i->merchandise->price * $i->amount }}</td>
                        </tr>
                    @endforeach
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