# Captcha Testing Guide

## Testing the Captcha Refresh Functionality

### 1. Basic Refresh Test
1. Navigate to the login page
2. Note the current captcha image
3. Click the refresh button (circular arrow icon)
4. Verify that a new captcha image appears
5. Verify that the captcha input field is cleared

### 2. Form Submission Test
1. Enter incorrect captcha code
2. Submit the form
3. Verify that a new captcha is generated
4. Verify that the error message appears
5. Verify that username/email is preserved but password is cleared

### 3. Rate Limiting Test
1. Submit multiple incorrect login attempts
2. Verify that rate limiting kicks in after 5 attempts
3. Verify that new captchas are still generated even when rate limited

### 4. Session Security Test
1. Open login page in one browser tab
2. Open login page in another browser tab
3. Verify that each tab has a different captcha
4. Verify that captchas are unique per session

### 5. Expiration Test
1. Leave the login page open for 15+ minutes
2. Try to submit the form
3. Verify that the captcha is rejected as expired
4. Verify that a new captcha is generated

## Debugging Tips

### Check Session Data
```php
// Add this to the login view temporarily for debugging
@if(config('app.debug'))
    <div style="display:none;">
        Captcha ID: {{ session('captcha_id') }}<br>
        Has SVG: {{ session('captcha_svg') ? 'Yes' : 'No' }}<br>
        Session ID: {{ session()->getId() }}
    </div>
@endif
```

### Check Controller Debug
```php
// Add this to the create method temporarily
Log::info('Captcha generated', [
    'id' => $captchaData['id'],
    'svg_length' => strlen($captchaData['svg']),
    'session_id' => session()->getId()
]);
```

### Common Issues and Solutions

1. **Captcha not refreshing**: Check if session data is being stored properly
2. **Captcha validation failing**: Check if captcha_id is being passed correctly
3. **Rate limiting not working**: Check if throttle middleware is applied
4. **Security headers missing**: Check if SecurityHeaders middleware is loaded

## Manual Testing Commands

```bash
# Clear all sessions (for testing)
php artisan session:table
php artisan migrate

# Test captcha cleanup command
php artisan captcha:cleanup

# Check application logs
tail -f storage/logs/laravel.log
```

## Browser Developer Tools

1. **Network Tab**: Check if login requests are being made
2. **Console**: Look for JavaScript errors
3. **Application Tab**: Check session storage
4. **Elements Tab**: Verify captcha SVG is present

## Expected Behavior

- ✅ Captcha refreshes when refresh button is clicked
- ✅ New captcha generated on form submission errors
- ✅ Captcha validation works correctly
- ✅ Rate limiting prevents brute force attacks
- ✅ Security headers are present
- ✅ Failed attempts are logged
- ✅ Session data is properly managed
