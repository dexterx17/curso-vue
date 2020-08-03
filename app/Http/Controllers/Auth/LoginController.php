<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    

    use AuthenticatesUsers;


    protected $redirectTo = RouteServiceProvider::HOME;


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }*/

    protected $redirectTo = 'main';


    public function showLoginForm(){
        return view('auth.login');
    }

    public function login(Request $request){
        
        $this->validateLogin($request);

        if(Auth::attempt(['usuario'=> $request->usuario,'password'=> $request->password,'condicion'=>1])){
            return redirect()->route('main');
        }
        
        return back()->withErrors([trans('auth.failed')]);
    }

    protected function validateLogin(Request $request){
        $request->validate([
            'usuario' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        return redirect('/');
    }
}
