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
        // Check if Facebook credentials are configured
        if (empty(config('services.facebook.client_id'))) {
            return redirect()->route('login')->withErrors([
                'email' => 'Facebook login is not configured. Please contact the administrator.',
            ]);
        }

        return Socialite::driver('facebook')
            ->scopes(['email', 'public_profile'])
            ->redirect();
    }

    /**
     * Handle Facebook callback
     */
    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();
            
            // Validate that we have required data
            if (!$facebookUser->getId()) {
                return redirect()->route('login')->withErrors([
                    'email' => 'Failed to retrieve Facebook user information.',
                ]);
            }

            // Get email - Facebook might not always provide it
            $email = $facebookUser->getEmail();
            
            // If email is missing, we can't create/login user
            if (!$email) {
                return redirect()->route('login')->withErrors([
                    'email' => 'Email permission is required. Please authorize email access when logging in with Facebook.',
                ]);
            }
            
            // Check if user exists by provider
            $user = User::where('provider', 'facebook')
                        ->where('provider_id', $facebookUser->getId())
                        ->first();

            if ($user) {
                // User exists, log them in
                Auth::login($user);
            } else {
                // Check if user exists by email
                $existingUser = User::where('email', $email)->first();

                if ($existingUser) {
                    // Link Facebook account to existing user
                    $existingUser->provider = 'facebook';
                    $existingUser->provider_id = $facebookUser->getId();
                    $existingUser->save();
                    Auth::login($existingUser);
                } else {
                    // Create new user from Facebook data
                    $name = $facebookUser->getName() ?? 'User';
                    $nameParts = $this->parseName($name);

                    $user = User::create([
                        'fname' => $nameParts['first'] ?: 'User',
                        'mname' => $nameParts['middle'],
                        'lname' => $nameParts['last'],
                        'email' => $email,
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
            
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            // InvalidStateException occurs when the OAuth state parameter doesn't match
            // This can happen if the session expired or was lost between redirect and callback
            \Log::warning('Facebook InvalidStateException: ' . $e->getMessage());
            
            // Try to get user without state (stateless mode)
            try {
                $facebookUser = Socialite::driver('facebook')->stateless()->user();
                
                // Validate that we have required data
                if (!$facebookUser->getId()) {
                    return redirect()->route('login')->withErrors([
                        'email' => 'Failed to retrieve Facebook user information.',
                    ]);
                }

                // Get email - Facebook might not always provide it
                $email = $facebookUser->getEmail();
                
                // If email is missing, we can't create/login user
                if (!$email) {
                    return redirect()->route('login')->withErrors([
                        'email' => 'Email permission is required. Please authorize email access when logging in with Facebook.',
                    ]);
                }
                
                // Check if user exists by provider
                $user = User::where('provider', 'facebook')
                            ->where('provider_id', $facebookUser->getId())
                            ->first();

                if ($user) {
                    Auth::login($user);
                } else {
                    // Check if user exists by email
                    $existingUser = User::where('email', $email)->first();

                    if ($existingUser) {
                        // Link Facebook account to existing user
                        $existingUser->provider = 'facebook';
                        $existingUser->provider_id = $facebookUser->getId();
                        $existingUser->save();
                        Auth::login($existingUser);
                    } else {
                        // Create new user from Facebook data
                        $name = $facebookUser->getName() ?? 'User';
                        $nameParts = $this->parseName($name);

                        $user = User::create([
                            'fname' => $nameParts['first'] ?: 'User',
                            'mname' => $nameParts['middle'],
                            'lname' => $nameParts['last'],
                            'email' => $email,
                            'password' => bcrypt(Str::random(16)),
                            'role' => 'patient',
                            'provider' => 'facebook',
                            'provider_id' => $facebookUser->getId(),
                            'email_verified_at' => now(),
                        ]);

                        Auth::login($user);
                    }
                }

                return $this->redirectBasedOnRole(Auth::user());
            } catch (\Exception $statelessException) {
                // If stateless also fails, return error
                return redirect()->route('login')->withErrors([
                    'email' => 'Facebook authentication session expired. Please try logging in again.',
                ]);
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Facebook login error: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

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
                'email' => 'Failed to authenticate with Google. Please try again.',
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
