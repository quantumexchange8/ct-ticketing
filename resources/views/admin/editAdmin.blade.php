@extends('layouts.masterAdmin')
@section('content')

<!-- Page Content-->
<div class="page-content">
    <div class="container-fluid">
        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="row">
                        <div class="col">
                            <h4 class="page-title">Edit Admin</h4>
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('updateAdmin', $user->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" name="name" placeholder="Enter Name" autocomplete="off" value="{{ $user->name }}">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" name="username" placeholder="Enter Username" autocomplete="off" value="{{ $user->username }}">
                                        @error('username')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" name="email" placeholder=" Enter Email" autocomplete="off" value="{{ $user->email }}">
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="username">New Password</label>
                                        <input type="text" class="form-control" name="newpassword" placeholder="Enter new password" autocomplete="off">
                                        @error('newpassword')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6" style="display: none;">
                                    <div class="form-group">
                                        <label for="role_id">Role</label>
                                        <select class="form-control" name="role_id">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ $user->roles->id == $role->id ? 'selected' : '' }}>
                                                {!! $role->role_name !!}
                                            </option>
                                        @endforeach
                                        </select>
                                        @error('role_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="category_id">Category</label>
                                        <select class="form-control" name="category_id">
                                        {{-- <option value="0" {{ $user->category_id == 0 ? 'selected' : '' }}>All</option> --}}
                                        @foreach($supportCategories as $supportCategory)
                                            <option value="{{ $supportCategory->id }}" {{ $user->category_id == $supportCategory->id ? 'selected' : '' }}>
                                                {!! $supportCategory->category_name !!}
                                            </option>
                                        @endforeach
                                        </select>
                                        @error('category_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="privileges">
                                <div class="row">
                                    <div class="col mb-2" >
                                        <h4 class="card-title">Privileges - Main</h4>
                                    </div>
                                </div>

                                {{-- <div class="row all-ticket" style="display: none;">
                                    <div class="col-xl-4">
                                        <div class="checkbox checkbox-primary">
                                            <input id="manage-all-ticket" name="manage_all_ticket" class="privilege-checkbox" type="checkbox" value="1" <?php if ($user->manage_all_ticket == 1) echo 'checked'; ?>>
                                            <label for="manage-all-ticket">
                                                Able to manage all ticket
                                            </label>
                                        </div>
                                    </div>
                                </div> --}}

                                <div class="row ticket-management-row">
                                    <div class="col-xl-4">
                                        <div class="checkbox checkbox-primary">
                                            <input id="manage-ticket-in-category" name="manage_ticket_in_category" class="privilege-checkbox" type="checkbox" value="1" <?php if ($user->manage_ticket_in_category == 1) echo 'checked'; ?>>
                                            <label for="manage-ticket-in-category">
                                                Able to manage ticket in category
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="checkbox checkbox-primary">
                                            <input id="manage-own-ticket" name="manage_own_ticket" class="privilege-checkbox" type="checkbox" value="1" <?php if ($user->manage_own_ticket== 1) echo 'checked'; ?>>
                                            <label for="manage-own-ticket">
                                                Able to manage own ticket
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col mb-2" >
                                        <h4 class="card-title">Privileges - Administration</h4>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-4">
                                        <div class="checkbox checkbox-primary">
                                            <input id="manage-title" name="manage_title" class="privilege-checkbox" type="checkbox" value="1" <?php if ($user->manage_title == 1) echo 'checked'; ?>>
                                            <label for="manage-title">
                                                Able to manage title
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="checkbox checkbox-primary">
                                            <input id="manage-category" name="manage_support_category" class="privilege-checkbox" type="checkbox" value="1" <?php if ($user->manage_support_category == 1) echo 'checked'; ?>>
                                            <label for="manage-category">
                                                Able to manage support category
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-4">
                                        <div class="checkbox checkbox-primary">
                                            <input id="manage-subtitle" name="manage_subtitle" class="privilege-checkbox" type="checkbox" value="1" <?php if ($user->manage_subtitle == 1) echo 'checked'; ?>>
                                            <label for="manage-subtitle">
                                                Able to manage subtitle
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="checkbox checkbox-primary">
                                            <input id="manage-subcategory" name="manage_support_subcategory" class="privilege-checkbox" type="checkbox" value="1" <?php if ($user->manage_support_subcategory == 1) echo 'checked'; ?>>
                                            <label for="manage-subcategory">
                                                Able to manage support subcategory
                                            </label>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-xl-4">
                                        <div class="checkbox checkbox-primary">
                                            <input id="manage-content" name="manage_content" class="privilege-checkbox" type="checkbox" value="1" <?php if ($user->manage_content == 1) echo 'checked'; ?>>
                                            <label for="manage-content">
                                                Able to manage content
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="checkbox checkbox-primary">
                                            <input id="manage-status" name="manage_status" class="privilege-checkbox" type="checkbox" value="1" <?php if ($user->manage_status == 1) echo 'checked'; ?>>
                                            <label for="manage-status">
                                                Able to manage status
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-12 text-right">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div><!--end card-body-->
                </div><!--end card-->
            </div><!--end col-->
        </div><!--end row-->
    </div>
</div>
<!-- end page content -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            Swal.fire({
                title: 'Done',
                text: '{{ session('success') }}',
                icon: 'success',
                timer: 1000, // 3000 milliseconds (3 seconds)
                showConfirmButton: false, // Hide the "OK" button
            });
        @elseif(session('error'))
            Swal.fire({
                title: 'Error',
                text: '{{ session('error') }}',
                icon: 'error',
                timer: 1000, // 3000 milliseconds (3 seconds)
                showConfirmButton: false, // Hide the "OK" button
            });
        @endif
    });
