<x-app-layout>
    <div class="container-fluid" style="margin-left: 10px; margin-right: 10px;">
        <a href="javascript:window.history.back()" style="float: right; margin-right: 50px; margin-top: 10px; text-decoration: none; color: black; display: flex; align-items: center;">
            <img src="{{ asset('images/direction.png') }}" alt="Back" style="width: 20px; height: 20px; margin-right: 5px;">Back
        </a>
    </div>
    <div class="pt-12 w-full sm:max-w-md mx-auto mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <p class="font-bold text-xl mb-4 text-center"><label>{{ __('User  Account Details') }}</label></p>

        <div class="mt-4">
            <x-input-label for="username" :value="__('Username:')" style="font-weight: bold; font-size: 18px;" />
            <p class="block mt-1 w-full"><label>{{ $useraccount->username }}</label></p>
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email:')" style="font-weight: bold; font-size: 18px;" />
            <p class="block mt-1 w-full"><label>{{ $useraccount->email }}</label></p>
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password:')" style="font-weight: bold; font-size: 18px;" />
            <p class="block mt-1 w-full"><label>{{ str_repeat('*', strlen($useraccount->password)) }}</label></p>
        </div>

        <div class="mt-4">
            <x-input-label for="first_name" :value="__('First Name:')" style="font-weight: bold; font-size: 18px;" />
            <p class="block mt-1 w-full"><label>{{ $useraccount->firstname }}</label></p>
        </div>

        <div class="mt-4">
            <x-input-label for="last_name" :value="__('Last Name:')" style="font-weight: bold; font-size: 18px;" />
            <p class="block mt-1 w-full"><label>{{ $useraccount->lastname }}</label></p>
        </div>

        <div class="mt-4">
            <x-input-label for="middle_name" :value="__('Middle Name:')" style="font-weight: bold; font-size: 18px;" />
            <p class="block mt-1 w-full"><label>{{ $useraccount->middlename }}</label></p>
        </div>

        <div class="mt-4">
            <x-input-label for="mobile" :value="__('Mobile Number:')" style="font-weight: bold; font-size: 18px;" />
            <p class="block mt-1 w-full"><label>{{ $useraccount->mobile }}</label></p>
        </div>

        <div class="mt-4">
            <x-input-label for="organization" :value="__('Name of Organization:')" style="font-weight: bold; font-size: 18px;" />
            <p class="block mt-1 w-full"><label>{{ $useraccount->organization }}</label></p>
        </div>

        <div class="mt-4">
            <x-input-label for="is_approved" :value="__('Approved:')" style="font-weight: bold; font-size: 18px;" />
            <p class="block mt-1 w-full"><label style="color: {{ $useraccount->is_approved == 0 ? 'red' : '' }}">{{ $useraccount->is_approved == 0 ? 'No' : 'Yes' }}</label></p>
        </div>

        <div class="mt-4">
            <x-input-label for="is_active" :value="__('Status:')" style="font-weight: bold; font-size: 18px;" />
            <p class="block mt-1 w-full"><label style="color: {{ $useraccount->is_active == 0 ? 'red' : '' }}">{{ $useraccount->is_active == 0 ? 'Inactive' : 'Active' }}</label></p>
        </div>

        <div class="mt-4">
            <x-input-label for="userlevel" :value="__('User Level:')" style="font-weight: bold; font-size: 18px;" />
            <p class="block mt-1 w-full"><label>{{ DB::table('ref_userlevels')->where('userlevel_code', $useraccount->userlevel)->value('userlevel_desc') }}</label></p>
        </div>

    </div>
</x-app-layout>