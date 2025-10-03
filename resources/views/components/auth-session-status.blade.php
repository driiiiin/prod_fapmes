@props(['status'])

@php
    // Check for both 'status' and 'success' session keys
    $message = $status ?? session('success') ?? session('status');
@endphp

@if ($message)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ $message }}',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#6C63FF',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                });
            } else {
                // Fallback if SweetAlert2 is not loaded
                console.log('Session Message: {{ $message }}');
            }
        });
    </script>
@endif
