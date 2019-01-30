<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        if($request->has('input')) {
            $users = User::where($request->column_name, 'like', '%'.$request->input.'%')->get();
        } else {
            $users = User::all();
        }
        $binding = [
            'users' => $users,
        ];
        return view('admin.user.index', $binding);
    }

    public function add(Request $request)
    {
        Validator::make($request->all(), [
            'email' => 'required | email',
        ])->validate();

        $User = User::where('email', $request->email)->first();
        if(empty($User)) {
            $binding = [
                'users' => User::all(),
                'alert' => [
                    'type' => 'danger',
                    'message' => 'User not found !',
                ],
            ];
            return view('admin.user.index', $binding);
        }
        if($request->method == 'add') {
            $User->is_admin = true;
        } else {
            $User->is_admin = false;
        }
        $User->save();

        $binding = [
            'users' => User::all(),
            'alert' => [
                'type' => 'success',
                'message' => 'User '.$User->name.'('.$User->email.') successfully be added to Admin.',
            ],
        ];
        return view('admin.user.index', $binding);
    }
}