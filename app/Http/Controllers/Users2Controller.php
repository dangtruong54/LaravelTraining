<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class Users2Controller extends Controller
{

    public function getLogin(Request $request)
    {
        return view('login2');
    }

    public function postLogin(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->get('remember');

        if(Auth::guard('web1')->attempt(['email' => $request->email, 'password' => $request->password], $remember))
        {
            //Authentication passed...
            return redirect()->intended('home');
        }

        return back()->withInput()->with('message', 'Login Failed');
    }
}
