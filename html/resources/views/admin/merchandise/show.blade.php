@extends('admin.base')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            @if(isset($alert) || $alert = Session::get('alert', false))
                @component('components.alert', ['type' => $alert['type']])
                {{ $alert['message'] }}
                @endcomponent
            @endif
            <div class="card">
                <div class="card-header">
                    <a href="/admin">Admin</a>
                    >
                    <a href="/admin/merchandise">Merchandise</a>
                    >
                    <a href="#">{{ $Merchandise->name }}</a>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div style="width:100%;">
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="{{ $Merchandise->photo ? Storage::disk('s3')->url($Merchandise->photo) : secure_url('default-merchandise.jpg') }}"
                                    alt=""
                                    class="img-thumbnail"
                                    style="width:600px; height:width; display:block; margin:auto;">
                            </div>
                            <div class="col-md-6" style="min-height: 300px;">
                                <h2>{{ $Merchandise->name }}</h2>
                                <h5>
                                    {{ $Merchandise->intro }}
                                </h5>
                                <div style="">
                                    <p style="bottom:0; position:absolute; color:gray;">@lang('ID') : {{ sprintf('%08d', $Merchandise->id) }}</p>
                                    <h2 style="bottom:0; right:0; position:absolute; color:red; float:right;">NTD$ <span style="font-size:10vmin;">{{ $Merchandise->price }}</span></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection