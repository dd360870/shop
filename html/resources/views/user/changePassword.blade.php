@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">@lang('Change Password')</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('user.changePassword') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="old-password" class="col-md-4 col-form-label text-md-right">{{ __('Old Password') }}</label>

                            <div class="col-md-6">
                                <input id="old-password" type="password" class="form-control{{ $errors->has('old-password') ? ' is-invalid' : '' }}" name="old-password" value="" required>

                                @if ($errors->has('old-password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('old-password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="new-password" class="col-md-4 col-form-label text-md-right">{{ __('New Password') }}</label>

                            <div class="col-md-6">
                                <input id="new-password" type="password" class="form-control{{ $errors->has('new-password') ? ' is-invalid' : '' }}" name="new-password" value="" required>

                                @if ($errors->has('new-password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('new-password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="new-password-confirmation" class="col-md-4 col-form-label text-md-right">@lang('New Password Confirmation')</label>

                            <div class="col-md-6">
                                <input id="new-password-confirmation" type="password" class="form-control{{ $errors->has('new-password-confirmation') ? ' is-invalid' : '' }}" name="new-password-confirmation" value="" required>

                                @if ($errors->has('new-password-confirmation'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('new-password-confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    @lang('Save')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
