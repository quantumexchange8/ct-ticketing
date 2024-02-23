<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function login()
    {
        return view('auth.adminLogin');
    }

    public function loginPost(Request $request)
    {
        // dd($request->all());
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        $messages = [
            'username.required' => 'Username is required',
            'password.required' => 'Password is required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $credentials = [
            'username' => $request->input('username'),
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials)) {
            // $user = Auth::user();
            return redirect()->route('adminDashboard');
        } else {
            return redirect()->back()->with('error', 'Incorrect username or password.');
        }

    }

    public function logout()
    {
        Session::flush();
        Auth::logout();

        return redirect('login');
    }
}
