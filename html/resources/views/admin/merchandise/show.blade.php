@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
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
                        <img src="{{ $Merchandise->photo ? secure_asset('storage/'.$Merchandise->photo) : secure_url('default-merchandise.jpg') }}"
                            alt=""
                            class="img-thumbnail"
                            style="width:300px; height:300px; object-fit:cover; margin:auto; display:block;">
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-4">y</div>
                            <div class="col-8">s</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection