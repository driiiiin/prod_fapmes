<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class ValidateSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip validation for login/logout routes and AJAX session cleanup
        if ($request->routeIs('login') || 
            $request->routeIs('logout') || 
            $request->routeIs('session.cleanup') ||
            $request->routeIs('captcha.refresh') ||
            $request->routeIs('password.*') ||
            $request->is('login') ||
            $request->is('logout') ||
            $request->is('session-cleanup')) {
            return $next($request);
        }

        // Check if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            $currentSessionId = Session::getId();
            
            // If user has a stored session_id and it doesn't match current session
            if ($user->session_id && $user->session_id !== $currentSessionId) {
                // Clear the session_id and logout
                try {
                    $user->session_id = null;
                    $user->save();
                } catch (\Exception $e) {
                    // Continue even if save fails
                }
                
                Auth::logout();
                Session::invalidate();
                Session::regenerateToken();
                
                return redirect()->route('login')->withErrors([
                    'login' => 'Your session has expired or you have been logged in from another device.'
                ]);
            }
            
            // If session_id is null, restore it instead of logging out
            // This handles edge cases where session_id might be cleared incorrectly
            if (!$user->session_id) {
                try {
                    $user->session_id = $currentSessionId;
                    $user->save();
                } catch (\Exception $e) {
                    // If we can't save, just continue - don't logout the user
                }
            }
        }

        return $next($request);
    }
}
