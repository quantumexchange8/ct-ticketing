<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>Dastyle - Admin & Dashboard Template</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

        <!-- App css -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    </head>

    <body class="account-body accountbg">

        <!-- Login page -->
        <div class="container">
            <div class="row vh-100 d-flex justify-content-center">
                <div class="col-12 align-self-center">
                    <div class="row">
                        <div class="col-lg-5 mx-auto">
                            <div class="card">
                                <div class="card-body p-0 auth-header-box">
                                    <div class="text-center p-3">
                                        <a href="index.html" class="logo logo-admin">
                                            <img src="{{ asset('assets/images/logo-sm.png') }}" height="50" alt="logo" class="auth-logo">
                                        </a>
                                        <h4 class="mt-3 mb-1 font-weight-semibold text-white font-18">Welcome to Helpdesk-Support</h4>
                                        {{-- <p class="text-muted  mb-0">Sign in to continue to Dastyle.</p> --}}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ul class="nav-border nav nav-pills" role="tablist">
                                        <li class="nav-item">
                                            <a class="font-weight-semibold font-16" >Log In</a>
                                        </li>
                                    </ul>
                                     <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div class="tab-pane active px-3 pt-3" role="tabpanel">
                                            <form class="form-horizontal auth-form my-4" action="{{ route('loginPost')}}" method="POST">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="username">Username</label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" name="username" id="username" placeholder="Enter username">
                                                    </div>
                                                </div><!--end form-group-->

                                                <div class="form-group">
                                                    <label for="userpassword">Password</label>
                                                    <div class="input-group mb-3">
                                                        <input type="password" class="form-control" name="password" id="userpassword" placeholder="Enter password">
                                                    </div>
                                                </div><!--end form-group-->

                                                <div class="form-group mb-0 row">
                                                    <div class="col-12 mt-2">
                                                        <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">Login <i class="fas fa-sign-in-alt ml-1"></i></button>
                                                    </div><!--end col-->
                                                </div> <!--end form-group-->
                                            </form><!--end form-->
                                            {{-- <div class="m-3 text-center text-muted">
                                                <p class="">Don't have an account ?  <a href="auth-register.html" class="text-primary ml-2">Free Register</a></p>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
        <!-- End Login page -->




        <!-- jQuery  -->
        <script src="{{ assert('assets/js/jquery.min.js') }}"></script>
        <script src="{{ assert('assets/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ assert('assets/js/waves.js') }}"></script>
        <script src="{{ assert('assets/js/feather.min.js') }}"></script>
        <script src="{{ assert('assets/js/simplebar.min.js') }}"></script>


    </body>

</html>
