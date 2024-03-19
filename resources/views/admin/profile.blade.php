@extends('layouts.masterAdmin')
@section('content')

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


<!-- Page Content-->
<div class="page-content">
    <div class="container-fluid">
        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="row">
                        <div class="col">
                            <h4 class="page-title">User Profile</h4>
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <!-- end page title end breadcrumb -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('updateProfile', $user->id) }}" method="POST" enctype="multipart/form-data">
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
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" name="email" placeholder="Enter Email" autocomplete="off" value="{{ $user->email }}">
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" name="username" placeholder="Enter Username" autocomplete="off" value="{{ $user->username }}">
                                        @error('username')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="category_id">Category</label>
                                        <input type="text" class="form-control" name="category_id" value="{{ $user->supportCategories->category_name ?? 'All'}}" readonly>
                                        {{-- <select class="form-control" name="category_id" disabled>
                                        <option value="0" {{ $user->category_id == 0 ? 'selected' : '' }}>All</option>
                                        @foreach($supportCategories as $supportCategory)
                                            <option value="{{ $supportCategory->id }}" {{ $user->category_id == $supportCategory->id ? 'selected' : '' }}>
                                                {!! $supportCategory->category_name !!}
                                            </option>
                                        @endforeach
                                        </select> --}}
                                        @error('category_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="oldpassword">Old Password</label>
                                        <input type="password" class="form-control" name="oldpassword" placeholder="Enter Old Password" autocomplete="off">
                                        @error('oldpassword')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="newpassword">New Password</label>
                                        <input type="password" class="form-control" name="newpassword" placeholder="Enter New Password" autocomplete="off">
                                        @error('newpassword')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="retypepassword">Retype Password</label>
                                        <input type="password" class="form-control" name="retypepassword" placeholder="Enter Retype Password" autocomplete="off">
                                        @error('retypepassword')
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
                                                        <a href="{{ asset('storage/profilePicture/' . $profile_picture) }}" class="file-modal-link">
                                                            @if ($profile_picture)
                                                                <img src="{{ asset('storage/profilePicture/' . $profile_picture) }}" style="width: 100%; height: 100%">
                                                            @endif
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-right mt-2">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div><!--end card-body-->
                </div><!--end card-->
            </div><!--end col-->
        </div><!--end row-->
    </div><!-- container -->
</div>
<!-- end page content -->

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

<!-- Include jQuery if it's not already included -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

{{-- Sweet Alert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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
