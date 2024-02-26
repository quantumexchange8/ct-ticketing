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
                                                    <label for="title-name">Name</label>
                                                    <input type="text" class="form-control" name="name" placeholder="Enter Name" autocomplete="off" value="{{ $user->name }}">
                                                    @error('name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="title-name">Email</label>
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
                                                    <label for="title-name">Username</label>
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
                                                    <label for="title-name">Old Password</label>
                                                    <input type="password" class="form-control" name="oldpassword" placeholder="Enter Old Password" autocomplete="off">
                                                    @error('oldpassword')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="title-name">New Password</label>
                                                    <input type="password" class="form-control" name="newpassword" placeholder="Enter New Password" autocomplete="off">
                                                    @error('newpassword')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="title-name">Retype Password</label>
                                                    <input type="password" class="form-control" name="retypepassword" placeholder="Enter Retype Password" autocomplete="off">
                                                    @error('retypepassword')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 text-right">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>

                                    </form>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!-- container -->

                <footer class="footer text-center text-sm-left">
                    &copy; 2020 Dastyle <span class="d-none d-sm-inline-block float-right">Crafted with <i class="mdi mdi-heart text-danger"></i> by Mannatthemes</span>
                </footer><!--end footer-->
            </div>
            <!-- end page content -->


@endsection
