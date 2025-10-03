<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Constrain login to email or safe username characters (blocks quotes and SQL meta)
            'login' => [
                'required',
                'string',
                'max:150',
                // allow either an email or a username made of safe chars
                'regex:/^(?:[A-Za-z0-9._+\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}|[A-Za-z0-9._+\-]{3,150})$/'
            ],
            'password' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Sanitize inputs before validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'login' => is_string($this->input('login')) ? trim($this->input('login')) : $this->input('login'),
        ]);
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Get the login input (either email or username)
        $login = $this->input('login');
        $password = $this->input('password');

        // Attempt to find the user by email or username
        $user = \App\Models\User::where(function ($query) use ($login) {
            $query->where('email', $login)
                  ->orWhere('username', $login);
        })->first();

        if ($user) {
            // Check if the user is approved
            if ($user->is_approved == 0) {
                throw ValidationException::withMessages([
                    'login' => trans('Your account is currently pending for approval. Please contact the administrator for further assistance.'), // Custom message for pending approval
                ]);
            }

            // Check if the user is active
            if ($user->is_active == 0) {
                throw ValidationException::withMessages([
                    'login' => trans('Your account is currently inactive. Please contact the administrator for further assistance.'), // Custom message for inactive account
                ]);
            }

            // Attempt to authenticate the user
            if (Auth::attempt(['id' => $user->id, 'password' => $password], $this->boolean('remember'))) {
                RateLimiter::clear($this->throttleKey());
                return; // Authentication successful
            }
        }

        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.failed'),
        ]);

        // if (! $user) {
        //     throw ValidationException::withMessages([
        //         'login' => trans('auth.login'),
        //     ]);
        // }

        // throw ValidationException::withMessages([
        //     'password' => trans('auth.password'),
        // ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 3)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 86400),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
        public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('login')).'|'.$this->ip());
    }
}
