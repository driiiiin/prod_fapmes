<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'min:4', 'unique:users,username'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'lastname' => 'required',
            'firstname' => 'required',
            'middlename' => 'required',
            'mobile' => ['required', 'regex:/^09\d{9}$/'],
            'organization' => 'required',
            'userlevel' => 'required',
            
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'lastname' => $request['lastname'],
            'firstname' => $request['firstname'],
            'middlename' => $request['middlename'],
            'mobile' => $request['mobile'],
            'userlevel' => $request['userlevel'],
            'organization' => $request['organization'],
            'is_approved' => 0, // Account is pending for approval by admin
            'is_active' => 1,
            // 'role' => DB::table('userlevels')->where('userlevelid', $request['userlevel'])->value('userlevelname'),
        ]);

        event(new Registered($user));

        // Auth::login($user);

        return redirect(route('useraccount.index', absolute: false))->with('success', 'Registration successful and pending approval.');
    }
}
