<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Check either username or email.
     * @return string
     */


    public function getLogin(Request $request)
    {
        return view('login');
    }

    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->get('remember');
//        $credentials = $request->only('email', 'password');

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember))
        {
            //Authentication passed...
            return redirect()->intended('home');
        }

        return back()->withInput()->with('message', 'Login Failed');

    }

}
