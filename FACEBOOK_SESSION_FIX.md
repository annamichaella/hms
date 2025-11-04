# Fix: "Facebook authentication session expired"

## What This Error Means

This error occurs when the OAuth state parameter doesn't match between the initial redirect to Facebook and the callback. This can happen due to:

1. **Session cookies not being set properly**
2. **Browser blocking third-party cookies**
3. **Session driver issues**
4. **Taking too long to authorize on Facebook**

## Quick Fixes

### Solution 1: Try Again (Simplest)

Simply click the Facebook login button again. Sometimes the session just needs to be refreshed.

### Solution 2: Clear Browser Cookies

1. Open your browser's developer tools (F12)
2. Go to **Application** (Chrome) or **Storage** (Firefox)
3. Clear cookies for `localhost`
4. Try logging in again

### Solution 3: Check Session Configuration

Make sure your `.env` has:

```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
```

Then run:
```bash
php artisan config:clear
php artisan migrate
```

### Solution 4: Use Same Browser Window

- Don't open Facebook in a new tab/window
- Complete the authorization in the same browser session
- Don't close the browser between redirect and callback

### Solution 5: Disable Cookie Blocking

If you have browser extensions that block cookies:
- Temporarily disable them
- Or add `localhost` to allowed sites

## Why This Happens

Facebook OAuth uses a "state" parameter to prevent CSRF attacks. Laravel stores this in the session. If the session is lost between:
1. Redirecting to Facebook
2. User authorizing
3. Facebook redirecting back

Then the state won't match and you'll get this error.

## Technical Details

The code now has a fallback mechanism:
- First tries with session-based state (more secure)
- If that fails, falls back to stateless mode
- This should handle most cases automatically

## Still Having Issues?

1. **Check session table exists**: `php artisan migrate`
2. **Check browser console** for cookie errors
3. **Try incognito/private mode** to rule out extensions
4. **Check Laravel logs**: `storage/logs/laravel.log`

## For Production

In production, make sure:
- You're using HTTPS (required for secure cookies)
- Session cookies are set correctly
- Domain is properly configured
- No strict SameSite cookie policies blocking OAuth

