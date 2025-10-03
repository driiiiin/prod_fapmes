<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Str;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        // Only clear session if user is already authenticated and accessing login page
        if (Auth::check()) {
            $user = Auth::user();
            $user->session_id = null; // Clear the session ID
            $user->save();
            
            // Logout the user since they're accessing login page
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        // Generate captcha with unique identifier
        $captchaData = $this->generateCaptchaCode();

        // Store captcha data in session for the view
        session([
            'captcha_svg' => $captchaData['svg'],
            'captcha_id' => $captchaData['id']
        ]);

        return view('auth.login', [
            'captcha_svg' => $captchaData['svg'],
            'captcha_id' => $captchaData['id']
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Check if the user has exceeded the maximum number of login attempts
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->logFailedAttempt($request, 'Rate limit exceeded');
            // Generate new captcha for next attempt
            $captchaData = $this->generateCaptchaCode();
            session([
                'captcha_svg' => $captchaData['svg'],
                'captcha_id' => $captchaData['id']
            ]);
            return back()->withErrors([
                'login' => 'Too many login attempts. Please try again after 24 hours.',
            ])->onlyInput('login');
        }

        // Validate captcha first
        $captchaId = $request->input('captcha_id');
        $captchaInput = $request->input('captcha_input');

        if (!$this->validateCaptcha($captchaId, $captchaInput)) {
            $this->incrementLoginAttempts($request);
            $this->logFailedAttempt($request, 'Invalid captcha');

            // Generate new captcha for next attempt
            $captchaData = $this->generateCaptchaCode();

            // Store new captcha data in session
            session([
                'captcha_svg' => $captchaData['svg'],
                'captcha_id' => $captchaData['id']
            ]);

            return back()->withInput($request->except(['password', 'captcha_input']))
                ->withErrors(['captcha_input' => 'Incorrect captcha. Please try again.']);
        }

        // Determine if the input is an email or username and find the user
        $loginInput = $request->input('login');
        $isEmail = filter_var($loginInput, FILTER_VALIDATE_EMAIL);
        
        // Use proper query grouping to prevent SQL injection
        $user = User::where(function ($query) use ($loginInput, $isEmail) {
            if ($isEmail) {
                $query->where('email', $loginInput);
            } else {
                $query->where('username', $loginInput);
            }
        })->first();

        // Check if user exists and handle account status
        if ($user) {
            if ($user->is_approved == 0) {
                $this->logFailedAttempt($request, 'Account pending approval');
                // Generate new captcha for next attempt
                $captchaData = $this->generateCaptchaCode();
                session([
                    'captcha_svg' => $captchaData['svg'],
                    'captcha_id' => $captchaData['id']
                ]);
                return back()->withErrors([
                    'login' => 'Your account is pending for approval. Please contact the administrator for approval of your account.',
                ])->onlyInput('login');
            }
            if ($user->is_active == 0) {
                $this->logFailedAttempt($request, 'Account inactive');
                // Generate new captcha for next attempt
                $captchaData = $this->generateCaptchaCode();
                session([
                    'captcha_svg' => $captchaData['svg'],
                    'captcha_id' => $captchaData['id']
                ]);
                return back()->withErrors([
                    'login' => 'This user account is not active. Please contact the administrator for approval of your account.',
                ])->onlyInput('login');
            }
            if ($user->session_id && $user->session_id !== session()->getId()) {
                $this->logFailedAttempt($request, 'Account already logged in elsewhere');
                // Generate new captcha for next attempt
                $captchaData = $this->generateCaptchaCode();
                session([
                    'captcha_svg' => $captchaData['svg'],
                    'captcha_id' => $captchaData['id']
                ]);
                return back()->withErrors([
                    'login' => 'This account is already logged in from another device.',
                ])->onlyInput('login');
            }
        }

        // Attempt to log in the user using the determined field
        $credentials = $isEmail 
            ? ['email' => $loginInput, 'password' => $request->input('password')]
            : ['username' => $loginInput, 'password' => $request->input('password')];
            
        if (Auth::attempt($credentials)) {
            // Clear captcha data after successful validation
            $this->clearCaptcha($captchaId);

            // Regenerate session first
            $request->session()->regenerate();

            // Store the session ID in the user record after regeneration
            $authenticatedUser = Auth::user();
            if ($authenticatedUser) {
                $sessionId = session()->getId();
                $authenticatedUser->session_id = $sessionId;
                $authenticatedUser->save();
                
                Log::info('Session ID saved after login', [
                    'user_id' => $authenticatedUser->id,
                    'session_id' => $sessionId,
                    'username' => $authenticatedUser->username
                ]);
            }

            // Set session flag to show welcome modal
            session(['show_welcome_modal' => true]);

            // Redirect based on userlevel
            $authUser = Auth::user();
            if ($authUser && in_array($authUser->userlevel, [3, 4])) {
                // Redirect to the about route for userlevel 3 or 4
                return redirect()->route('about')
                    ->with('success', 'You are now logged in.');
            }

            return redirect()->intended(route('dashboard', absolute: false))
                ->with('success', 'You are now logged in.');
        }

        // If authentication fails, increment the login attempts
        $this->incrementLoginAttempts($request);
        $this->logFailedAttempt($request, 'Invalid credentials');

        // Get the number of attempts made
        $attempts = $this->limiter()->attempts($this->throttleKey($request));

        // Generate new captcha for next attempt
        $captchaData = $this->generateCaptchaCode();

        // Store new captcha data in session
        session([
            'captcha_svg' => $captchaData['svg'],
            'captcha_id' => $captchaData['id']
        ]);

        // Provide a warning message if the user is close to the limit
        if ($attempts >= 3) {
            return back()->withErrors([
                'login' => 'Invalid Username/Email Address/Password. You have ' . (5 - $attempts) . ' attempt(s) left before your account is locked for 24 hours.',
            ])->onlyInput('login');
        }

        return back()->withErrors([
            'login' => 'Invalid Username/Email Address/Password. You have ' . (5 - $attempts) . ' attempt(s) left.',
        ])->onlyInput('login');
    }

    /**
     * Refresh captcha via AJAX
     */
    public function refreshCaptcha(Request $request)
    {
        // Generate new captcha
        $captchaData = $this->generateCaptchaCode();

        // Store new captcha data in session
        session([
            'captcha_svg' => $captchaData['svg'],
            'captcha_id' => $captchaData['id']
        ]);

        // Return JSON response
        return response()->json([
            'success' => true,
            'captcha_svg' => $captchaData['svg'],
            'captcha_id' => $captchaData['id']
        ]);
    }

    /**
     * Generate a unique captcha code with identifier
     */
    protected function generateCaptchaCode(): array
    {
        // Generate unique 32-character identifier
        $captchaId = Str::random(32);

        // Generate 6-character captcha code
        $captcha = '';
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
        for ($i = 0; $i < 6; $i++) {
            $captcha .= $chars[random_int(0, strlen($chars) - 1)];
        }

        // Generate SVG
        $svg = $this->generateCaptchaSvg($captcha);

        // Store captcha data with expiration (15 minutes)
        $expiresAt = now()->addMinutes(15);
        session([
            "captcha_code_{$captchaId}" => $captcha,
            "captcha_expires_{$captchaId}" => $expiresAt->timestamp
        ]);

        return [
            'id' => $captchaId,
            'code' => $captcha,
            'svg' => $svg,
            'expires_at' => $expiresAt
        ];
    }

    /**
     * Validate captcha input
     */
    protected function validateCaptcha(string $captchaId, string $input): bool
    {
        // Check if captcha ID exists
        if (!$captchaId || !session("captcha_code_{$captchaId}")) {
            return false;
        }

        // Check if captcha has expired
        $expiresAt = session("captcha_expires_{$captchaId}");
        if (!$expiresAt || now()->timestamp > $expiresAt) {
            $this->clearCaptcha($captchaId);
            return false;
        }

        // Get stored captcha code
        $storedCode = session("captcha_code_{$captchaId}");

        // Case-insensitive comparison with trimmed input
        $isValid = strtolower(trim($input)) === strtolower(trim($storedCode));

        return $isValid;
    }

    /**
     * Clear captcha data from session
     */
    protected function clearCaptcha(string $captchaId): void
    {
        session()->forget([
            "captcha_code_{$captchaId}",
            "captcha_expires_{$captchaId}"
        ]);
    }


    /**
     * Log failed login attempts
     */
    protected function logFailedAttempt(Request $request, string $reason): void
    {
        Log::warning('Failed login attempt', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'login_field' => $request->input('login'),
            'reason' => $reason,
            'timestamp' => now()->toISOString()
        ]);
    }

    protected function hasTooManyLoginAttempts(Request $request)
    {
        return $this->limiter()->tooManyAttempts($this->throttleKey($request), 5);
    }

    protected function incrementLoginAttempts(Request $request)
    {
        $this->limiter()->hit($this->throttleKey($request));
    }

    protected function throttleKey(Request $request)
    {
        return strtolower($request->input('login')) . '|' . $request->ip();
    }

    protected function limiter()
    {
        return app('Illuminate\Cache\RateLimiter');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user) {
            $user->session_id = null; // Clear the session ID
            $user->save();
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }


    // Generate SVG with effects
    private function generateCaptchaSvg($text)
    {
        $svgWidth = 360;
        $svgHeight = 60;
        $svg = '<svg width="' . $svgWidth . '" height="' . $svgHeight . '" viewBox="0 0 ' . $svgWidth . ' ' . $svgHeight . '" xmlns="http://www.w3.org/2000/svg">';
        // Background gradient
        $svg .= '<defs><linearGradient id="bg" x1="0" y1="0" x2="1" y2="0"><stop offset="0%" stop-color="#e0e7ff"/><stop offset="100%" stop-color="#f0fdf4"/></linearGradient></defs>';
        $svg .= '<rect width="' . $svgWidth . '" height="' . $svgHeight . '" rx="16" fill="url(#bg)"/>';
        // Noise lines
        for ($i = 0; $i < 4; $i++) {
            $x1 = rand(0, $svgWidth); $y1 = rand(0, $svgHeight); $x2 = rand(0, $svgWidth); $y2 = rand(0, $svgHeight);
            $svg .= "<line x1='$x1' y1='$y1' x2='$x2' y2='$y2' stroke='#296D98' stroke-opacity='0.3' stroke-width='3'/>";
        }
        // Text with random color/rotation
        $charCount = strlen($text);
        $spacing = $svgWidth / ($charCount + 1);
        for ($i = 0; $i < $charCount; $i++) {
            $angle = rand(-18, 18);
            $x = $spacing * ($i + 1) + rand(-6,6);
            $y = $svgHeight / 2 + rand(8,16);
            $svg .= "<text x='$x' y='$y' font-size='38' font-family='Segoe UI, Arial, sans-serif' fill='#296D98' font-weight='bold' transform='rotate($angle $x $y)' style='text-shadow:1px 1px 2px #b6e3c6;'>" . htmlspecialchars($text[$i]) . "</text>";
        }
        // Dots
        for ($i = 0; $i < 30; $i++) {
            $cx = rand(0, $svgWidth); $cy = rand(0, $svgHeight); $r = rand(2, 5);
            $svg .= "<circle cx='$cx' cy='$cy' r='$r' fill='#296D98' fill-opacity='0.12'/>";
        }
        $svg .= '</svg>';
        return $svg;
    }
}

