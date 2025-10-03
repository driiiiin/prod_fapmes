<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FAPMES') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap 5.3 and icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <!-- Font Awesome for logos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.2/css/buttons.dataTables.css">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        #loading-overlay {
            position: fixed;
            z-index: 9999;
            top: 0; left: 0; right: 0; bottom: 0;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.3s;
        }
        #loading-overlay.hidden {
            opacity: 0;
            pointer-events: none;
        }
        .dot-spinner {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 64px;
        }
        .dot-spinner .dot {
            width: 16px;
            height: 16px;
            margin: 0 6px;
            background: #3498db;
            border-radius: 50%;
            display: inline-block;
            animation: dot-bounce 0.8s infinite ease-in-out both;
        }
        .dot-spinner .dot:nth-child(2) {
            animation-delay: 0.16s;
        }
        .dot-spinner .dot:nth-child(3) {
            animation-delay: 0.32s;
        }
        @keyframes dot-bounce {
            0%, 80%, 100% {
                transform: scale(0.7);
                opacity: 0.7;
            }
            40% {
                transform: scale(1.2);
                opacity: 1;
            }
        }
    </style>
</head>


<body>
    <!-- Session Status for SweetAlert2 notifications -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div id="loading-overlay" style="display:none;">
        <div class="dot-spinner">
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </div>

    @include('partials.header')
    <div class="sidebar" style="overflow-y:auto; height:100vh;">
        @include('partials.sidebarnav')
    </div>

    <!-- Welcome Modal -->
    @if(session('show_welcome_modal'))
    <div id="welcomeModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity duration-300">
        <div class="relative w-full max-w-6xl mx-4 transform transition-all duration-300 scale-100">
            <div class="relative overflow-hidden rounded-2xl shadow-2xl">
                <!-- Green Gradient Background -->
                <div class="absolute inset-0 bg-gradient-to-br from-green-700 via-emerald-800 to-teal-800"></div>

                <!-- Animated Pattern Overlay with Subtle Shimmer -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute inset-0" style="background-image: radial-gradient(circle at 20% 50%, white 1px, transparent 1px), radial-gradient(circle at 80% 80%, white 1px, transparent 1px); background-size: 50px 50px;"></div>
                </div>

                <!-- Floating Orbs -->
                <div class="absolute top-10 left-10 w-32 h-32 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute bottom-20 right-20 w-40 h-40 bg-emerald-300/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>

                <!-- Close Button -->
                <!-- <button onclick="closeWelcomeModal()" class="absolute top-4 right-4 z-10 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-full p-2 transition-all duration-200 group">
                    <svg class="w-6 h-6 text-white group-hover:rotate-90 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button> -->

                <!-- Content -->
                <div class="relative px-8 py-16 sm:px-12 sm:py-18 lg:px-16">
                    <div class="mx-auto text-center">
                        <!-- Icon with Enhanced Animation -->
                        <div class="flex justify-center mb-6">
                            <div class="relative">
                                <div class="absolute inset-0 bg-white/30 backdrop-blur-sm rounded-full blur-xl animate-pulse"></div>
                                <div class="relative bg-white/20 backdrop-blur-sm rounded-full p-6 shadow-lg ring-2 ring-white/30">
                                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Main Heading -->
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-black text-white mb-4 tracking-tight drop-shadow-lg leading-tight">
                            Welcome<br>to the<br>Department of Health
                        </h1>
                        <h2 class="text-xl sm:text-2xl lg:text-3xl xl:text-4xl font-bold text-white mb-6 drop-shadow-lg">
                            Foreign Assisted Project Monitoring and Evaluation System
                        </h2>
                        <div class="inline-block bg-white/20 backdrop-blur-sm rounded-full px-8 py-3 mb-8 ring-2 ring-white/30 shadow-xl">
                            <p class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white tracking-wider">
                                FAPMES
                            </p>
                        </div>

                        <!-- Description -->
                        <div class="mx-auto mb-10 max-w-4xl">
                            <p class="text-base sm:text-lg lg:text-xl text-white/95 leading-relaxed font-medium drop-shadow bg-white/10 backdrop-blur-sm rounded-2xl px-8 py-4 border border-white/20">
                                The FAPMES is a system designed to track and manage DOH Official Development Assistance (ODA) or Foreign Assisted Projects (FAPs).
                            </p>
                        </div>

                        <!-- Decorative Line with Enhanced Design -->
                        <div class="flex justify-center items-center space-x-2 mb-8">
                            <div class="h-1 w-12 bg-white/50 rounded-full shadow-sm"></div>
                            <div class="h-1.5 w-8 bg-white/70 rounded-full shadow-sm"></div>
                            <div class="h-1 w-4 bg-white/90 rounded-full shadow-sm"></div>
                        </div>

                        <!-- Get Started Button with Modern Design -->
                        <button onclick="closeWelcomeModal()" class="bg-white text-green-600 hover:bg-green-50 font-bold py-4 px-12 text-lg rounded-full shadow-2xl hover:shadow-green-200/50 transform hover:scale-105 transition-all duration-200 ring-2 ring-white/50 hover:ring-white">
                            <span class="flex items-center justify-center space-x-3">
                                <span>Get Started</span>
                                <svg class="w-6 h-6 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Bottom Wave Decoration -->
                <div class="absolute bottom-0 left-0 right-0">
                    <svg class="w-full h-16 text-white/10" preserveAspectRatio="none" viewBox="0 0 1200 120" fill="currentColor">
                        <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25"></path>
                        <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5"></path>
                        <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Page Content -->
    <div class="container-fluid">
        <button id="toggle-sidebar">Toggle Sidebar</button>
        <main id="main-content" class="transition-all duration-300">
        {{ $slot }}

            <!-- Start of footer -->
            <footer class="pt-12">
                @include('partials.footer')
            </footer>
        </main>
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script src="{{ asset('js/app.js') }}"></script>

    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/dataTables.buttons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.print.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.js"></script>

    <script>
        @php use Illuminate\Support\Str; @endphp
        $(document).ready(function() {
            // Initialize DataTables only if the elements exist
            if ($('#useraccountTable').length) {
                var useraccountTable = new DataTable('#useraccountTable');
            }
            if ($('#projects-table').length) {
                var projectstable = new DataTable('#projects-table');
            }
            if ($('#implementation-table').length) {
                var implementationTable = new DataTable('#implementation-table');
            }
            if ($('#level-table').length) {
                var levelTable = new DataTable('#level-table');
            }
            if ($('#financial-table').length) {
                var financialTable = new DataTable('#financial-table');
            }
            if ($('#physical-table').length) {
                var physicalTable = new DataTable('#physical-table');
            }
            if ($('#fundingsource-table').length) {
                var fundingsourceTable = new DataTable('#fundingsource-table');
            }
            if ($('#distribution-table').length) {
                var distributionTable = new DataTable('#distribution-table');
            }
            if ($('#manage-table').length) {
                var manageTable = new DataTable('#manage-table');
            }
            if ($('#management-table').length) {
                var managementTable = new DataTable('#management-table');
            }
            if ($('#depdev-table').length) {
                var depdevTable = new DataTable('#depdev-table');
            }
            if ($('#depdevclass-table').length) {
                var depdevclassTable = new DataTable('#depdevclass-table');
            }
            if ($('#gph-implemented-table').length) {
                var gphImplementedTable = new DataTable('#gph-implemented-table');
            }
            if ($('#level1-table').length) {
                var level1Table = new DataTable('#level1-table');
            }
            if ($('#level2-table').length) {
                var level2Table = new DataTable('#level2-table');
            }
            if ($('#level3-table').length) {
                var level3Table = new DataTable('#level3-table');
            }
            if ($('#type-of-funds-table').length) {
                var typeOfFundsTable = new DataTable('#type-of-funds-table');
            }
            if ($('#fundandmanagement-table').length) {
                var fundandmanagementTable = new DataTable('#fundandmanagement-table');
            }
            if ($('#datatable-table').length) {
                var datatableTable = new DataTable('#datatable-table');
            }

            // Toggle search inputs visibility
            $('#toggle-search').on('click', function() {
                $('.search-row').toggle();
            });
        });
    </script>
    <script>
        let timeout;

        function resetTimer() {
            clearTimeout(timeout);
            timeout = setTimeout(logoutUser , 3600000); // 1 hour (3,600,000 ms)
            // timeout = setTimeout(logoutUser , 60000);// 1 minute
            // timeout = setTimeout(logoutUser , 30000);// 30 sec
        }

        function logoutUser () {
            Swal.fire({
                title: 'Auto Logout',
                text: 'You have been automatically logged out due to inactivity. Please login again to continue.',
                icon: 'warning',
                showCancelButton: false,
                confirmButtonText: 'Login Again',
                allowEnterKey: true, // allow enter key to confirm
                didOpen: () => {
                    // Add keydown listener for Enter key
                    document.addEventListener('keydown', handleEnterKeyForSwal);
                },
                willClose: () => {
                    // Remove keydown listener to avoid memory leaks
                    document.removeEventListener('keydown', handleEnterKeyForSwal);
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    performLogout();
                }
            });
        }

        function handleEnterKeyForSwal(e) {
            // Only trigger if the SweetAlert2 modal is open and Enter is pressed
            if (e.key === 'Enter') {
                const swalConfirmBtn = document.querySelector('.swal2-confirm');
                if (swalConfirmBtn) {
                    swalConfirmBtn.click();
                }
            }
        }

        function performLogout() {
            // Create a form and submit it to logout
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('logout') }}';

            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            document.body.appendChild(form);
            form.submit();
        }

        // Reset timer on user activity
        window.onload = resetTimer;
        window.onmousemove = resetTimer;
        window.onkeydown = resetTimer;
        window.onscroll = resetTimer;
        window.onclick = resetTimer;
        window.ontouchmove = resetTimer;

    </script>
    <script>
        // Show overlay only if load takes longer than 300ms
        let overlayTimeout = setTimeout(function() {
            document.getElementById('loading-overlay').style.display = 'flex';
        }, 300);
        window.addEventListener('load', function() {
            clearTimeout(overlayTimeout);
            document.getElementById('loading-overlay').style.display = 'none';
        });
    </script>

    <script>
        // Welcome Modal Functions
        function closeWelcomeModal() {
            const modal = document.getElementById('welcomeModal');
            if (modal) {
                modal.classList.add('opacity-0');
                setTimeout(() => {
                    modal.style.display = 'none';
                    // Clear the session flag via AJAX
                    fetch('{{ route("clear-welcome-modal") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    });
                }, 300);
            }
        }

        // Show modal on page load if session flag is set
        window.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('welcomeModal');
            if (modal) {
                modal.style.display = 'flex';
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                }, 100);
            }
        });

        // Close modal when clicking outside
        document.getElementById('welcomeModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeWelcomeModal();
            }
        });
    </script>

</body>

</html>
