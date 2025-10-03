<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class UseraccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currentUserlevel = (string) auth()->user()->userlevel;

        $query = User::query();

        // For super admin (-1), show all users including -1 to allow self-identification
        if ($currentUserlevel === '-1') {
            // no filter
        } elseif ($currentUserlevel === '2') {
            $query->whereNotIn('userlevel', [-1, -2]);
        }

        $useraccounts = $query->get();

        // Determine which users are currently logged in based on users.session_id
        $activeUserIds = [];
        if ($currentUserlevel === '-1') {
            $activeUserIds = User::query()
                ->whereNotNull('session_id')
                ->where('session_id', '!=', '')
                ->pluck('id')
                ->all();
        }

        return view('app.useraccount.index', compact('useraccounts', 'activeUserIds'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Useraccount  $useraccount
     * @return \Illuminate\Http\Response
     */
    public function show(User $useraccount)
    {

        // Check if user can view this account
        if (auth()->user()->id !== $useraccount->id &&
            !in_array(auth()->user()->userlevel, [-1, 2])) {
            abort(403, 'Unauthorized access');
        }

        return view('app.useraccount.show', compact('useraccount'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Useraccount  $useraccount
     * @return \Illuminate\Http\Response
     */
    public function edit(User $useraccount)
    {

         // Check if user can edit this account
        if (auth()->user()->id !== $useraccount->id &&
            !in_array(auth()->user()->userlevel, [-1, 2])) {
            abort(403, 'Unauthorized access');
        }
        return view('app.useraccount.edit', compact('useraccount'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Useraccount  $useraccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $useraccount)
    {
        $request->validate([
            'username' => 'required|unique:users,username,' . $useraccount->id,
            'email' => 'required|email|unique:users,email,' . $useraccount->id,
            'password' => 'sometimes|nullable|min:8',
            'lastname' => 'required',
            'firstname' => 'required',
            'middlename' => 'required',
            'mobile' => ['required', 'regex:/^09\d{9}$/'],
            'userlevel' => 'required',
            'organization' => 'required',
            'terminate_sessions' => 'sometimes|boolean',

        ]);

        $useraccount->update([
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $useraccount->password,
            'lastname' => $request->lastname,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'mobile' => $request->mobile,
            'userlevel' => $request->userlevel,
            'organization' => $request->organization,
            'is_approved' => $request->is_approved ?? $useraccount->is_approved,
            'is_active' => $request->is_active ?? $useraccount->is_active,
        ]);

        $terminateSessions = $request->boolean('terminate_sessions');

        if ($terminateSessions) {
            // Remove all sessions for the target user
            DB::table('sessions')->where('user_id', $useraccount->id)->delete();

            // Also clear the stored session_id on the user record
            $useraccount->session_id = null;
            $useraccount->save();

            // If the editor is the same user, end current session immediately
            if (auth()->user()->id === $useraccount->id) {
                $request->session()->invalidate();
                auth()->logout();
                $request->session()->regenerateToken();
                return redirect()->route('login')->with('info', 'Your session has been terminated. Please login again.');
            }

            return redirect()->route('useraccount.index')->with('success', 'User session terminated successfully. User will be logged out on their next request.');
        }

        return redirect()->route('useraccount.index')->with('success', 'User account updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Useraccount  $useraccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $useraccount)
    {
        $useraccount->delete();
        return redirect()->route('useraccount.index')->with('success', 'User account deleted successfully.');
    }

    public function checkUsername(Request $request)
    {
        $username = $request->query('username');

        // Check if the username exists in the database using Eloquent
        $exists = User::where('username', $username)->exists();

        return response()->json(['available' => !$exists]);
    }

    public function checkEmail(Request $request)
    {
        $email = $request->query('email');

         // Check if the email exists in the database using Eloquent
        $exists = User::where('email', $email)->exists();

        return response()->json(['available' => !$exists]);
    }
}
