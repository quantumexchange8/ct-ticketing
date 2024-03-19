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
                                        <label for="phone_number">Phone Number</label>
                                        <input type="text" class="form-control" name="phone_number" placeholder="Enter Phone Number" autocomplete="off" value="{{ $user->phone_number }}">
                                        @error('phone_number')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="category_id">Category</label>
                                        <select class="form-control" name="category_id">
                                        <option value="0" {{ $user->category_id == 0 ? 'selected' : '' }}>All</option>
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
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="position">Position</label>
                                        <input type="text" class="form-control" name="position" placeholder="Enter Position" autocomplete="off" value="{{ $user->position }}">
                                        @error('position')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <div style="display: flex; flex-direction: row; align-items: baseline; gap: 5px;">
                                            <label for="whatsapp_me">WhatsApp Me</label><a href="https://create.wa.link/"><i class="fa-solid fa-circle-info" title="How to create whatsapp me link?"></i></a>
                                        </div>
                                        <input type="text" class="form-control" name="whatsapp_me" placeholder="Ex: wa.link/u64q5m" autocomplete="off" value="{{ $user->whatsapp_me }}">
                                        @error('whatsapp_me')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="telegram_username">Telegram Username</label>
                                        <input type="text" class="form-control" name="telegram_username" placeholder="Ex: amberlee_415" autocomplete="off" value="{{ $user->telegram_username }}">
                                        @error('telegram_username')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="username">New Password</label>
                                        <input type="text" class="form-control" name="newpassword" placeholder="Enter new password" autocomplete="off">
                                        @error('newpassword')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="profile_picture">Profile Picture</label>
                                                        <div>
                                                            <input type="file" class="theme-input-style" name="profile_picture" id="profilePictureFile">
                                                        </div>
                                                        @error('profile_picture')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div>
                                                        <a href="{{ asset('storage/profilePicture/' . $user->profile_picture) }}" class="file-modal-link">
                                                            @if ($user->profile_picture)
                                                                <img src="{{ asset('storage/profilePicture/' . $user->profile_picture) }}" style="width: 100%; height: 100%">
                                                            @endif
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
                            </div>

                            <div class="privileges">
                                <div class="row">
                                    <div class="col mb-2" >
                                        <h4 class="card-title">Privileges - Main</h4>
                                    </div>
                                </div>

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
                                        <h4 class="card-title">Privileges - Project</h4>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-4">
                                        <div class="checkbox checkbox-primary">
                                            <input id="manage_documentation" name="manage_documentation" class="privilege-checkbox" type="checkbox" value="1" <?php if ($user->manage_documentation == 1) echo 'checked'; ?>>
                                            <label for="manage_documentation">
                                                Able to manage documentation
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="checkbox checkbox-primary">
                                            <input id="manage_support_tool" name="manage_support_tool" class="privilege-checkbox" type="checkbox" value="1" <?php if ($user->manage_support_tool == 1) echo 'checked'; ?>>
                                            <label for="manage_support_tool">
                                                Able to manage support tool
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

{{-- Image Modal --}}
<div class="modal fade" id="fileModal" tabindex="-1" role="dialog" aria-labelledby="fileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 700px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fileModalLabel">File Preview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="profilePictureUrl" value="{{$user->profile_picture}}">
                <iframe id="fileViewer" src="" style="width: 100%; height: 80vh;" frameborder="0"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="deleteImageButton">Delete</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            Swal.fire({
                title: 'Done',
                text: '{{ session('success') }}',
                icon: 'success',
                timer: 1000,
                showConfirmButton: false,
            });
        @elseif(session('error'))
            Swal.fire({
                title: 'Error',
                text: '{{ session('error') }}',
                icon: 'error',
                timer: 1000,
                showConfirmButton: false,
            });
        @endif
    });
</script>

{{-- Privileges --}}
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

<!-- Include jQuery if it's not already included -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Add a script to your edit form view -->
<script>
    $(document).ready(function() {

        $('.file-modal-link').on('click', function(e) {
            e.preventDefault();
            var src = $(this).attr('href');
            $('#fileViewer').attr('src', src);
            $('#fileModal').modal('show');
        });

        $('#deleteImageButton').click(function() {
            // Get the profile picture URL from the input field
            var profilePictureUrl = $('#profilePictureUrl').val();

            Swal.fire({
                title: 'Are you sure?',
                text: 'This action will delete the profile picture.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If the user confirms the action, send AJAX request to delete the ticket
                    $.ajax({
                        url: '/delete-profile-picture',
                        type: 'DELETE',
                        data: {
                            imageUrl: profilePictureUrl // Pass profile picture URL to the controller
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Add CSRF token
                        },
                        success: function(response) {
                            // Handle success
                            location.reload(); // Reload the page after successful deletion
                        },
                        error: function(xhr, status, error) {
                            // Handle error
                            console.error(error);
                        }
                    });
                }
            });

            $('#fileModal').modal('hide');
        });

    });
</script>

@endsection
