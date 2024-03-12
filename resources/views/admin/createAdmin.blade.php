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
                            <h4 class="page-title">Create Admin</h4>
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('addAdmin') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            {{-- @if($errors->any())
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif --}}
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" name="name" placeholder=" Enter Name" autocomplete="off" value="{{ old('name') }}">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" name="username" placeholder="Enter Username" autocomplete="off" value="{{  old('username') }}">
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
                                        <input type="email" class="form-control" name="email" placeholder=" Enter Email" autocomplete="off" value="{{ old('email') }}">
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="phone_number">Phone Number</label>
                                        <input type="text" class="form-control" name="phone_number" placeholder="Enter Phone Number" autocomplete="off" value="{{ old('phone_number') }}">
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
                                        <select class="form-control" name="category_id" value="{{ old('category_id') }}">
                                            <option value="">Select Category</option>
                                            {{-- <option value="0">All</option> --}}
                                            @foreach($supportCategories as $supportCategory)
                                                <option value="{{ $supportCategory->id }}" {{ old('category_id') == $supportCategory->id ? 'selected' : '' }}>
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
                                        <input type="text" class="form-control" name="position" placeholder="Enter Position" autocomplete="off" value="{{ old('position') }}">
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

                                        <input type="text" class="form-control" name="whatsapp_me" placeholder="Ex: wa.link/u64q5m" autocomplete="off">
                                        @error('whatsapp_me')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="telegram_username">Telegram Username</label>
                                        <input type="text" class="form-control" name="telegram_username" placeholder="Ex: amberlee_415" autocomplete="off">
                                        @error('telegram_username')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" name="password" placeholder="********" autocomplete="off">
                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">

                                    <div class="form-group">
                                        <label for="profile_picture">Profile Picture</label>
                                        <div>
                                            <input type="file" class="theme-input-style" name="profile_picture" autocomplete="off" >
                                        </div>

                                        @error('profile_picture')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6" style="display: none;">
                                    <div class="form-group">
                                        <label for="role_id">Role</label>
                                        <select class="form-control" id="role" name="role_id" value="{{ old('role_id') }}">
                                            {{-- <option value="">Select Role</option> --}}
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
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

                                {{-- <div class="row all-ticket">
                                    <div class="col-xl-4" style="display: none;">
                                        <div class="checkbox checkbox-primary">
                                            <input id="manage-all-ticket" name="manage_all_ticket" class="privilege-checkbox" type="checkbox" value="1">
                                            <label for="manage-all-ticket">
                                                Able to manage all ticket
                                            </label>
                                        </div>
                                    </div>
                                </div> --}}

                                <div class="row ticket-management-row" >
                                    <div class="col-xl-4">
                                        <div class="checkbox checkbox-primary">
                                            <input id="manage-ticket-in-category" name="manage_ticket_in_category" class="privilege-checkbox" type="checkbox" value="1">
                                            <label for="manage-ticket-in-category">
                                                Able to manage ticket in category
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="checkbox checkbox-primary">
                                            <input id="manage-own-ticket" name="manage_own_ticket" class="privilege-checkbox" type="checkbox" value="1">
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
                                            <input id="manage_documentation" name="manage_documentation" class="privilege-checkbox" type="checkbox" value="1">
                                            <label for="manage_documentation">
                                                Able to manage documentation
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="checkbox checkbox-primary">
                                            <input id="manage_support_tool" name="manage_support_tool" class="privilege-checkbox" type="checkbox" value="1">
                                            <label for="manage_support_tool">
                                                Able to manage support tool
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="row">
                                    <div class="col-xl-4">
                                        <div class="checkbox checkbox-primary">
                                            <input id="manage-subtitle" name="manage_subtitle" class="privilege-checkbox" type="checkbox" value="1">
                                            <label for="manage-subtitle">
                                                Able to manage subtitle
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="checkbox checkbox-primary">
                                            <input id="manage-subcategory" name="manage_support_subcategory" class="privilege-checkbox" type="checkbox" value="1">
                                            <label for="manage-subcategory">
                                                Able to manage support subcategory
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-4">
                                        <div class="checkbox checkbox-primary">
                                            <input id="manage-content" name="manage_content" class="privilege-checkbox" type="checkbox" value="1">
                                            <label for="manage-content">
                                                Able to manage content
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="checkbox checkbox-primary">
                                            <input id="manage-status" name="manage_status" class="privilege-checkbox" type="checkbox" value="1">
                                            <label for="manage-status">
                                                Able to manage status
                                            </label>
                                        </div>
                                    </div>
                                </div> --}}
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var roleSelect = document.getElementById('role');
        var privilegeCheckboxes = document.querySelectorAll('.privilege-checkbox');
        var ticketManagementRow = document.querySelector('.ticket-management-row');
        // var manageAllTicketCheckbox = document.querySelector('.all-ticket'); // Add this line

        // Disable privilege checkboxes initially
        privilegeCheckboxes.forEach(function(checkbox) {
            checkbox.disabled = true;
        });

        roleSelect.addEventListener('change', function() {
            var roleId = roleSelect.value;
            var isRole1 = roleId === '1';
            var isNotRole1 = !isRole1; // Define isNotRole1 variable

            // Toggle visibility of ticket management row based on role ID
            ticketManagementRow.style.display = isRole1 ? 'none' : 'flex';
            // Toggle visibility of "Manage All Ticket" checkbox based on role ID
            // manageAllTicketCheckbox.style.display = isNotRole1 ? 'none' : 'block'; // Hide if role ID is not 1

            privilegeCheckboxes.forEach(function(checkbox) {
                checkbox.disabled = roleId === '';
                checkbox.checked = isRole1;
                checkbox.disabled = isRole1;
            });
        });

        privilegeCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                if (roleSelect.value === '1') {
                    checkbox.checked = true;
                }
            });
        });

        // Initial check for role ID on page load
        var initialRoleId = roleSelect.value;
        var initialIsRole1 = initialRoleId === '1';
        ticketManagementRow.style.display = initialIsRole1 ? 'none' : 'flex';
        // manageAllTicketCheckbox.style.display = !initialIsRole1 ? 'none' : 'block'; // Hide initially if role ID is not 1
        privilegeCheckboxes.forEach(function(checkbox) {
            checkbox.checked = initialIsRole1;
            checkbox.disabled = initialRoleId === '';
        });
    });
</script>

@endsection
