<x-app-layout>

<div class="container-fluid mt-4 pt-4" style="width: 90%;">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="text-left mb-0"><strong>USER ACCOUNTS</strong></h4>
            <a href="{{ route('register') }}" class="btn btn-sm ms-2 d-flex align-items-center" title="Add New User Account" style="padding: 0 5px; font-size: small;">
                Add User Account
                <img src="{{ asset('images/add-button.png') }}" width="20" height="20" alt="Add New User Account" class="ms-1">
            </a>
        </div>
        <!-- Button Container -->
         <div>
            <div class="d-flex justify-content-start mb-2 mt-2 ml-2" id="button-container">
            </div>
         </div>

        <div class="card-body pt-0" >
            <div class="table-responsive">
                <table id="useraccountTable">
                     <thead>
                        <style>
                        .user-row:hover { background-color: #C6F7D0 !important; }
                        .bg-unapproved { background-color: #FFC5C5 !important; }
                        </style>
                        <tr>
                            <th style="font-size: small; background-color: #296D98; color: white;" class="text-center">Actions</th>
                            @if (Auth()->user()->userlevel == -1)
                            <th style="font-size: small; background-color: #296D98; color: white; line-height: 1;" class="text-center">Logged-in</th>
                            @endif
                            <th style="font-size: small; background-color: #296D98; color: white; line-height: 1;" class="text-center">No.</th>
                            <th style="font-size: small; background-color: #296D98; color: white; line-height: 1;" class="text-center">Username</th>
                            <th style="font-size: small; background-color: #296D98; color: white; line-height: 1;" class="text-center">Email</th>
                            <th style="font-size: small; background-color: #296D98; color: white; line-height: 1;" class="text-center">Last Name</th>
                            <th style="font-size: small; background-color: #296D98; color: white; line-height: 1;" class="text-center">First Name</th>
                            <th style="font-size: small; background-color: #296D98; color: white; line-height: 1;" class="text-center">Middle Name</th>
                            <th style="font-size: small; background-color: #296D98; color: white; line-height: 1;" class="text-center">Mobile No.</th>
                            <th style="font-size: small; background-color: #296D98; color: white; line-height: 1;" class="text-center">Name of Organization</th>
                            <th style="font-size: small; background-color: #296D98; color: white; line-height: 1;" class="text-center">Approved</th>
                            <th style="font-size: small; background-color: #296D98; color: white; line-height: 1;" class="text-center">Status</th>
                            <th style="font-size: small; background-color: #296D98; color: white; line-height: 1;" class="text-center">Userlevel</th>
                            <th style="font-size: small; background-color: #296D98; color: white; line-height: 1;" class="text-center d-none">Session</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($useraccounts->sortByDesc('created_at') as $useraccount)
                            <tr class="user-row {{ $useraccount->is_approved == 0 ? 'bg-unapproved' : '' }}">
                            <td style="width: 120px;" class="text-center">
                                <div style="display: flex; justify-content: center; align-items: center; gap: 10px;">
                                    <a href="{{ route('useraccount.show', $useraccount->id) }}" title="View User Account Details">
                                        <img src="{{ asset('images/view.png') }}" width="15" height="15" alt="View" />
                                    </a>
                                    @if (Auth()->user()->userlevel == -1 || Auth()->user()->userlevel == 2 )
                                    <a href="{{ route('useraccount.edit', $useraccount->id) }}" title="Edit User Account">
                                        <img src="{{ asset('images/edit.png') }}" width="15" height="15" alt="Edit" />
                                    </a>
                                    @endif
                                    @if (Auth()->user()->userlevel == -1)
                                    <form action="{{ route('useraccount.destroy', $useraccount->id) }}" method="POST" style="display:inline;" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="delete-button" title="Delete User Account" style="background:none; border:none; padding:0;">
                                            <img src="{{ asset('images/delete.png') }}" width="15" height="15" alt="Delete" />
                                        </button>
                                    </form>
                                </div>
                                <script>
                                    document.querySelectorAll('.delete-button').forEach(button => {
                                        button.addEventListener('click', function(event) {
                                            event.preventDefault();
                                            const form = this.closest('.delete-form');
                                            Swal.fire({
                                                title: '<span style="font-size: medium;">Are you sure you want to delete this user account permanently?</span>',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#3085d6',
                                                cancelButtonColor: '#d33',
                                                cancelButtonText: '<span style="font-size: smaller;">Cancel</span>',
                                                confirmButtonText: '<span style="font-size: smaller;">Yes, delete it!</span>'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    form.submit();
                                                }
                                            });
                                        });
                                    });
                                </script>
                                @endif
                            </td>
                                @if (Auth()->user()->userlevel == -1)
                                <td style="font-size: small;" class="text-center">
                                    @php $isOnline = isset($activeUserIds) && in_array($useraccount->id, $activeUserIds); @endphp
                                    @if ($isOnline)
                                        <span class="badge bg-success" title="User currently online">Online</span>
                                        @if ($useraccount->id == Auth()->id())
                                            <span class="text-muted">(You)</span>
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                @endif
                                <td style="font-size: small;" class="text-center">{{ $useraccounts->count() - $loop->index }}</td>
                                <td style="font-size: small;" class="text-center">{{ $useraccount->username }}</td>
                                <td style="font-size: small;" class="text-center">{{ $useraccount->email }}</td>
                                <td style="font-size: small;" class="text-center">{{ $useraccount->lastname }}</td>
                                <td style="font-size: small;" class="text-center">{{ $useraccount->firstname }}</td>
                                <td style="font-size: small;" class="text-center">{{ $useraccount->middlename }}</td>
                                <td style="font-size: small;" class="text-center">{{ substr($useraccount->mobile, 0, 4) . '-' . substr($useraccount->mobile, 4, 3) . '-' . substr($useraccount->mobile, 7) }}</td>
                                <td style="font-size: small;" class="text-center">{{ $useraccount->organization }}</td>
                                <td style="font-size: small;" class="text-center">{{ $useraccount->is_approved == 0 ? 'No' : 'Yes' }}</td>
                                <td style="font-size: small;" class="text-center">{{ $useraccount->is_active == 0 ? 'Inactive' : 'Active' }}</td>
                                <td style="font-size: small;" class="text-center">
                                    {{ DB::table('ref_userlevels')->where('userlevel_code', $useraccount->userlevel)->value('userlevel_desc') }}
                                </td>
                                <td style="font-size: small;" class="text-center d-none">{{ $useraccount->session }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
                                                        icon: "error",
                                                        showCancelButton: false,
                                                        showConfirmButton: false,
                                                        timer: 3000
                                                    });
                                                }
                                                var message = "{{ session('success') }}";
                                                if (message) {
                                                    Swal.fire({
                                                        title: "Success",
                                                        text: message,
                                                        icon: "success",
                                                        showCancelButton: false,
                                                        showConfirmButton: false,
                                                        timer: 3000
                                                    });
                                                }
                                                var info = "{{ session('info') }}";
                                                if (info) {
                                                    Swal.fire({
                                                        title: "Info",
                                                        text: info,
                                                        icon: "info",
                                                        showCancelButton: false,
                                                        showConfirmButton: false,
                                                        timer: 3000
                                                    });
                                                }
                                            </script>
                                            <script>
                                                $(document).ready(function() {
                                                    $('.delete-record').click(function(event) {
                                                        event.preventDefault(); // Prevent the default form submission
                                                        var form = $(this).closest('form');
                                                        Swal.fire({
                                                            title: 'Are you sure?',
                                                            text: "You won't be able to revert this!",
                                                            icon: 'warning',
                                                            showCancelButton: true,
                                                            confirmButtonColor: '#3085d6',
                                                            cancelButtonColor: '#d33',
                                                            confirmButtonText: 'Yes, delete it!'
                                                        }).then((result) => {
                                                            if (result.isConfirmed) {
                                                                form.submit(); // Submit the form if confirmed
                                                            }
                                                        });
                                                    });
                                                });
                                            </script>

