<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginRegisterController extends BaseController
{
    /*
     * Instantiate a new LoginRegisterController instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except([
            'logout', 'dashboard'
        ]);
    }

    /*
     * Display a registration form.
     */
    public function register()
    {
        return view('auth.register');
    }

    /*
     * Store a new user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $user = Auth::user();

        // Retrieve additional user details from the database
        $userDetails = User::select('id', 'name', 'email')->find($user->id);

        // Start a session and store user ID and name
        $request->session()->put('user', [
            'id' => $userDetails->id,
            'name' => $userDetails->name,
            'email' => $userDetails->email
        ]);
        $request->session()->regenerate();
        return redirect()->route('dashboard')
            ->withSuccess('You have successfully registered & logged in!');
    }

    /*
     * Display a login form.
     */
    public function login()
    {
        return view('auth.login');
    }

    /*
     * Authenticate the user.
     */


    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);


        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::user();

            // Regular user authentication logic
            // Retrieve additional user details from the database
            $userDetails = User::select('id', 'name', 'email')->find($user->id);

            // Start a session and store user ID and name
            $request->session()->put('user', [
                'id' => $userDetails->id,
                'name' => $userDetails->name,
                'email' => $userDetails->email,
            ]);

            $request->session()->regenerate();
            return redirect()->route('dashboard')
                ->withSuccess('You have successfully logged in!');
        } elseif (Auth::guard('agent')->attempt($credentials)) {
            // Agent authentication logic
            $agent = Auth::guard('agent')->user();

            // Retrieve additional agent details from the database
            $agentDetails = Agent::select('id', 'name', 'email')->find($agent->id);

            // Start a session and store agent ID and name
            $request->session()->put('agent', [
                'id' => $agentDetails->id,
                'name' => $agentDetails->name,
                'email' => $agentDetails->email,
            ]);

            $request->session()->regenerate();
            return redirect()->route('agent-dashboard')
                ->withSuccess('You have successfully logged in as a bank agent!');
        }

        return back()->withErrors([
            'email' => 'Your provided credentials do not match in our records.',
        ])->onlyInput('email');
    }



    /*
     * Display a dashboard to authenticated users.
     */
    public function dashboard()
    {
        if (Auth::check()) {
            return view('auth.dashboard');
        }

        return redirect()->route('login')
            ->withErrors([
                'email' => 'Please login to access the dashboard.',
            ])->onlyInput('email');
    }

    public function agent_dashboard(){
        if (Auth::guard('agent')->check()) {
            return view('auth.agent_dashboard');
        }

        return redirect()->route('login')
            ->withErrors([
                'email' => 'Please login to access the dashboard.',
            ])->onlyInput('email');
    }

    /*
     * Log out the user from application.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        if(session('user')){
            $request->session()->forget('user');
        }
        else if(session('agent')){
            $request->session()->forget('agent');
        }
        $request->session()->regenerateToken();
        return redirect()->route('login')
            ->withSuccess('You have logged out successfully!');;
    }
}
