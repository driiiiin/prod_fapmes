<div class="bg-[#FAF9F6] h-15 flex items-center">
    <div class="w-full flex flex-wrap">
        <div class="w-full flex items-center justify-center md:flex-row md:justify-start md:w-1/2 md:text-left lg:w-1/2 lg:text-left mt-1 mb-1 pb-3">
            <span class="text-xs">&copy;{{ date('Y') }} Department of Health. All rights reserved.</span>
            <img src="{{ asset('images/S-DOH-logo.png') }}" class="h-6 w-auto ml-2" alt="DOH Logo">
        </div>
        <div class="w-full flex items-center justify-center md:flex-row md:justify-end md:w-1/2 md:text-right lg:w-1/2 lg:text-right mt-1 mb-1">
            <span class="text-xs">
                Page load time: <span id="loadtime">0</span> seconds
            </span>
            <script>
                var time = performance.now();
                document.addEventListener("DOMContentLoaded", function() {
                    time = Math.round(performance.now() - time);
                    document.getElementById("loadtime").innerText = time / 1000 + " seconds";
                });
            </script>
        </div>
    </div>

    <!-- Back to Top Button -->
    <button id="backToTopBtn" title="Back to Top"
        style="display: none; position: fixed; bottom: 80px; right: 32px; z-index: 9999; background: #296D98; color: #fff; border: none; border-radius: 50%; width: 44px; height: 44px; box-shadow: 0 2px 8px rgba(20,83,45,0.18); cursor: pointer; transition: opacity 0.3s;">
        <i class="bi bi-arrow-up" style="font-size: 1.3rem;"></i>
    </button>
    <script>
        // Show/hide back to top button on scroll
        window.addEventListener('scroll', function() {
            var btn = document.getElementById('backToTopBtn');
            if (window.scrollY > 50) {
                btn.style.display = 'block';
            } else {
                btn.style.display = 'none';
            }
        });

        // Scroll to top when button is clicked
        document.getElementById('backToTopBtn').addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
</div>
