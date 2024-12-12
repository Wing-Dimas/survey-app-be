<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegistrationRequest;
use App\Models\ApiKey;
use App\Models\FormSubmission;
use App\Models\Response;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        if(Auth::check()){
            return redirect("/dashboard");
        }
        if($this->noRegisteredUsers()){
            return redirect()->route('auth.show.signup');
        }else{
            return redirect()->route('auth.show.signin');
        }
    }

    public function dashboard()
    {
        if(!Auth::check()){
            return redirect()->route('auth.show.signin');
        }

        $applications = ApiKey::count();
        $responses = Response::count();
        $formSubmissions = FormSubmission::count();

        return view('pages.index', compact(
            'applications',
            'responses',
            'formSubmissions'
        ));
    }

    public function showLogin()
    {
        if(Auth::check()) return redirect("/dashboard");

        if($this->noRegisteredUsers()) return redirect()->route('auth.show.signup');

        return view('pages.auth.signin');
    }

    public function showRegristration()
    {
        if($this->hasRegisteredUsers()) return redirect()->route('auth.show.signin');

        return view('pages.auth.signup');
    }

    public function signin(UserLoginRequest $request)
    {
        if(Auth::check()) return redirect("/");

        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    public function signup(UserRegistrationRequest $request)
    {
        $data = $request->validated();

        User::create($data);

        return redirect()->route('auth.show.signin');
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('auth.show.signin');
    }

    protected function noRegisteredUsers()
    {
        return !User::exists();
    }

    protected function hasRegisteredUsers()
    {
        return User::exists();
    }

}
