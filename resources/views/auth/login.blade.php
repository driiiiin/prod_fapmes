<x-guest-layout>
    <div class="flex items-center justify-between" style="padding-bottom:20px">
        <img src="{{ asset('images/DOH-logo.png') }}" class="h-20 w-auto mx-2" alt="DOH Logo">
        <p class="font-bold text-xl text-center"><strong>{{ __('Log in') }}</strong></p>
        <img src="{{ asset('images/BP-logo.png') }}" class="h-24 w-auto mx-2" alt="BP Logo">
    </div>

    <form method="POST" action="{{ route('login') }}" id="login-form">
        @csrf

        <!-- Username or Email Address -->
        <div>
            <x-input-label for="login" :value="__('Username or Email Address')" class="bi bi-person-fill" />

            <x-text-input id="login" class="block mt-1 w-full"
                type="text"
                name="login"
                required autofocus
                placeholder="Enter Username or Email Address" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="bi bi-lock-fill" />

            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full pr-10"
                    type="password"
                    name="password"
                    required autocomplete="current-password" />
                <i class="bi bi-eye-fill absolute right-2 top-1/2 transform -translate-y-1/2 cursor-pointer" id="eye" onclick="showPassword()"></i>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <script>
            function showPassword() {
                var x = document.getElementById("password");
                if (x.type === "password") {
                    x.type = "text";
                    document.getElementById("eye").classList.remove("bi-eye-fill");
                    document.getElementById("eye").classList.add("bi-eye-slash-fill");
                } else {
                    x.type = "password";
                    document.getElementById("eye").classList.remove("bi-eye-slash-fill");
                    document.getElementById("eye").classList.add("bi-eye-fill");
                }
            }
        </script>

        <!-- Remember Me -->
        <!-- <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div> -->

        <!-- Terms and Conditions Agreement -->
        <!-- <div class="flex items-start mt-4">
            <label for="terms_agree" class="mt-1">
                <input id="terms_agree" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="terms_agree" required>
                <span class="sr-only">I agree</span>
            </label>
            <div class="ms-2 text-sm text-gray-600 text-justify w-full" style="text-align: justify;">
                I have read about the <a href="#" class="underline text-blue-600 hover:text-blue-800">Terms and Conditions</a> and express my consent thereto.
            </div>
        </div>
        <span id="terms-error" class="text-red-600 text-sm hidden">You must agree to the terms and conditions.</span> -->


        <!-- Captcha Section -->
        <div class="mt-4">
            <div class="flex items-center gap-2">
                <span id="captchaSvgContainer" style="display: flex; align-items: center; flex: 1 1 0; min-width: 0;">
                    {!! str_replace('<svg ', '<svg style="width:100%;height:44px;max-width:100%;" ', session('captcha_svg', $captcha_svg ?? '')) !!}
                </span>
                <button type="button"
                        id="refreshCaptchaBtn"
                        title="Refresh Captcha"
                        class="flex items-center justify-center h-11 w-11 rounded-full bg-white border border-gray-300 shadow-sm transition hover:bg-indigo-50 hover:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-400 group"
                        aria-label="Refresh Captcha">
                    <i class="fa fa-refresh fa-lg group-hover:animate-spin transition" style="color: #296D98;"></i>
                </button>
            </div>
            <input type="hidden" name="captcha_id" id="captcha_id" value="{{ session('captcha_id', $captcha_id ?? '') }}" />
            <input id="captcha_input" name="captcha_input" type="text" maxlength="6" autocomplete="off" required placeholder="Enter Captcha" class="mt-2 block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
            @error('captcha_input')
            <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror

            @if(config('app.debug'))
                <div style="display:none; font-size: 10px; color: #666; margin-top: 5px;">
                    Debug: Captcha ID: {{ session('captcha_id') }} | Has SVG: {{ session('captcha_svg') ? 'Yes' : 'No' }} | Session: {{ session()->getId() }}
                </div>
            @endif
        </div>
        <!-- End Captcha Section -->



        <div class="flex items-center justify-center mt-4">
            <!-- @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif -->

            <x-primary-button class="ms-3">
                {{ __('Sign in') }}
            </x-primary-button>
        </div>
    </form>

    <!-- <script>
        window.history.forward();
        function noBack() {
            window.history.forward();
        }
    </script>
    <body onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload=""> -->

    <script>
        window.history.forward();

        function noBack() {
            window.history.forward();
        }

        // Captcha refresh functionality
        document.addEventListener('DOMContentLoaded', function() {
            const refreshBtn = document.getElementById('refreshCaptchaBtn');
            const captchaContainer = document.getElementById('captchaSvgContainer');
            const captchaIdInput = document.getElementById('captcha_id');
            const captchaInput = document.getElementById('captcha_input');

            if (refreshBtn) {
                refreshBtn.addEventListener('click', function() {
                    // Disable button and show loading state
                    refreshBtn.disabled = true;
                    const icon = refreshBtn.querySelector('i');
                    icon.classList.add('animate-spin');

                    // Clear captcha input
                    captchaInput.value = '';

                    // Make AJAX request to refresh captcha
                    fetch('{{ route("captcha.refresh") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update captcha SVG
                            const newSvg = data.captcha_svg.replace('<svg ', '<svg style="width:100%;height:44px;max-width:100%;" ');
                            captchaContainer.innerHTML = newSvg;

                            // Update captcha ID
                            captchaIdInput.value = data.captcha_id;

                            // Focus on captcha input
                            captchaInput.focus();
                        } else {
                            console.error('Failed to refresh captcha');
                            // Fallback to page reload
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error refreshing captcha:', error);
                        // Fallback to page reload
                        window.location.reload();
                    })
                    .finally(() => {
                        // Re-enable button and remove loading state
                        refreshBtn.disabled = false;
                        icon.classList.remove('animate-spin');
                    });
                });
            }
        });

        // Terms and Conditions validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('login-form');
            const termsCheckbox = document.getElementById('terms_agree');
            const errorMsg = document.getElementById('terms-error');
            form.addEventListener('submit', function(e) {
                if (!termsCheckbox.checked) {
                    e.preventDefault();
                    errorMsg.classList.remove('hidden');
                } else {
                    errorMsg.classList.add('hidden');
                }
            });
        });


    </script>

    <body onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="">
</x-guest-layout>
