<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>CT-Ticketing</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/current-tech-logo-white.png') }}">

        <!-- App css -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

        <style>
            section {
                height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .box-1 {
                width: 100%;
                height: 100%;
            }

            .box-2 {
                height: 10%;
                display: flex;
                align-items: center;
            }

            .box-3 {
                height: 90%;
                display:flex;
                justify-content:space-evenly;
                gap:20px;
                padding-left:20px;
                padding-right:20px;
            }

            .box-4 {
                display: flex;
                align-content: center;
                flex-wrap: wrap;
            }

            .box-5 {
                width: 90%;
            }

            .box-6 {
                width: 10%;
            }

            .box-7 {
                width: 100%
            }

        </style>
    </head>

    <body class="account-body accountbg">

        <div class="container">
            <section class="one">
                <div class="box-1">
                    <div class="box-2">
                        <div class="box-5">
                            <div class="col-lg-5 mx-auto">
                                <h1 class="font-54 font-weight-bold mt-5 mb-4 text-center">CT-Ticketing</h1>
                            </div><!--end col-->
                        </div>
                        <div class="box-6">
                            <a href="#SubmitTicket">
                                <button type="button" class="btn btn-primary waves-effect waves-light">Submit Ticket</button>
                            </a>

                        </div>

                    </div>
                    <div class="box-3">
                        @foreach ($projects as $project)
                        <div class="box-4">
                            <a href="{{ route('selectProject', ['projectId' => $project->id]) }}">
                                <h4 class="text-center">{{ $project->project_name }}</h4>
                            </a>
                        </div><!--end col-->
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="two" id="SubmitTicket">
                <div class="box-7">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card my-4">
                                <div class="card-header">
                                    <h4 class="card-title">Submit Ticket</h4>
                                </div><!--end card-header-->
                                <div class="card-body">
                                    <form action="{{route('submitTicket')}}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="sender_name">Full Name</label>
                                                    <input type="text" class="form-control" id="username" name="sender_name" autocomplete="off" value="{{ old('sender_name') }}" required>
                                                    @error('sender_name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="sender_email">Email Address</label>
                                                    <input type="email" class="form-control" id="useremail" name="sender_email" autocomplete="off" value="{{ old('sender_email') }}" required>
                                                    @error('sender_email')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="p_name">Project</label>
                                                    <input type="text" class="form-control" name="p_name" autocomplete="off" value="{{ old('p_name') }}">
                                                    @error('p_name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="category_id">Category</label>
                                                    <select class="form-control" name="category_id" required>
                                                        <option value="">Select Category</option>
                                                        @foreach($supportCategories as $supportCategory)
                                                            <option value="{{ $supportCategory->id }}" {{ old('category_id') == $supportCategory->id ? 'selected' : '' }}>{!! $supportCategory->category_name !!}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('category_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="subject">Subject</label>
                                                    <input type="text" class="form-control" id="subject" name="subject" autocomplete="off" value="{{ old('subject') }}" required>
                                                    @error('subject')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="priority">Priority</label>
                                                    <select class="form-control" name="priority" required>
                                                        <option value="">Select Priority</option>
                                                        <option value="Low" {{ old('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                                                        <option value="Medium" {{ old('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
                                                        <option value="High" {{ old('priority') == 'High' ? 'selected' : '' }}>High</option>
                                                    </select>
                                                    @error('priority')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="message">Message</label>
                                                    <textarea class="form-control" rows="3" id="message" name="message" autocomplete="off" value="{{ old('message') }}"  required></textarea>
                                                    @error('title_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <fieldset>
                                            <div class="repeater-default">
                                                <div data-repeater-list="car">
                                                    <div data-repeater-item="">
                                                        <div class="form-group row d-flex align-items-end">

                                                            <div class="col-sm-11">
                                                                {{-- <input type="file" id="input-file-now" name="t_image" class="dropify" /> --}}
                                                                <input type="file" id="input-file-now" name="car[][t_image]">

                                                            </div><!--end col-->

                                                            <div class="col-sm-1">
                                                                <span data-repeater-delete="" class="btn btn-danger">
                                                                    <span class="far fa-trash-alt mr-1"></span> Delete
                                                                </span>
                                                            </div><!--end col-->
                                                        </div><!--end row-->
                                                    </div><!--end /div-->
                                                </div><!--end repet-list-->
                                                <div class="form-group mb-0 row float-right">
                                                    <div class="col-sm-12">
                                                        <span data-repeater-create="" class="btn btn-secondary">
                                                            <span class="fas fa-plus"></span> Add
                                                        </span>
                                                        <button type="submit" class="btn btn-primary px-4">Send Message</button>
                                                    </div><!--end col-->
                                                </div><!--end row-->
                                            </div> <!--end repeter-->
                                        </fieldset><!--end fieldset-->
                                    </form>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->
                    </div><!--end row-->
                </div>
            </section>

        </div>


        <!-- jQuery  -->
        <script src="{{ assert('assets/js/jquery.min.js') }}"></script>
        <script src="{{ assert('assets/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ assert('assets/js/waves.js') }}"></script>
        <script src="{{ assert('assets/js/feather.min.js') }}"></script>
        <script src="{{ assert('assets/js/simplebar.min.js') }}"></script>

        <!-- Sweet-Alert  -->
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
                        timer: 1000, // 3000 milliseconds (3 seconds)
                        showConfirmButton: false, // Hide the "OK" button
                    });
                @endif
            });
        </script>


    </body>

</html>
