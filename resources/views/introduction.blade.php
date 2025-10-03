<x-app-layout>
<div class="content h-screen w-full flex justify-center items-center">  
    <!-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot> -->

    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-center">
        <div class="flex items-center justify-center space-x-8">
            <div class="flex flex-col items-center mt-[-350px]">
                <img src="{{ asset('images/WHO-logo.png') }}" class="h-60 w-auto" alt="WHO Logo">
            </div>
            <div class="flex flex-col items-center mt-[-350px]">
                <img src="{{ asset('images/DOH-logo.png') }}" class="h-60 w-auto" alt="DOH Logo">
            </div>
        </div>
    </div>

</div>

<script>
    const warning = "{{ session('warning') }}";
    if (warning) {
        Swal.fire({
            title: "Failed",
            text: warning,
            icon: "error"
        });
    }
    var message = "{{ session('success') }}";
    if (message) {
        Swal.fire({
            title: "Success",
            text: message,
            icon: "success"
        });
    }
</script>


</x-app-layout>

