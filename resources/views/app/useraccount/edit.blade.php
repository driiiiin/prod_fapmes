@if (Auth()->user()->userlevel == -1 || Auth()->user()->userlevel == 2 )
<x-app-layout>
    <div class="container-fluid" style="margin-left: 10px; margin-right: 10px;">
        <a href="javascript:window.history.back()" style="float: right; margin-right: 50px; margin-top: 10px; text-decoration: none; color: black; display: flex; align-items: center;">
            <img src="{{ asset('images/direction.png') }}" alt="Back" style="width: 20px; height: 20px; margin-right: 5px;">Back
        </a>
    </div>
    <div class="pt-12 w-full sm:max-w-md mx-auto mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <p class="font-bold text-xl mb-4 text-center"><strong>{{ __('Edit User Account') }}</strong></p>

        <form method="POST" action="{{ route('useraccount.update', $useraccount->id) }}">
            @csrf
            @method('PUT')

            <div>
                <x-input-label for="username" :value="__('Username')" />
                <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username', $useraccount->username)" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('username')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $useraccount->email)" required autocomplete="email" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                <p class="mt-2 text-sm text-red-600">Leave the password blank if you want to keep the current password.</p>
            </div>

            <div class="mt-4">
                <x-input-label for="lastname" :value="__('Last Name')" />
                <x-text-input id="lastname" class="block mt-1 w-full" type="text" name="lastname" :value="old('lastname', $useraccount->lastname)" required autofocus autocomplete="lastname" />
                <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="firstname" :value="__('First Name')" />
                <x-text-input id="firstname" class="block mt-1 w-full" type="text" name="firstname" :value="old('firstname', $useraccount->firstname)" required autofocus autocomplete="firstname" />
                <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="middlename" :value="__('Middle Name')" />
                <x-text-input id="middlename" class="block mt-1 w-full" type="text" name="middlename" :value="old('middlename', $useraccount->middlename)" required autofocus autocomplete="middlename" />
                <x-input-error :messages="$errors->get('middlename')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="mobile" :value="__('Mobile Number e.g. 09101234567')" />
                <x-text-input id="mobile" class="block mt-1 w-full" type="text" name="mobile" :value="old('mobile', $useraccount->mobile)" required autofocus autocomplete="mobile" placeholder="09101234567" pattern="09\d{9}" />
                <x-input-error :messages="$errors->get('mobile')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="organization" :value="__('Name of Organization')" />
                <x-text-input id="organization" class="block mt-1 w-full" type="text" name="organization" :value="old('organization', $useraccount->organization)" required autofocus autocomplete="organization" placeholder="Enter organization name" />
                <x-input-error :messages="$errors->get('organization')" class="mt-2" />
            </div>


            <div class="mt-4">
                <x-input-label for="is_approved" :value="__('Is Approved')" />
                <div class="flex items-center">
                    <input id="is_approved_1" type="radio" name="is_approved" value="1" {{ old('is_approved', $useraccount->is_approved) == 1 ? 'checked' : '' }}>
                    <x-input-label for="is_approved_1" :value="__('Yes')" class="ml-2 mr-4" />
                    <input id="is_approved_0" type="radio" name="is_approved" value="0" {{ old('is_approved', $useraccount->is_approved) == 0 ? 'checked' : '' }}>
                    <x-input-label for="is_approved_0" :value="__('No')" class="ml-2" />
                </div>
            </div>

            <div class="mt-4">
                <x-input-label for="is_active" :value="__('Is Active')" />
                <div class="flex items-center">
                    <input id="is_active_1" type="radio" name="is_active" value="1" {{ old('is_active', $useraccount->is_active) == 1 ? 'checked' : '' }}>
                    <x-input-label for="is_active_1" :value="__('Yes')" class="ml-2 mr-4" />
                    <input id="is_active_0" type="radio" name="is_active" value="0" {{ old('is_active', $useraccount->is_active) == 0 ? 'checked' : '' }}>
                    <x-input-label for="is_active_0" :value="__('No')" class="ml-2" />
                </div>
            </div>

            <div class="mt-4">
                <x-input-label for="userlevel" :value="__('User Level')" />
                <select id="userlevel" name="userlevel" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                    <option value="" disabled selected>{{ __('Select User Level') }}</option>
                    @php $isSuperAdmin = Auth()->user()->userlevel == -1; @endphp
                    @foreach (DB::table('ref_userlevels')->get() as $userlevel)
                    @if($isSuperAdmin || !in_array($userlevel->userlevel_code, [-1, 2]))
                    <option value="{{ $userlevel->userlevel_code }}" {{ old('userlevel', $useraccount->userlevel) == $userlevel->userlevel_code ? 'selected' : '' }}>
                        {{ $userlevel->userlevel_desc }}
                    </option>
                    @endif
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('userlevel')" class="mt-2" />
            </div>



            @if (auth()->user()->userlevel == -1 || auth()->user()->userlevel == 2)
            <div class="mt-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="terminate_sessions" value="1" class="rounded border-gray-300">
                    <span class="ml-2">{{ __('Force logout this user (terminate all sessions)') }}</span>
                </label>
            </div>
            @endif


            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ms-4">
                    {{ __('Update') }}
                </x-primary-button>
            </div>
        </form>
    </div>



    <style>
        .username-feedback,
        .email-feedback,
        .mobile-feedback {
            font-size: 0.75rem;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const usernameInput = document.getElementById('username');
            const emailInput = document.getElementById('email');
            const mobileInput = document.getElementById('mobile');

            const usernameFeedback = document.createElement('div');
            const emailFeedback = document.createElement('div');
            const mobileFeedback = document.createElement('div');

            usernameFeedback.className = 'username-feedback';
            emailFeedback.className = 'email-feedback';
            mobileFeedback.className = 'mobile-feedback';

            usernameInput.parentNode.insertBefore(usernameFeedback, usernameInput.nextSibling);
            emailInput.parentNode.insertBefore(emailFeedback, emailInput.nextSibling);
            mobileInput.parentNode.insertBefore(mobileFeedback, mobileInput.nextSibling);

            usernameInput.addEventListener('input', function() {
                const username = usernameInput.value;

                if (username.length > 0) {
                    fetch(`/check-username?username=${username}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.available) {
                                usernameFeedback.textContent = 'Username is available';
                                usernameFeedback.style.color = 'green';
                            } else {
                                usernameFeedback.textContent = 'Username is already taken';
                                usernameFeedback.style.color = 'red';
                            }
                        });
                } else {
                    usernameFeedback.textContent = '';
                }
            });

            emailInput.addEventListener('input', function() {
                const email = emailInput.value;

                if (email.length > 0) {
                    fetch(`/check-email?email=${email}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.available) {
                                emailFeedback.textContent = 'Email is available';
                                emailFeedback.style.color = 'green';
                            } else {
                                emailFeedback.textContent = 'Email is already taken';
                                emailFeedback.style.color = 'red';
                            }
                        });
                } else {
                    emailFeedback.textContent = '';
                }
            });

            mobileInput.addEventListener('input', function() {
                const mobile = mobileInput.value;

                if (mobile.length > 0) {
                    if (mobile.match(/^09\d{9}$/)) {
                        mobileFeedback.textContent = 'Mobile number is valid';
                        mobileFeedback.style.color = 'green';
                    } else {
                        mobileFeedback.textContent = 'Mobile number is invalid';
                        mobileFeedback.style.color = 'red';
                    }
                } else {
                    mobileFeedback.textContent = '';
                }
            });
        });
    </script>
</x-app-layout>
@endif
