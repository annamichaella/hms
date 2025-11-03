# Facebook Login Setup Guide

This guide will walk you through setting up Facebook social login for the Hospital Management System.

## Prerequisites

- Facebook Developer Account
- Laravel application already set up
- Domain or localhost URL

## Step 1: Create a Facebook App

1. Go to [Facebook Developers](https://developers.facebook.com/)
2. Click on "My Apps" in the top right corner
3. Click "Create App"
4. Select "Consumer" as the app type
5. Fill in the app details:
   - **App Name**: Hospital Management System (or your preferred name)
   - **App Contact Email**: Your email address
   - Click "Create App"

## Step 2: Add Facebook Login Product

1. In your app dashboard, find "Add Products to Your App"
2. Click the "Set Up" button on "Facebook Login"
3. Select "Web" as the platform
4. You'll be redirected to the Facebook Login settings

## Step 3: Configure Facebook Login Settings

1. Go to **Settings** → **Basic** in the left sidebar
2. Note down your **App ID** and **App Secret** (you'll need these later)
3. Add **App Domains**: `localhost` (for development)
4. Click "Save Changes"

4. Go to **Products** → **Facebook Login** → **Settings**
5. Add your OAuth Redirect URIs:
   - For development: `http://localhost:8000/auth/facebook/callback`
   - For production: `https://yourdomain.com/auth/facebook/callback`
6. Click "Save Changes"

## Step 4: Configure Your Laravel Application

1. Open your `.env` file
2. Add the following credentials:

```env
FACEBOOK_CLIENT_ID=your_app_id_here
FACEBOOK_CLIENT_SECRET=your_app_secret_here
FACEBOOK_REDIRECT_URI=http://localhost:8000/auth/facebook/callback
```

Replace:
- `your_app_id_here` with your actual Facebook App ID
- `your_app_secret_here` with your actual Facebook App Secret

## Step 5: Test Facebook Login

1. Make sure your migrations are run:
   ```bash
   php artisan migrate
   ```

2. Start your development server:
   ```bash
   php artisan serve
   ```

3. Go to `http://localhost:8000/login`
4. Click the "Sign in with Facebook" button
5. Authorize the app with your Facebook account
6. You should be redirected back to your application

## How It Works

### User Flow

1. **New User**:
   - Clicks "Sign in with Facebook"
   - Authorizes the app on Facebook
   - System creates a new account with role "patient" by default
   - User is automatically logged in

2. **Existing User**:
   - Clicks "Sign in with Facebook"
   - Authorizes the app on Facebook
   - System finds user by Facebook ID or email
   - User is automatically logged in

3. **Linking Accounts**:
   - If a user has an existing account with the same email
   - The system links the Facebook account to their existing account
   - They can use either method to log in

### Database Schema

The system adds two columns to the `users` table:

- `provider`: Stores the OAuth provider name (e.g., "facebook")
- `provider_id`: Stores the unique ID from the provider

These columns are nullable, so regular email/password users aren't affected.

## Troubleshooting

### Issue: "Invalid OAuth Redirect URI"

**Solution**: Make sure you've added the exact redirect URI in Facebook App Settings:
- Check for `http` vs `https`
- Check for trailing slashes
- Ensure the URI matches exactly what's in your `.env` file

### Issue: "App Not Setup: This app is still in development mode"

**Solution**: 
1. Go to App Dashboard → Roles → Roles
2. Add yourself or test users
3. Or make your app public (not recommended for development)

### Issue: "Facebook login button not showing"

**Solution**:
1. Check that `.env` file has Facebook credentials
2. Clear config cache: `php artisan config:clear`
3. Make sure Font Awesome is loaded (for the Facebook icon)

### Issue: "Failed to authenticate with Facebook"

**Solution**:
1. Check that `FACEBOOK_CLIENT_ID` and `FACEBOOK_CLIENT_SECRET` are correct in `.env`
2. Verify the redirect URI is added in Facebook App Settings
3. Check Laravel logs: `storage/logs/laravel.log`

## Production Deployment

When deploying to production:

1. **Update `.env`**:
   ```env
   FACEBOOK_REDIRECT_URI=https://yourdomain.com/auth/facebook/callback
   ```

2. **Update Facebook App**:
   - Add your production domain to App Domains
   - Add production redirect URI
   - Submit your app for review if needed

3. **Security**:
   - Keep your App Secret secure
   - Never commit `.env` to version control
   - Use environment-specific configurations

## Additional Facebook Configuration

### Requesting Additional Permissions

To get user's profile picture or other data, modify `SocialAuthController`:

```php
$facebookUser = Socialite::driver('facebook')
    ->scopes(['email', 'public_profile'])
    ->fields(['name', 'email', 'picture'])
    ->user();
```

### Handling Missing Email

If a user denies email permission, you can handle it in the controller:

```php
if (!$facebookUser->getEmail()) {
    return redirect()->route('login')->withErrors([
        'email' => 'Facebook email is required. Please authorize email access.',
    ]);
}
```

## Security Considerations

1. **HTTPS**: Always use HTTPS in production
2. **App Secret**: Never expose your App Secret
3. **Tokens**: Laravel Socialite handles token management securely
4. **Validation**: All user data from Facebook is validated

## Support

For Facebook API issues, check:
- [Facebook Login Documentation](https://developers.facebook.com/docs/facebook-login)
- [Laravel Socialite Documentation](https://laravel.com/docs/socialite)

For application issues, check:
- Laravel logs: `storage/logs/laravel.log`
- Application logs in your server

