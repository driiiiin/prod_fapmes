@auth
<aside id="sidebar" style="min-height: 100vh; width: 250px; position: fixed; padding-top: 60px;" class="sidenav bg-dark">
    <ul class="nav flex-column">
        <!-- <li class="nav-item">
            <a class="nav-link d-flex align-items-center text-white" style="font-size: 16px;" href="{{ route('introduction') }}"><i class="fa fa-home text-white mr-2"></i>
                <span class="font-weight-bold">Introduction</span>
            </a>
        </li> -->

        @if (in_array(auth()->user()->userlevel, [-1, 2, 5, 6]))
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center text-white" style="font-size: 16px;" href="{{ route('dashboard') }}"><i class="fa fa-dashboard text-white mr-2"></i>
                <span class="font-weight-bold">Dashboard</span>
            </a>
        </li>
        @endif


        <li class="nav-item">
            <a class="nav-link active text-white" style="font-size: 16px;" href="#"><i class="fa fa-table text-white mr-1" style="font-size: 12px;"></i>Project Information Management</a>
            <ul class="nav flex-column ml-3 d-none" id="section5">
                <li class="nav-item"><a class="nav-link text-gray-500" style="font-size: 14px;" href="{{ route('projects.index') }}"><i class="fa fa-ellipsis-h mr-2"></i> Progress Data Update</a></li>
            </ul>
        </li>

        @if (in_array(auth()->user()->userlevel, [-1, 2, 5, 6]))
        <li class="nav-item">
            <a class="nav-link text-white" style="font-size: 16px;" href="#"><i class="fa fa-briefcase text-white mr-1" style="font-size: 12px;"></i> Section 1: <br> Implementation Status</a>
            <ul class="nav flex-column ml-3 d-none" id="section1">
                <li class="nav-item"><a class="nav-link text-gray-500" style="font-size: 14px;" href="{{ route('fapslist') }}"><i class="fa fa-eye mr-2"></i> FAPs List</a></li>
                <!-- <li class="nav-item"><a class="nav-link text-gray-500" style="font-size: 14px;" href="{{ route('introduction') }}"><i class="fa fa-line-chart mr-2" style="font-size: 12px;"></i> Performance Evaluation</a></li>
                <li class="nav-item"><a class="nav-link text-gray-500" style="font-size: 14px;" href="{{ route('introduction') }}"><i class="fa fa-exclamation-triangle mr-2"></i> Risk Alarm</a></li> -->
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white" style="font-size: 16px;" href="#"><i class="fa fa-map text-white mr-1" style="font-size: 12px;"></i> Section 2: <br> Project Distribution</a>
            <ul class="nav flex-column ml-3 d-none" id="section2">
                <li class="nav-item"><a class="nav-link text-gray-500" style="font-size: 14px;" href="{{ route('geographic_distribution') }}"><i class="fa fa-location-arrow mr-2" style="font-size: 12px;"></i> Geographic Distribution</a></li>
                <!-- <li class="nav-item"><a class="nav-link text-gray-500" style="font-size: 14px;" href="/"><i class="fa fa-sitemap mr-2" style="font-size: 12px;"></i>Health Area Distribution</a></li> -->
                <li class="nav-item"><a class="nav-link text-gray-500" style="font-size: 14px;" href="{{ route('health_area_distribution') }}"><i class="fa fa-sitemap mr-2" style="font-size: 12px;"></i>Health Area Distribution</a></li>
                <li class="nav-item"><a class="nav-link text-gray-500" style="font-size: 14px;" href="{{ route('overall_area_distribution') }}"><i class="fa fa-calculator mr-2" style="font-size: 12px;"></i> Overall Distribution</a></li>
                <!-- @if (auth()->user()->userlevel == -1)
                <li class="nav-item"><a class="nav-link text-gray-500" style="font-size: 14px;" href="{{ route('overall_area_distribution.report') }}"><i class="fa fa-bar-chart mr-2" style="font-size: 12px;"></i> Comprehensive Report</a></li>
                @endif -->
            </ul>
        </li>

        <!-- <li class="nav-item">
            <a class="nav-link text-white" style="font-size: 16px;" href="#"><i class="fa fa-line-chart text-white mr-1" style="font-size: 12px;"></i> Section 3: <br> Trend Review</a>
            <ul class="nav flex-column ml-3 d-none" id="section3">
                <li class="nav-item"><a class="nav-link text-gray-500" style="font-size: 14px;" href="{{ route('health_area') }}"> <i class="fa fa-heartbeat mr-2"></i> Health Area</a></li>
                <li class="nav-item"><a class="nav-link text-gray-500" style="font-size: 14px;" href="{{ route('funding_source') }}"> <i class="fa fa-money mr-2"></i> Funding Source</a></li>
            </ul>
        </li> -->


        <!-- <li class="nav-item">
            <a class="nav-link active text-white" style="font-size: 16px;" href="/"><i class="fa fa-dashboard text-white"></i> Dashboard</a>
            <ul class="nav flex-column ml-3">
                <li class="nav-item"><a class="nav-link text-gray-500" style="font-size: 14px;" href="#">link1</a></li>
                <li class="nav-item"><a class="nav-link text-gray-500" style="font-size: 14px;" href="#">link2</a></li>
            </ul>
        </li> -->

        <!-- <li class="nav-item">
            <a class="nav-link active text-white" style="font-size: 16px;" href="#"><i class="fa fa-file-o text-white mr-1" style="font-size: 12px;"></i> Section 3: <br> Project Reporting</a>
            <ul class="nav flex-column ml-3 d-none" id="section4">
                <li class="nav-item"><a class="nav-link text-gray-500" style="font-size: 14px;" href="{{ route('report') }}"><i class="fa fa-calendar mr-2"></i>  Reports</a></li> -->
                <!-- <li class="nav-item"><a class="nav-link text-gray-500" style="font-size: 14px;" href="/"><i class="fa fa-calendar mr-2"></i> Yearly Reports</a></li>
                <li class="nav-item"><a class="nav-link text-gray-500" style="font-size: 14px;" href="/"><i class="fa fa-calendar mr-2"></i> Special Reports</a></li> -->
            <!-- </ul>
        </li> -->
        @endif

        @if (Auth::user()->userlevel == -1 || Auth::user()->userlevel == 2)
        <li class="nav-item">
            <a class="nav-link active text-white" style="font-size: 16px;" href="#"><i class="fa fa-user text-white mr-1" style="font-size: 12px;"></i>Account Management</a>
            <ul class="nav flex-column ml-3 d-none" id="section5">
                <li class="nav-item"><a class="nav-link text-gray-500" style="font-size: 14px;" href="{{ route('useraccount.index') }}"><i class="fa fa-user mr-2"></i> User Accounts</a></li>
            </ul>
        </li>
        @endif

        <!-- <li class="nav-item">
            <a class="nav-link text-gray-500" style="font-size: 16px;" href="/"><i class="fa fa-cloud-download text-gray-500"></i> Overview</a>
        </li> -->
        <!-- <li class="nav-item">
            <a class="nav-link text-gray-500" style="font-size: 16px;" href="/"><i class="fa fa-cart-plus text-gray-500"></i> Events</a>
        </li> -->
        <!-- <li class="nav-item">
            <a class="nav-link text-gray-500" style="font-size: 16px;" href="/"><i class="fa fa-wrench text-gray-500"></i> Services</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-gray-500" style="font-size: 16px;" href="/"><i class="fa fa-server text-gray-500"></i> Contact</a>
        </li> -->
        <li class="nav-item">
            <a class="nav-link text-white" style="font-size: 16px;" href="{{ route('about') }}"><i class="fa fa-info-circle text-white"></i> About</a>
        </li>
        <!-- <li class="nav-item">
            <a class="nav-link text-white" style="font-size: 16px;" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out text-white"></i> Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li> -->
    </ul>
            <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const sidebar = document.querySelector('.sidebar');
                        const mainContent = document.getElementById('main-content');
                        const toggleButton = document.getElementById('toggle-sidebar');

                        toggleButton.addEventListener('click', function () {
                            sidebar.classList.toggle('active');
                            mainContent.classList.toggle('sidebar-active');
                        });
                    });
            </script>
</aside>
@endauth