</script>

{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        var roleSelect = document.querySelector('select[name="role_id"]');
        var privilegeCheckboxes = document.querySelectorAll('.privilege-checkbox');

        roleSelect.addEventListener('change', function() {
            // Disable privilege checkboxes when role with id 1 is selected
            var roleId = roleSelect.value;
            var disableCheckboxes = roleId === '1';

            privilegeCheckboxes.forEach(function(checkbox) {
                checkbox.checked = disableCheckboxes;
                checkbox.disabled = disableCheckboxes;
            });
        });

        privilegeCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                if (roleSelect.value === '1') {
                    checkbox.checked = true; // Ensure checkbox remains checked
                }
            });
        });
    });
</script> --}}

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var roleSelect = document.querySelector('select[name="role_id"]');
        // var allTicketRow = document.querySelector('.all-ticket');
        var ticketManagementRow = document.querySelector('.ticket-management-row');
        var privilegeCheckboxes = document.querySelectorAll('.privilege-checkbox');

        // Function to set initial visibility of ticket rows
        function setInitialVisibility() {
            var roleId = roleSelect.value;
            var isRole1 = roleId === '1';
            // allTicketRow.style.display = isRole1 ? 'block' : 'none';
            // ticketManagementRow.style.display = isRole1 ? 'none' : 'block';
        }

        // Set initial visibility of ticket rows on page load
        setInitialVisibility();

        // Function to handle role select change event
        function handleRoleSelectChange() {
            var roleId = roleSelect.value;
            var isRole1 = roleId === '1';
            // allTicketRow.style.display = isRole1 ? 'block' : 'none';
            // ticketManagementRow.style.display = isRole1 ? 'none' : 'block';

            // Disable privilege checkboxes when role with id 1 is selected
            privilegeCheckboxes.forEach(function(checkbox) {
                checkbox.checked = isRole1;
                checkbox.disabled = isRole1;
            });
        }

        // Add event listener for role select change
        roleSelect.addEventListener('change', handleRoleSelectChange);

        // Function to handle checkbox change event
        function handleCheckboxChange() {
            if (roleSelect.value === '1') {
                this.checked = true; // Ensure checkbox remains checked
            }
        }

        // Add event listener for privilege checkboxes change
        privilegeCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', handleCheckboxChange);
        });
    });
</script>


@endsection
