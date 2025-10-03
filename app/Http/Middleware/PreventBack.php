<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventBack
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Set cache control headers to prevent caching
        $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Add JavaScript to prevent back button if this is a view response
        if ($response->headers->get('Content-Type') && str_contains($response->headers->get('Content-Type'), 'text/html')) {
            $content = $response->getContent();

            // Add prevent-back JavaScript before closing </body> tag
            $preventBackScript = '
            <script>
                // Prevent back button functionality
                window.history.forward();

                function noBack() {
                    window.history.forward();
                }

                // Prevent back button on page load
                window.onload = function() {
                    noBack();
                };

                // Prevent back button on page show (for cached pages)
                window.onpageshow = function(event) {
                    if (event.persisted) {
                        noBack();
                    }
                };

                // Prevent back button on beforeunload
                window.onbeforeunload = function() {
                    noBack();
                };

                // Additional security: disable right-click context menu
                document.addEventListener("contextmenu", function(e) {
                    e.preventDefault();
                });

                // Prevent keyboard shortcuts for back navigation
                document.addEventListener("keydown", function(e) {
                    // Prevent Alt+Left (back button)
                    if (e.altKey && e.keyCode === 37) {
                        e.preventDefault();
                        return false;
                    }
                    // Prevent Ctrl+Z (undo)
                    if (e.ctrlKey && e.keyCode === 90) {
                        e.preventDefault();
                        return false;
                    }
                });
            </script>';

            // Insert script before closing body tag
            $content = str_replace('</body>', $preventBackScript . '</body>', $content);
            $response->setContent($content);
        }

        return $response;
    }
}
