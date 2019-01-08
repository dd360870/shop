<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $bindings = [
            'name'
        ];
        return view('user.index', $user);
    }

    public function changePassword()
    {
        return view('user.changePassword');
    }

    public function changePasswordProcess(Request $request)
    {
        $user = Auth::user();
        if(!Hash::check($request['old-password'], $user->password)) {
            return redirect(route('user.changePassword'))->withErrors(['old-password' => 'Old Password is wrong. Please type again']);
        }
        Validator::make($request->all(), [
            'new-password' => 'required | min:6',
            'new-password-confirmation' => 'same:new-password',
        ])->validate();

        //success, redirect to relogin
        Auth::logout();
        $message = [
            'type' => 'success',
            'message' => 'Password changed successfully, login with your new password.',
        ];
        return view('auth.login', [
            'alert' => $message,
            'email' => $user->email,
        ]);
    }
}
