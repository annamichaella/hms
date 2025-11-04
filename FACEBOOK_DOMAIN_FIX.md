# Fix: "Can't Load URL - Domain not included in app's domains"

## Quick Fix

You need to add `localhost` to your Facebook App Domains.

## Step-by-Step Fix

### Step 1: Go to Facebook App Settings

1. Go to https://developers.facebook.com/
2. Click on **"My Apps"** in the top right
3. Select your app (or create one if you haven't)

### Step 2: Add App Domain

1. In the left sidebar, click **"Settings"** → **"Basic"**
2. Scroll down to **"App Domains"** section
3. Click **"Add Domain"**
4. Enter: `localhost`
5. Click **"Save Changes"**

### Step 3: Configure Site URL (Important!)

1. Still in **"Settings"** → **"Basic"**
2. Scroll down to **"Website"** section
3. Click **"Add Platform"** → Select **"Website"**
4. In the **"Site URL"** field, enter:
   ```
   http://localhost:8000
   ```
5. Click **"Save Changes"**

### Step 4: Configure Facebook Login Settings

1. In the left sidebar, click **"Products"** → **"Facebook Login"** → **"Settings"**
2. Scroll down to **"Valid OAuth Redirect URIs"** (optional for localhost, but good to add)
3. Add:
   ```
   http://localhost:8000/auth/facebook/callback
   ```
4. Click **"Save Changes"**

### Step 5: Clear Config and Test

1. Make sure your `.env` has the credentials:
   ```env
   FACEBOOK_CLIENT_ID=your_app_id
   FACEBOOK_CLIENT_SECRET=your_app_secret
   FACEBOOK_REDIRECT_URI=http://localhost:8000/auth/facebook/callback
   ```

2. Clear Laravel config:
   ```bash
   php artisan config:clear
   ```

3. Test the login again at `http://localhost:8000/login`

## What to Add in Facebook App Settings

### App Domains
```
localhost
```

### Site URL (in Website platform)
```
http://localhost:8000
```

### Valid OAuth Redirect URIs (optional but recommended)
```
http://localhost:8000/auth/facebook/callback
```

## Important Notes

1. **App Domains**: Add `localhost` (no http://, no port)
2. **Site URL**: Add `http://localhost:8000` (with http:// and port)
3. **Redirect URI**: Add the full callback URL

## Still Not Working?

1. **Clear browser cache** - Sometimes Facebook caches old settings
2. **Wait 5-10 minutes** - Facebook settings can take time to propagate
3. **Check app is in Development Mode** - Make sure you're added as a test user
4. **Verify credentials** - Double-check App ID and Secret in `.env`

## For Production (Later)

When deploying to production, you'll need to:
1. Add your production domain to **App Domains**: `yourdomain.com`
2. Update **Site URL**: `https://yourdomain.com`
3. Add production redirect URI: `https://yourdomain.com/auth/facebook/callback`