<script>
    $(document).ready(function() {
        // Initialize the DataTable
        var useraccountTable = $('#useraccountTable').DataTable({
            pageLength: 25
        });

        // Add button functionality
        $('#copy-button').on('click', function() {
            useraccountTable.button('.buttons-copy').trigger();
        });

        $('#csv-button').on('click', function() {
            useraccountTable.button('.buttons-csv').trigger();
        });

        $('#excel-button').on('click', function() {
            useraccountTable.button('.buttons-excel').trigger();
        });

        $('#pdf-button').on('click', function() {
            useraccountTable.button('.buttons-pdf').trigger();
        });

        $('#print-button').on('click', function() {
            useraccountTable.button('.buttons-print').trigger();
        });

        // Initialize buttons
        new $.fn.dataTable.Buttons(useraccountTable, {
            buttons: [
                {
                    extend: 'copy',
                    text: 'Copy',
                    className: 'buttons-copy'
                },
                {
                    extend: 'csv',
                    text: 'CSV',
                    className: 'buttons-csv'
                },

                {
                    extend: 'excel',
                    text: 'Excel',
                    className: 'buttons-excel'
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    className: 'buttons-pdf'
                },
                {
                    extend: 'print',
                    text: 'Print',
                    className: 'buttons-print'
                }
            ]
        }).container().appendTo('#button-container'); // Append buttons to the custom container
    });

</script>
</x-app-layout>

