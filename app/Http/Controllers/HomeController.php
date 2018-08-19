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

    public function index()
    {
        dd(123);
    }

    public function getRegister(Request $request)
    {
        return view('register');
    }

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

    public function getLogout(Request $request)
    {
        Auth::logout();
        return redirect()->intended('login');
    }

    public function postDelete($id)
    {
        User::find($id)->delete();
        return redirect('home')->with('success','Information has been  deleted');
    }

    public function getEdit($id)
    {
        $user = User::find($id);
        return view('user_edit',compact('user'));
    }

    public function postEdit(Request $request, $id)
    {

        $user = User::find($id);
        $user->username = $request->get('username');
        $user->email = $request->get('email');
        $date = date('Y/m/d h:i:s a', time());
        $user->updated_at = strtotime($date);

        $user->save();

        return $this->index();
    }

    public function postRegister(UserRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] =  bcrypt($validated['password']);
        if(User::create($validated)){
            return redirect()->route('home');
        }else{
            dd($request);
        }
    }
}
