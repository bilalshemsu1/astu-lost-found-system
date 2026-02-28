<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Manual validation with custom messages
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:30|min:9',
            'student_id' => 'required|string|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return back()
                ->withErrors($validator);
        }

        // If passed: proceed
        $validated = $validator->validated();
        $validated['password'] = bcrypt($validated['password']);
        
        $user = User::create($validated);
        if(!$user){
            return back()
                ->with('error', 'Registration Failed! Try Again!');
        }

        return redirect()->route('login')->with('success', 'Account Created Successfully! Please Login!');
    }


    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Manual validation with custom messages
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return back()
                ->withErrors($validator);
        }

        $credentials = $validator->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if(auth::user()->role === 'admin'){
                return redirect()->route('admin.dashboard');
            }
            
            return redirect()->route('student.dashboard');
        }

        return back()
            ->withInput()
            ->with('error', 'Invalid email or password!');
    }  

    public function logout(Request $request)
    {
        auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'Logged out successfully!');
    }
}
