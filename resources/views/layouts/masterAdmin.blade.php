
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

        <!-- Kanban -->
        <link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" type="text/css" />

        <!-- Dropify -->
        <link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet">

        <!-- DataTables -->
        <link href="{{ asset('assets//plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

        <!-- Responsive datatable examples -->
        <link href="{{ asset('assets/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

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

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    </head>

    <body class="dark-sidenav">
        <!-- Left Sidenav -->
        <div class="left-sidenav">
            <!-- LOGO -->
            <div class="brand">
                <a href="{{ route('adminDashboard') }}" class="logo">
                    <span>
                        <img src="{{ asset('assets/images/current-tech-logo-white.png') }}" alt="logo-large"  width="40%" height="90%">
                    </span>
                </a>
            </div>
            <!--end logo-->
            <div class="menu-content h-100" data-simplebar>
                @php
                    $titles = App\Models\Title::all();
                    $subtitles = App\Models\Subtitle::with('title')
                                                    ->join('titles', 'subtitles.title_id', '=', 'titles.id')
                                                    ->orderBy('titles.t_sequence')
                                                    ->orderBy('subtitles.s_sequence')
                                                    ->get();
                    $supportCategories = App\Models\SupportCategory::all();
                    $ticketStatuses = App\Models\TicketStatus::all();
                    $projects = App\Models\Project::all();
                @endphp

                <ul class="metismenu left-sidenav-menu">

                    <li class="menu-label mt-0">Main</li>
                    <li>
                        <a href="{{ route('adminDashboard') }}"> <i data-feather="home" class="align-self-center menu-icon"></i><span>Dashboard</span></a>
                    </li>

                    <li>
                        <a href="{{ route('ticket') }}"> <i data-feather="sliders" class="align-self-center menu-icon"></i><span>Ticket</span></a>
                    </li>

                    {{-- <li>
                        <a href="javascript: void(0);"><i data-feather="list" class="align-self-center menu-icon"></i><span>Ticket - List</span><span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                        <ul class="nav-second-level" aria-expanded="false">
                            @foreach ($ticketStatuses as $status)
                                <li class="nav-item"><a class="nav-link" href="{{ route('ticketSumm', $status) }}"><i class="ti-control-record"></i>{{ $status->status }}</a></li>
                            @endforeach
                                <li class="nav-item"><a class="nav-link" href="{{ route('unassignedTicket') }}"><i class="ti-control-record"></i>Unassigned</a></li>
                        </ul>
                    </li> --}}

                    @if (Auth::user()->role_id == 1)
                        <li>
                            <a href="javascript: void(0);"><i data-feather="grid" class="align-self-center menu-icon"></i><span>Category</span><span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="nav-second-level" aria-expanded="false">
                                @foreach ($supportCategories as $supportCategory)
                                    <li class="nav-item"><a class="nav-link" href="{{ route('categorySumm', $supportCategory)}}"><i class="ti-control-record"></i>{!! $supportCategory->category_name !!}</a></li>
                                @endforeach
                            </ul>
                        </li>

                        <li>
                            <a href="javascript: void(0);"><i data-feather="dollar-sign" class="align-self-center menu-icon"></i><span>Project</span><span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="nav-second-level" aria-expanded="false">
                                @foreach ($projects as $project)
                                    <li class="nav-item"><a class="nav-link" href="{{ route('projectTicket', $project)}}"><i class="ti-control-record"></i>{!! $project->project_name !!}</a></li>
                                @endforeach
                            </ul>
                        </li>

                    @endif

                    <li>
                        <a href="{{ route('helpdesk') }}"><i data-feather="command" class="align-self-center menu-icon"></i><span>Report - Helpdesk</span></a>
                    </li>

                    @if (Auth::user()->role_id == 1 || (Auth::user()->role_id !== 1 && Auth::user()->manage_ticket_in_category == 1))
                        <li>
                            <a href="{{ route('performance') }}"><i data-feather="award" class="align-self-center menu-icon"></i><span>Performance</span></a>
                        </li>
                    @endif

                    <hr class="hr-dashed hr-menu">
                    <li class="menu-label my-2">Project</li>

                    @if (Auth::user()->role_id == 1 || (Auth::user()->role_id !== 1 && Auth::user()->manage_support_tool == 1))
                        <li>
                            <a href="{{ route('supportTool') }}"><i data-feather="tool" class="align-self-center menu-icon"></i><span>Support Tools</span></a>
                        </li>
                    @endif

                    <li>
                        <a href="{{ route('enhancement') }}"><i data-feather="file-text" class="align-self-center menu-icon"></i><span>Enhancement</span></a>
                    </li>

                    <li>
                        <a href="{{ route('invoice') }}"><i data-feather="shopping-bag" class="align-self-center menu-icon"></i><span>Invoice</span></a>
                    </li>


                    @if (Auth::user()->role_id == 1 || (Auth::user()->role_id !== 1 && Auth::user()->manage_documentation == 1))
                        <hr class="hr-dashed hr-menu">
                        <li class="menu-label my-2">Documentation</li>
                        @foreach ($projects as $project)
                            <li>
                                <a href="{{ route('titleSumm', $project) }}"><i data-feather="zap" class="align-self-center menu-icon"></i><span>{{ $project->project_name }}</span></a>
                            </li>
                        @endforeach
                    @endif

                    @if (Auth::user()->role_id == 1)
                        <hr class="hr-dashed hr-menu">
                        <li class="menu-label my-2">Administration</li>

                        <li>
                            <a href="{{ route('projectSumm') }}"><i data-feather="pocket" class="align-self-center menu-icon"></i><span>Project List</span></a>
                        </li>

                        <li>
                            <a href="{{ route('supportCategory') }}"><i data-feather="file-text" class="align-self-center menu-icon"></i><span>Support Category</span></a>
                        </li>

                        <li>
                            <a href="{{ route('ticketStatus') }}"> <i data-feather="layers" class="align-self-center menu-icon"></i><span>Status</span></a>
                        </li>



                        <li>
                            <a href="{{ route('adminSumm') }}"> <i data-feather="user" class="align-self-center menu-icon"></i><span>Admin</span></a>
                        </li>
                    @endif
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
                        <li class="dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-light nav-user mr-3" data-toggle="dropdown" href="#" role="button"
                                aria-haspopup="false" aria-expanded="false">
                                <span class="ml-1 nav-user-name hidden-sm">{{ auth()->user()->name }}</span>
                                @if (auth()->user()->profile_picture)
                                    <img src="{{ asset('storage/profilePicture/' . auth()->user()->profile_picture) }}" alt="profile-user" class="rounded-circle" />
                                @endif

                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{ route('profile') }}"><i data-feather="user" class="align-self-center icon-xs icon-dual mr-1"></i> Profile</a>
                                <a class="dropdown-item" href="{{ route('emailSignature') }}"><i data-feather="edit-3" class="align-self-center icon-xs icon-dual mr-1"></i> Email Signature</a>
                                {{-- <a class="dropdown-item" href="#"><i data-feather="settings" class="align-self-center icon-xs icon-dual mr-1"></i> Settings</a> --}}
                                <div class="dropdown-divider mb-0"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">

                                    <i data-feather="power" class="align-self-center icon-xs icon-dual mr-1">
                                    </i> Logout
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
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

        {{-- <script src="{{ asset('assets/plugins/tiny-editable/mindmup-editabletable.js') }}"></script>
        <script src="{{ asset('assets/plugins/tiny-editable/numeric-input-example.js') }}"></script>
        <script src="{{ asset('assets/plugins/bootable/bootstable.js') }}"></script>
        <script src="{{ asset('assets/pages/jquery.tabledit.init.js') }}"></script> --}}

        <script src="{{ asset('assets/plugins/dropify/js/dropify.min.js') }}"></script>
        <script src="{{ asset('assets/pages/jquery.form-upload.init.js') }}"></script>

        <!--Wysiwig js-->
        <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
        <script src="{{ asset('assets/pages/jquery.form-editor.init.js') }}"></script>

        <!-- Required datatable js -->
        <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

        <!-- Buttons examples -->
        <script src="{{ asset('assets/plugins/datatables/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables/jszip.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables/pdfmake.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables/vfs_fonts.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables/buttons.print.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables/buttons.colVis.min.js') }}"></script>
        <!-- Responsive examples -->
        <script src="{{ asset('assets/plugins/datatables/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('assets/pages/jquery.datatable.init.js') }}"></script>

        <script src="{{ asset('assets/plugins/repeater/jquery.repeater.min.js') }}"></script>
        <script src="{{ asset('assets/pages/jquery.form-repeater.js') }}"></script>

        <!-- Kanban -->
        <script src="{{ asset('assets/plugins/dragula/dragula.min.js') }}"></script>
        <script src="{{ asset('assets/pages/jquery.dragula.init.js') }}"></script>

        <!-- App js -->
        <script src="{{ asset('assets/js/app.js') }}"></script>


        {{-- Excel --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>

    </body>

</html>
