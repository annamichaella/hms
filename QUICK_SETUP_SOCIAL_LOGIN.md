# Quick Social Login Setup (Google & Facebook)

## Current Issue
Your `.env` file has empty social login credentials, which is why you're getting "Invalid App ID" errors.

---

# GOOGLE LOGIN SETUP

## Step 1: Get Google Credentials (5 minutes)

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Click **"New Project"** → Enter project name (e.g., "HMS") → **"Create"**
3. Go to **"OAuth consent screen"**:
   - Select **"External"** → **"Create"**
   - Fill required info → **"Save and Continue"**
   - Add scopes: `email`, `profile`, `openid` → **"Save and Continue"**
   - Add test users (your Gmail) → **"Save and Continue"**
4. Go to **"Credentials"** → **"Create Credentials"** → **"OAuth client ID"**
   - Choose **"Web application"**
   - **Authorized redirect URIs**: `http://localhost:8000/auth/google/callback`
   - Click **"Create"**
5. **Copy your Client ID and Client Secret** (save them!)

**Note**: You no longer need to enable any APIs in the library. OAuth 2.0 works directly!

## Step 2: Add Google to .env

```env
GOOGLE_CLIENT_ID=your_client_id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your_client_secret_here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

## Step 3: Test Google Login

```bash
php artisan config:clear
php artisan serve
```

Go to `http://localhost:8000/login` → Click **"Sign in with Google"**

---

# FACEBOOK LOGIN SETUP

## Step 1: Get Facebook Credentials (5 minutes)

1. Go to [Facebook Developers](https://developers.facebook.com/)
2. Click **"My Apps"** → **"Create App"**
3. Choose **"Consumer"** app type
4. Enter app name and email → **"Create App"**
5. Go to **Settings** → **Basic** → **Copy App ID and App Secret**
6. Click **"Add Products"** → Find **"Facebook Login"** → **"Set Up"**
7. Choose **"Web"** platform

**Note**: `http://localhost` redirects are automatically allowed in development mode, so you don't need to add them manually. Only add redirect URIs when deploying to production.

## Step 2: Add Facebook to .env

```env
FACEBOOK_CLIENT_ID=your_app_id_here
FACEBOOK_CLIENT_SECRET=your_app_secret_here
FACEBOOK_REDIRECT_URI=http://localhost:8000/auth/facebook/callback
```

## Step 3: Test Facebook Login

```bash
php artisan config:clear
php artisan serve
```

Go to `http://localhost:8000/login` → Click **"Sign in with Facebook"**

---

## Complete .env Example

After setup, your `.env` should look like this:

```env
# Google Social Login
GOOGLE_CLIENT_ID=123456789-abcdefg.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-your_secret_here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

# Facebook Social Login
FACEBOOK_CLIENT_ID=1234567890123456
FACEBOOK_CLIENT_SECRET=abc123def456ghi789
FACEBOOK_REDIRECT_URI=http://localhost:8000/auth/facebook/callback
```

**IMPORTANT**: Use your actual credentials, not the examples above!

---

## Troubleshooting

### "Invalid App ID" / "Invalid Client ID"
- ✅ Check you copied credentials correctly (no spaces, quotes, or extra characters)
- ✅ Run `php artisan config:clear`
- ✅ Restart Laravel server

### "redirect_uri_mismatch"
- ✅ **For development**: `http://localhost` redirects are automatically allowed - no configuration needed
- ✅ **For production**: Make sure redirect URI in `.env` matches exactly what's in app settings
- ✅ Check `http` vs `https` (production must use HTTPS)
- ✅ No trailing slashes

### "Access Denied" / "403 Error"
- ✅ Add yourself as a test user in app settings
- ✅ Complete OAuth consent screen setup
- ✅ For Google: Make sure OAuth consent is "External" type

### Button Not Showing
- ✅ Clear browser cache
- ✅ Check browser console for errors
- ✅ Make sure Font Awesome is loaded

---

## Quick Links

**Google:**
- [Cloud Console](https://console.cloud.google.com/)
- [Credentials](https://console.cloud.google.com/apis/credentials)
- [OAuth Consent](https://console.cloud.google.com/apis/credentials/consent)

**Facebook:**
- [Developer Portal](https://developers.facebook.com/apps/)
- [My Apps](https://developers.facebook.com/apps/)

---

## Need More Help?

See detailed guides:
- **Google**: `GOOGLE_LOGIN_SETUP.md`
- **Facebook**: `FACEBOOK_LOGIN_SETUP.md`

