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
                <h1>HOME PAGE</h1>
            </div>
        </div>
    </div>
</div>
@endsection
