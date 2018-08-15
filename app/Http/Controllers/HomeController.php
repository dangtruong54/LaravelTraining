<?php

namespace App\Http\Controllers;

use App\Http\Requests\User as UserRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            return view('home');
        }else {
            return view('login');
        }

    }

    public function getRegister(Request $request)
    {
        return view('register');
    }

    public function postRegister(UserRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] =  bcrypt($validated['password']);
        if(User::create($validated)){
            return redirect()->route('register');
        }else{
            dd($request);
        }

    }
}
