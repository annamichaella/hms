<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:100',
            'mname' => 'nullable|string|max:200',
            'lname' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'fname' => $request->fname,
            'mname' => $request->mname,
            'lname' => $request->lname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'patient', // Default role for registration
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        Auth::login($user);

        // Redirect based on user role
        switch ($user->role) {
            case 'admin':
                return redirect()->intended('/admin');
            case 'doctor':
                return redirect()->intended('/doctor');
            case 'nurse':
                return redirect()->intended('/nurse');
            case 'staff':
                return redirect()->intended('/staff');
            case 'patient':
                return redirect()->intended('/patient');
            default:
                return redirect()->intended('/');
        }
    }
}
