<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\CheckConfirmPasswordRule;

use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    private $client;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = DB::table('oauth_clients')->where('id', 2)->first();
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
            return redirect()->intended(route('user.home'));
        }

        return back()->withInput()->with('message', 'Login Failed');
    }

    public function getLogout(Request $request)
    {
        Auth::guard('web')->logout();
        $token = \Cookie::forget('token');
        return redirect()->route('login')->withCookie($token);;
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
//            DB::transaction();
//            try {
                $request->request->add([
                    'grant_type'    => 'password',
                    'client_id'     => 2,
                    'client_secret' => 'biYMUOnz0tolMnyWQvSmQUpltx5nYjviYyChPep7',
                    'username'      => $request->get('email'),
                    'password'      => $request->get('password'),
                    'scope'         => null,
                ]);

                // Fire off the internal request.
                $proxy = Request::create(
                    'oauth/token',
                    'POST'
                );
                $response = json_decode( \Route::dispatch($proxy)->getContent());
                $token = $response->access_token;
                setcookie('token', $token, time() + (60 * 30), "/");
                return redirect()->route('login');
                DB::commit();
//            } catch (\Exception $e) {
//                DB::rollBack();
//            }
        } else {
            return redirect()->route('login');
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
