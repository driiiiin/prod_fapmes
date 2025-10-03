<header class="fixed top-0 left-0 right-0 bg-[#296D98] h-12 z-50">
    <div class="flex items-center h-full px-4 lg:px-0">
        @auth
            <button type="button" class="navbar-toggle collapsed focus:outline-none mr-4" data-toggle="collapse" id="toggle-sidebar">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        <div class="flex items-center h-full space-x-2">
            <a href="#">
            <!-- <a href="https://www.doh.gov.ph/" target="_blank"> -->
            <img src="{{ asset('images/DOH-logo.png') }}" alt="DOH Logo" class="h-10 w-auto" style="height: 40px; width: 40px;">
            </a>
            <a href="#">
            <!-- <a href="https://www.bagongpilipinastayo.com/" target="_blank" class="d-inline-block"> -->
            <img src="{{ asset('images/BP-logo.png') }}" alt="Bagong Pilipinas Logo" class="h-10 w-auto" style="height: 55px; width: 55px;">
            </a>
            <a href="#">
            <!-- <a href="https://www.who.int/philippines/" target="_blank" class="d-inline-block"> -->
            <img src="{{ asset('images/WHO-logo.png') }}" alt="WHO Logo" class="h-10 w-auto" style="margin-right: 10px; height: 45px; width: 45px; filter: brightness(0) invert(1);">
            </a>
        </div>
        @endauth
        <a href="/" class="font-bold text-white block md:hidden">
            FAPMES
        </a>
        <a href="/" class="font-bold text-white hidden md:block">
            Foreign Assisted Projects (FAPs) Monitoring and Evaluation System
        </a>
        <div class="flex items-center justify-end h-full flex-1 mr-2">
        @auth
            <span class="text-white mx-2">
                Welcome,
                <a href="{{ route('profile.edit') }}" class="hover:text-gray-200">
                    {{ Auth::user()->username }}
                </a>
            </span>

            <a class="nav-link text-white pl-10" style="font-size: 16px;" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out text-white"></i> Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @endauth
        </div>
    </div>
</header>

