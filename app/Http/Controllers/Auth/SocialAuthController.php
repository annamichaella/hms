<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    /**
     * Redirect to Facebook for authentication
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Handle Facebook callback
     */
    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();
            
            // Check if user exists by provider
            $user = User::where('provider', 'facebook')
                        ->where('provider_id', $facebookUser->getId())
                        ->first();

            if ($user) {
                // User exists, log them in
                Auth::login($user);
            } else {
                // Check if user exists by email
                $existingUser = User::where('email', $facebookUser->getEmail())->first();

                if ($existingUser) {
                    // Link Facebook account to existing user
                    $existingUser->provider = 'facebook';
                    $existingUser->provider_id = $facebookUser->getId();
                    $existingUser->save();
                    Auth::login($existingUser);
                } else {
                    // Create new user from Facebook data
                    $name = $facebookUser->getName();
                    $nameParts = $this->parseName($name);

                    $user = User::create([
                        'fname' => $nameParts['first'],
                        'mname' => $nameParts['middle'],
                        'lname' => $nameParts['last'],
                        'email' => $facebookUser->getEmail(),
                        'password' => bcrypt(Str::random(16)), // Random password for social login
                        'role' => 'patient', // Default role
                        'provider' => 'facebook',
                        'provider_id' => $facebookUser->getId(),
                        'email_verified_at' => now(),
                    ]);

                    Auth::login($user);
                }
            }

            // Redirect based on user role
            return $this->redirectBasedOnRole(Auth::user());
            
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Failed to authenticate with Facebook. Please try again.',
            ]);
        }
    }

    /**
     * Redirect to Google for authentication
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user exists by provider
            $user = User::where('provider', 'google')
                        ->where('provider_id', $googleUser->getId())
                        ->first();

            if ($user) {
                // User exists, log them in
                Auth::login($user);
            } else {
                // Check if user exists by email
                $existingUser = User::where('email', $googleUser->getEmail())->first();

                if ($existingUser) {
                    // Link Google account to existing user
                    $existingUser->provider = 'google';
                    $existingUser->provider_id = $googleUser->getId();
                    $existingUser->save();
                    Auth::login($existingUser);
                } else {
                    // Create new user from Google data
                    $name = $googleUser->getName();
                    $nameParts = $this->parseName($name);

                    $user = User::create([
                        'fname' => $nameParts['first'],
                        'mname' => $nameParts['middle'],
                        'lname' => $nameParts['last'],
                        'email' => $googleUser->getEmail(),
                        'password' => bcrypt(Str::random(16)), // Random password for social login
                        'role' => 'patient', // Default role
                        'provider' => 'google',
                        'provider_id' => $googleUser->getId(),
                        'email_verified_at' => now(),
                    ]);

                    Auth::login($user);
                }
            }

            // Redirect based on user role
            return $this->redirectBasedOnRole(Auth::user());
            
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Failed to authenticate with Google: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Parse full name into first, middle, and last name
     */
    private function parseName($fullName)
    {
        $parts = explode(' ', trim($fullName));
        
        $result = [
            'first' => $parts[0] ?? '',
            'middle' => '',
            'last' => '',
        ];

        if (count($parts) > 1) {
            $result['last'] = end($parts);
            
            if (count($parts) > 2) {
                $result['middle'] = implode(' ', array_slice($parts, 1, -1));
            }
        }

        return $result;
    }

    /**
     * Redirect user based on their role
     */
    private function redirectBasedOnRole($user)
    {
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
