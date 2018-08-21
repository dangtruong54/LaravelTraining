<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\CheckConfirmPasswordRule;

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
        $users = User::all();
        return view('home', ['users' => $users]);
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

        if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
            $request->session()->put('email_login', $request->email);
            return redirect()->intended(route('user.home'));
        }

        return back()->withInput()->with('message', 'Login Failed');

    }

    public function getLogout(Request $request)
    {
        Auth::guard('web')->logout();
        return redirect()->route('login');
    }

    public function postDelete($id)
    {
        User::find($id)->delete();
        return redirect()->intended(route('user.home'))->with('success', 'Information has been  deleted');
    }

    public function getEdit($id)
    {
        $user = User::find($id);
        return view('user_edit', compact('user'));
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

    public function postRegister(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required',
            'username' => 'required',
            'password' => [
                'required',
                new CheckConfirmPasswordRule($request)
            ],
            'password_confirmation' => 'required',
        ]);

        $validated['password'] = bcrypt($validated['password']);


        if (User::create($validated)) {
            return redirect()->route('user.home');
        } else {
            dd($request);
        }
    }

    public function getSearch()
    {

    }

    public function postSearch(Request $request)
    {
        $name = $request->get('name');
        $users = User::where('username', 'like', '%' . $name . '%')
            ->limit(3)
            ->get();
        return $users;
    }
}
