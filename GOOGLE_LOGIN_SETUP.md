# Google Login Setup Guide

This guide will walk you through setting up Google social login for the Hospital Management System.

## Prerequisites

- Google Account
- Laravel application already set up
- Domain or localhost URL

## Step 1: Create a Google Cloud Project

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Click on the project dropdown at the top
3. Click **"New Project"**
4. Fill in the project details:
   - **Project Name**: Hospital Management System (or your preferred name)
   - **Organization**: (Leave default if not needed)
   - **Location**: (Leave default)
5. Click **"Create"**

## Step 2: Configure OAuth Consent Screen

**Note**: You no longer need to enable any specific APIs! Google's OAuth 2.0 works without enabling additional APIs in the library.

1. Go to **"APIs & Services"** → **"OAuth consent screen"**
2. Select **"External"** (unless you have a Google Workspace account)
3. Click **"Create"**

4. Fill in the required information:
   - **App name**: Hospital Management System
   - **User support email**: Your email address
   - **Developer contact information**: Your email address
   - Click **"Save and Continue"**

5. For **Scopes**:
   - Click **"Add or Remove Scopes"**
   - Select the following scopes:
     - `email`
     - `profile`
     - `openid`
   - Click **"Update"** then **"Save and Continue"**

6. For **Test users** (in development):
   - Click **"Add Users"**
   - Add your Gmail address
   - Click **"Add"** then **"Save and Continue"**

7. Review and click **"Back to Dashboard"**

## Step 3: Create OAuth 2.0 Credentials

1. Go to **"APIs & Services"** → **"Credentials"**
2. Click **"Create Credentials"** → **"OAuth client ID"**
3. Select **"Web application"** as the application type
4. Fill in the details:
   - **Name**: Hospital Management System Web Client
   - **Authorized JavaScript origins**: 
     - `http://localhost:8000` (for development)
     - `https://yourdomain.com` (for production)
   - **Authorized redirect URIs**:
     - `http://localhost:8000/auth/google/callback` (for development)
     - `https://yourdomain.com/auth/google/callback` (for production)
5. Click **"Create"**

6. **Copy your credentials**:
   - A popup will show your **Client ID** and **Client Secret**
   - Save these securely (you won't see the secret again)

## Step 4: Configure Your Laravel Application

1. Open your `.env` file
2. Add the following credentials:

```env
GOOGLE_CLIENT_ID=your_client_id_here.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your_client_secret_here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

Replace:
- `your_client_id_here` with your actual Google Client ID
- `your_client_secret_here` with your actual Google Client Secret

## Step 5: Test Google Login

1. Make sure your `.env` file is updated
2. Clear Laravel config cache:
   ```bash
   php artisan config:clear
   ```

3. Start your development server:
   ```bash
   php artisan serve
   ```

4. Go to `http://localhost:8000/login`
5. Click the **"Sign in with Google"** button
6. Select your Google account
7. Authorize the app
8. You should be redirected back to your application and logged in!

## How It Works

### User Flow

1. **New User**:
   - Clicks "Sign in with Google"
   - Selects Google account
   - Authorizes the app
   - System creates a new account with role "patient" by default
   - User is automatically logged in

2. **Existing User**:
   - Clicks "Sign in with Google"
   - Selects Google account
   - Authorizes the app
   - System finds user by Google ID or email
   - User is automatically logged in

3. **Linking Accounts**:
   - If a user has an existing account with the same email
   - The system links the Google account to their existing account
   - They can use either method to log in

## Troubleshooting

### Issue: "redirect_uri_mismatch"

**Solution**: 
- Make sure the exact redirect URI in your `.env` matches what's in Google Cloud Console
- Check for `http` vs `https`
- Check for trailing slashes
- The URI must match exactly

### Issue: "Error 400: invalid_request"

**Solution**:
- Verify your Client ID and Client Secret are correct in `.env`
- Make sure you copied the full Client ID (ends with `.apps.googleusercontent.com`)
- Run `php artisan config:clear`

### Issue: "Error 403: access_denied"

**Solution**:
- Your OAuth consent screen might need to be published
- For testing, add yourself as a test user
- Make sure you selected "External" user type if you don't have Google Workspace

### Issue: "OAuth consent screen required"

**Solution**:
- Complete the OAuth consent screen setup in Google Cloud Console
- Make sure you've added at least one test user
- Enable the necessary APIs

### Issue: "Google login button not showing"

**Solution**:
- Check that Font Awesome is loaded (for Google icon)
- Clear browser cache
- Check browser console for errors

## Production Deployment

When deploying to production:

1. **Update OAuth Consent Screen**:
   - In Google Cloud Console, go to OAuth consent screen
   - Add your production domain
   - Submit for verification if making app public

2. **Update `.env`**:
   ```env
   GOOGLE_REDIRECT_URI=https://yourdomain.com/auth/google/callback
   ```

3. **Update Credentials**:
   - In Credentials section, edit your OAuth 2.0 Client
   - Add production domain to Authorized JavaScript origins
   - Add production callback to Authorized redirect URIs

4. **Security**:
   - Keep your Client Secret secure
   - Never commit `.env` to version control
   - Use environment-specific configurations

## Advanced Configuration

### Requesting Additional Permissions

To get additional user information, modify `SocialAuthController`:

```php
$googleUser = Socialite::driver('google')
    ->scopes(['email', 'profile', 'openid'])
    ->user();
```

### Handling Missing Email

If a user denies email permission:

```php
if (!$googleUser->getEmail()) {
    return redirect()->route('login')->withErrors([
        'email' => 'Google email is required. Please authorize email access.',
    ]);
}
```

### Using Profile Picture

Google provides profile pictures in this format:

```php
$avatarUrl = $googleUser->getAvatar();
```

## Security Considerations

1. **HTTPS**: Always use HTTPS in production
2. **Client Secret**: Never expose your Client Secret
3. **State Parameter**: Laravel Socialite automatically handles CSRF protection
4. **Token Validation**: All tokens are validated by Google

## Support

For Google API issues, check:
- [Google Identity Services Documentation](https://developers.google.com/identity/protocols/oauth2)
- [Google Cloud Console](https://console.cloud.google.com/)
- [Laravel Socialite Documentation](https://laravel.com/docs/socialite)

For application issues, check:
- Laravel logs: `storage/logs/laravel.log`
- Application logs in your server

## Quick Reference

**Google Cloud Console:**
https://console.cloud.google.com/

**Credentials Management:**
https://console.cloud.google.com/apis/credentials

**OAuth Consent Screen:**
https://console.cloud.google.com/apis/credentials/consent

**API Library:**
https://console.cloud.google.com/apis/library

