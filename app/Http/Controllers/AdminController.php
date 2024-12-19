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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    /**
     * Display the dashboard.
     *
     * If the user is not signed in, redirect to the signin page.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
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

    /**
     * Display the signin page.
     *
     * If the user is already signed in, redirect to the dashboard.
     *
     * If there are no registered users, redirect to the signup page.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showLogin()
    {
        if(Auth::check()) return redirect("/dashboard");

        if($this->noRegisteredUsers()) return redirect()->route('auth.show.signup');

        return view('pages.auth.signin');
    }

    /**
     * Display the registration page.
     *
     * If the user is already registered, redirect to the signin page.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showRegristration()
    {
        if($this->hasRegisteredUsers()) return redirect()->route('auth.show.signin');

        return view('pages.auth.signup');
    }

    /**
     * Sign in the user.
     *
     * @param  \App\Http\Requests\UserLoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function signin(UserLoginRequest $request)
    {
        Log::info("User attempting to sign in", ["email" => $request->email]);

        if(Auth::check()) return redirect("/");

        $request->authenticate();

        $request->session()->regenerate();

        Log::info("User signed in", ["email" => $request->email]);
        return redirect()->intended('/dashboard');
    }

    /**
     * Register a new user
     *
     * @param  \App\Http\Requests\UserRegistrationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function signup(UserRegistrationRequest $request)
    {
        DB::beginTransaction();
        try{
            Log::info("User attempting to register", $request->except('password'));

            $data = $request->validated();

            User::create($data);

            DB::commit();
            Log::info("User registered", $request->except('password'));
            return redirect()->route('auth.show.signin');
        }catch (\Throwable $th) {
            DB::rollBack();
            Log::error(flattenError($th), $request->except('password'));
            return redirect()->route('auth.show.signup');
        }
    }

    /**
     * Logout the current user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Log::info("User attempting to sign out");

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        Log::info("User signed out");
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
