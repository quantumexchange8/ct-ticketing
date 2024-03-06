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
            .box-1 {
                width: 100%;
            }

            .box-2 {
                height: 10%;
            }

            .box-3 {
                height: 50%;
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
        </style>
    </head>

    <body class="account-body accountbg">

        <!-- Login page -->
        {{-- <div class="container">
            <div class="row">
                <div class="col-lg-5 mx-auto">
                    <h1 class="font-54 font-weight-bold mt-5 mb-4 text-center">CT-Ticketing</h1>
                </div><!--end col-->
            </div>
            <div class="row">
                @foreach ($projects as $project)
                <div class="col-lg-3 mx-auto">
                    <h4 class="text-center">{{ $project->project_name }}</h4>
                </div><!--end col-->
                @endforeach
            </div>
        </div><!--end container--> --}}
        <!-- End Login page -->

        <div class="box-1">
            <div class="box-2">
                <div class="col-lg-5 mx-auto">
                    <h1 class="font-54 font-weight-bold mt-5 mb-4 text-center">CT-Ticketing</h1>
                </div><!--end col-->
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
                @if(session('error'))
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
