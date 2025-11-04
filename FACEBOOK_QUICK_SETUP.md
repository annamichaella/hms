# Quick Facebook Login Setup

## Step 1: Create Facebook App

1. Go to https://developers.facebook.com/
2. Click "My Apps" → "Create App"
3. Choose "Consumer" app type
4. Fill in app details:
   - App Name: Hospital Management System
   - Contact Email: Your email
5. Click "Create App"

## Step 2: Add Facebook Login

1. In your app dashboard, find "Add Products to Your App"
2. Click "Set Up" on "Facebook Login"
3. Choose "Web" platform

## Step 3: Configure Settings

1. Go to **Settings** → **Basic**
   - Note your **App ID** and **App Secret**
   - **IMPORTANT**: Scroll down to **"App Domains"** and add: `localhost`
   - Scroll to **"Website"** section, click **"Add Platform"** → Select **"Website"**
   - Add **Site URL**: `http://localhost:8000`
   - Click **"Save Changes"**

2. Go to **Products** → **Facebook Login** → **Settings**
   - Scroll to **"Valid OAuth Redirect URIs"**
   - Add: `http://localhost:8000/auth/facebook/callback`
   - Click **"Save Changes"**

## Step 4: Add Credentials to .env

Open your `.env` file and add:

```env
FACEBOOK_CLIENT_ID=your_app_id_here
FACEBOOK_CLIENT_SECRET=your_app_secret_here
FACEBOOK_REDIRECT_URI=http://localhost:8000/auth/facebook/callback
```

**Replace `your_app_id_here` and `your_app_secret_here` with your actual values from Step 3.**

## Step 5: Clear Config Cache

```bash
php artisan config:clear
```

## Step 6: Test

1. Go to `http://localhost:8000/login`
2. Click "Facebook" button
3. Authorize the app
4. You should be logged in!

## Troubleshooting

### "Can't Load URL - Domain not included in app's domains"
- ✅ Go to **Settings** → **Basic** → **App Domains**
- ✅ Add: `localhost` (no http://, no port number)
- ✅ Add **Website** platform with Site URL: `http://localhost:8000`
- ✅ Save changes and wait 2-3 minutes for changes to take effect

### "App Not Setup" Error
- Go to **App Dashboard** → **Roles** → **Roles**
- Add yourself as a test user
- Or add test users in **Roles** → **Test Users**

### "Invalid OAuth Redirect URI"
- **For development**: `http://localhost` redirects are automatically allowed - no configuration needed
- **For production**: Make sure the redirect URI in Facebook matches exactly:
  - `https://yourdomain.com/auth/facebook/callback`
  - No trailing slash
  - Must use HTTPS

### "Email permission required"
- Make sure you authorized email access when logging in
- The app should request email permission automatically

### Still Not Working?
1. Check `.env` file has correct values (App ID and App Secret)
2. Run `php artisan config:clear`
3. Check Laravel logs: `storage/logs/laravel.log`
4. For production: Verify redirect URI in Facebook matches exactly

