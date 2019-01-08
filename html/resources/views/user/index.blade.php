@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Settings</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <form>
                        <div class="form-group row">
                            <img style="margin:auto;max-height:20vh;" src="default-user.png">
                        </div>
                        <div class="form-group row">
                            <label for="userName" class="col-sm-2 col-form-label">@lang('Name')</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control-plaintext" id="userName" value="{{ $name }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="userEmail" class="col-sm-2 col-form-label">@lang('Email')</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control-plaintext" readonly id="userEmail" value="{{ $email }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="userPassword" class="col-sm-2 col-form-label">@lang('Password')</label>
                            <div class="col-sm-7">
                                <input type="password" class="form-control-plaintext" readonly id="userPassword" value="************">
                            </div>
                            <a class="btn btn-primary" href="/user/change-password" role="button">@lang('Change Password')</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
