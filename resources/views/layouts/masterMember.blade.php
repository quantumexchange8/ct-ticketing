
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>CT-Ticketing</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/current-tech-logo-white.png') }}">

        <link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet">

        <!-- App css -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/jquery-ui.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/metisMenu.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />


        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Allura&family=Courgette&family=Grand+Hotel&family=Great+Vibes&family=Inter:wght@500&family=Parisienne&family=Sacramento&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>

    <body class="dark-sidenav">
        <!-- Left Sidenav -->
        <div class="left-sidenav">
            <!-- LOGO -->
            <div class="brand">
                <a href="#" class="logo">
                    {{-- <span>
                        <img src="{{ asset('assets/images/logo-sm.png') }}" alt="logo-small" class="logo-sm">
                    </span>
                    <span>
                        <img src="{{ asset('assets/images/logo.png') }}" alt="logo-large" class="logo-lg logo-light">
                        <img src="{{ asset('assets/images/logo-dark.png') }}" alt="logo-large" class="logo-lg logo-dark">
                    </span> --}}
                    <span>
                        <img src="{{ asset('assets/images/current-tech-logo-white.png') }}" alt="logo-large"  width="40%" height="90%">
                    </span>
                </a>
            </div>
            <!--end logo-->
            <div class="menu-content h-100" data-simplebar>
                <ul class="metismenu left-sidenav-menu">

                    <li class="menu-label mt-0">Main</li>
                    {{-- <li>
                        <a href="{{ route('dashboard') }}"> <i data-feather="home" class="align-self-center menu-icon"></i><span>Dashboard</span></a>
                    </li> --}}
                    @php
                        $titles = App\Models\Title::all();
                    @endphp

                    <li>
                        <a href="javascript: void(0);"><i data-feather="file-text" class="align-self-center menu-icon"></i><span>Documentation</span><span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                        <ul class="nav-second-level" aria-expanded="false">
                            @foreach ($titles as $title)
                                <li class="nav-item"><a class="nav-link" href="{{ route('documentation', $title) }}"><i class="ti-control-record"></i>{{ $title->title_name }}</a></li>
                            @endforeach
                        </ul>
                    </li>

                    <li>
                        <a href="{{ route('support') }}"> <i data-feather="info" class="align-self-center menu-icon"></i><span>Support Tools</span></a>
                    </li>

                    <li>
                        <a href="{{ route('openTicket') }}"> <i data-feather="send" class="align-self-center menu-icon"></i><span>Submit a Ticket</span></a>
                    </li>

                </ul>

            </div>
        </div>
        <!-- end left-sidenav-->

        <div class="page-wrapper">
            <!-- Top Bar Start -->
            <div class="topbar">
                <!-- Navbar -->
                <nav class="navbar-custom">
                    <ul class="list-unstyled topbar-nav float-right mb-0">
                        {{-- <li class="dropdown hide-phone">
                            <a class="nav-link dropdown-toggle arrow-none waves-light waves-effect" data-toggle="dropdown" href="#" role="button"
                                aria-haspopup="false" aria-expanded="false">
                                <i data-feather="search" class="topbar-icon"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right dropdown-lg p-0">
                                <!-- Top Search Bar -->
                                <div class="app-search-topbar">
                                    <form action="#" method="get">
                                        <input type="search" name="search" class="from-control top-search mb-0" placeholder="Type text...">
                                        <button type="submit"><i class="ti-search"></i></button>
                                    </form>
                                </div>
                            </div>
                        </li> --}}

                        <li class="dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-light nav-user mr-3" data-toggle="dropdown" href="#" role="button"
                                aria-haspopup="false" aria-expanded="false">
                                {{-- <span class="ml-1 nav-user-name hidden-sm">{{ auth()->user()->name }}</span> --}}
                                {{-- <img src="assets/images/users/user-5.jpg" alt="profile-user" class="rounded-circle" /> --}}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"><i data-feather="user" class="align-self-center icon-xs icon-dual mr-1"></i> Profile</a>
                                <a class="dropdown-item" href="#"><i data-feather="settings" class="align-self-center icon-xs icon-dual mr-1"></i> Settings</a>
                                <div class="dropdown-divider mb-0"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">>

                                    <i data-feather="power" class="align-self-center icon-xs icon-dual mr-1">
                                    </i> Logout
                                    {{-- <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form> --}}
                                </a>
                            </div>
                        </li>
                    </ul><!--end topbar-nav-->

                    <ul class="list-unstyled topbar-nav mb-0">
                        <li>
                            <button class="nav-link button-menu-mobile">
                                <i data-feather="menu" class="align-self-center topbar-icon"></i>
                            </button>
                        </li>
                        {{-- <li class="creat-btn">
                            <div class="nav-link">
                                <a class=" btn btn-sm btn-soft-primary" href="#" role="button"><i class="fas fa-plus mr-2"></i>New Task</a>
                            </div>
                        </li> --}}
                    </ul>
                </nav>
                <!-- end navbar-->
            </div>
            <!-- Top Bar End -->

            <!-- Page Content-->
                @yield('content')
            <!-- end page content -->
        </div>
        <!-- end page-wrapper -->

        <!-- jQuery  -->
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/js/metismenu.min.js') }}"></script>
        <script src="{{ asset('assets/js/waves.js') }}"></script>
        <script src="{{ asset('assets/js/feather.min.js') }}"></script>
        <script src="{{ asset('assets/js/simplebar.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('assets/js/moment.js') }}"></script>
        <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>

        {{-- <script src="{{ asset('assets/plugins/apex-charts/apexcharts.min.js') }}"></script> --}}
        {{-- <script src="{{ asset('assets/pages/jquery.helpdesk-dashboard.init.js') }}"></script> --}}

        <script src="{{ asset('assets/plugins/tiny-editable/mindmup-editabletable.js') }}"></script>
        {{-- <script src="{{ asset('assets/plugins/tiny-editable/numeric-input-example.js') }}"></script> --}}
        <script src="{{ asset('assets/plugins/bootable/bootstable.js') }}"></script>
        {{-- <script src="{{ asset('assets/pages/jquery.tabledit.init.js') }}"></script> --}}

        <script src="{{ asset('assets/plugins/dropify/js/dropify.min.js') }}"></script>
        <script src="{{ asset('assets/pages/jquery.form-upload.init.js') }}"></script>

        <script src="{{ asset('assets/plugins/repeater/jquery.repeater.min.js') }}"></script>
        <script src="{{ asset('assets/pages/jquery.form-repeater.js') }}"></script>

        <!-- App js -->
        <script src="{{ asset('assets/js/app.js') }}"></script>

    </body>

</html>
