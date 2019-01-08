@extends('layouts.base')

@section('title', '註冊')

@section('content')

<div class="container">
    <form class="border border-primary rounded p-4 w-50 mt-3" style="margin:auto;">
        <h1>註冊</h1>
        <div class="form-group">
            <label for="InputEmail">Email address</label>
            <input type="email" class="form-control" id="InputEmail" aria-describedby="emailHelp" placeholder="Email" required>
            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
        </div>
        <div class="form-group">
            <label for="InputPassword">Password</label>
            <input type="password" class="form-control" id="InputPassword" placeholder="Password" required>
        </div>
        <div class="form-group">
            <label for="InputPassword">Password Confirmation</label>
            <input type="password" class="form-control" id="InputPasswordConfirm" placeholder="Password Confirmation" required>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>
@endsection
