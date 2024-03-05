
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
                @php
                    $titles = App\Models\Title::all();
                    $subtitles = App\Models\Subtitle::with('title')
                                                    ->join('titles', 'subtitles.title_id', '=', 'titles.id')
                                                    ->orderBy('titles.t_sequence')
                                                    ->orderBy('subtitles.s_sequence')
                                                    ->get();
                    $supportCategories = App\Models\SupportCategory::all();
                    $ticketStatuses = App\Models\TicketStatus::all();
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
                    @endif

                    {{-- @if (Auth::user()->role_id == 1) --}}
                        <li>
                            <a href="{{ route('helpdesk') }}"><i data-feather="command" class="align-self-center menu-icon"></i><span>Report - Helpdesk</span></a>
                        </li>
                    {{-- @endif --}}

                    @if (Auth::user()->role_id == 1 || (Auth::user()->role_id !== 1 && Auth::user()->manage_ticket_in_category == 1))
                        <li>
                            <a href="{{ route('performance') }}"><i data-feather="award" class="align-self-center menu-icon"></i><span>Performance</span></a>
                        </li>
                    @endif

                    <hr class="hr-dashed hr-menu">
                    <li class="menu-label my-2">Administration</li>

                    @if (Auth::user()->manage_title == 1)
                        <li>
                            <a href="{{ route('titleSumm') }}"> <i data-feather="slack" class="align-self-center menu-icon"></i><span>Title</span></a>
                        </li>
                    @endif

                    @if (Auth::user()->manage_subtitle == 1)
                        <li>
                            <a href="javascript: void(0);"><i data-feather="wind" class="align-self-center menu-icon"></i><span>Subtitle</span><span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="nav-second-level" aria-expanded="false">
                                @foreach ($titles as $title)
                                    <li class="nav-item"><a class="nav-link" href="{{ route('subtitleSumm', $title) }}"><i class="ti-control-record"></i>{{ $title->title_name }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @endif

                    @if (Auth::user()->manage_content == 1)
                        <li>
                            <a href="javascript: void(0);"><i data-feather="file-text" class="align-self-center menu-icon"></i><span>Content</span><span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="nav-second-level" aria-expanded="false">
                                @foreach ($titles as $title)
                                <li class="nav-item">
                                    <a class="nav-link" href="javascript: void(0);"><i class="ti-control-record"></i><span>{{ $title->title_name }}</span><span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                                    <ul class="nav-second-level" aria-expanded="false">
                                        @foreach ($title->subtitles as $subtitle)
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('contentSumm', $subtitle) }}">
                                                <i class="ti-control-record"></i>{{ $subtitle->subtitle_name }}
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif

                    @if (Auth::user()->manage_support_category == 1)
                        <li>
                            <a href="{{ route('supportCategorySumm') }}"><i data-feather="tool" class="align-self-center menu-icon"></i><span>Support Tool</span></a>
                        </li>
                    @endif

                    @if (Auth::user()->manage_status == 1)
                        <li>
                            <a href="{{ route('ticketStatus') }}"> <i data-feather="layers" class="align-self-center menu-icon"></i><span>Status</span></a>
                        </li>
                    @endif


                    @if (Auth::user()->role_id == 1)
                        <li>
                            <a href="{{ route('adminSumm') }}"> <i data-feather="user" class="align-self-center menu-icon"></i><span>Admin</span></a>
                        </li>
                    @endif

                    <hr class="hr-dashed hr-menu">
                    <li class="menu-label my-2">Preview Documentation</li>

                    @foreach ($titles as $title)
                        <li>
                            <a href="{{ route('viewContent', $title) }}">
                                <i data-feather="paperclip" class="align-self-center menu-icon"></i>
                                <span>{{ $title->title_name }}</span>
                                {{-- <span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span> --}}
                            </a>
                            {{-- <ul class="nav-second-level" aria-expanded="false">
                                @foreach ($title->contents()->orderBy('c_sequence')->get() as $content)
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('viewContent', $title) }}">
                                            <i class="ti-control-record"></i>{{ $content->subtitle_name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul> --}}
                        </li>
                    @endforeach

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

                        {{-- <li class="dropdown notification-list">

                            @php
                                $tickets = App\Models\Ticket::join('ticket_statuses', 'ticket_statuses.id', 'tickets.status_id')
                                                            ->where('ticket_statuses.status', '=', 'Pending')
                                                            ->select('tickets.id as ticket_id', 'tickets.created_at')
                                                            ->get();

                                    $pendingTicketsMoreThanOneDay = $tickets->filter(function ($ticket) {
                                        return now()->diffInDays($ticket->created_at) > 1;
                                    })->pluck('ticket_id');

                            @endphp

                            <a class="nav-link dropdown-toggle arrow-none waves-light waves-effect" data-toggle="dropdown" href="#" role="button"
                                aria-haspopup="false" aria-expanded="false">
                                <i data-feather="bell" class="align-self-center topbar-icon"></i>
                                <span class="badge badge-danger badge-pill noti-icon-badge">2</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-lg pt-0">

                                <h6 class="dropdown-item-text font-15 m-0 py-3 border-bottom d-flex justify-content-between align-items-center">
                                    Notifications <span class="badge badge-primary badge-pill">2</span>
                                </h6>
                                <div class="notification-menu" data-simplebar>
                                    <!-- item-->
                                    <a href="#" class="dropdown-item py-3">
                                        <small class="float-right text-muted pl-2">2 min ago</small>
                                        <div class="media">
                                            <div class="avatar-md bg-soft-primary">
                                                <i data-feather="shopping-cart" class="align-self-center icon-xs"></i>
                                            </div>
                                            <div class="media-body align-self-center ml-2 text-truncate">
                                                <h6 class="my-0 font-weight-normal text-dark">Your order is placed</h6>
                                                <small class="text-muted mb-0">Dummy text of the printing and industry.</small>
                                            </div><!--end media-body-->
                                        </div><!--end media-->
                                    </a><!--end-item-->
                                    <!-- item-->
                                    <a href="#" class="dropdown-item py-3">
                                        <small class="float-right text-muted pl-2">10 min ago</small>
                                        <div class="media">
                                            <div class="avatar-md bg-soft-primary">
                                                <img src="assets/images/users/user-4.jpg" alt="" class="thumb-sm rounded-circle">
                                            </div>
                                            <div class="media-body align-self-center ml-2 text-truncate">
                                                <h6 class="my-0 font-weight-normal text-dark">Meeting with designers</h6>
                                                <small class="text-muted mb-0">It is a long established fact that a reader.</small>
                                            </div><!--end media-body-->
                                        </div><!--end media-->
                                    </a><!--end-item-->
                                    <!-- item-->
                                    <a href="#" class="dropdown-item py-3">
                                        <small class="float-right text-muted pl-2">40 min ago</small>
                                        <div class="media">
                                            <div class="avatar-md bg-soft-primary">
                                                <i data-feather="users" class="align-self-center icon-xs"></i>
                                            </div>
                                            <div class="media-body align-self-center ml-2 text-truncate">
                                                <h6 class="my-0 font-weight-normal text-dark">UX 3 Task complete.</h6>
                                                <small class="text-muted mb-0">Dummy text of the printing.</small>
                                            </div><!--end media-body-->
                                        </div><!--end media-->
                                    </a><!--end-item-->
                                    <!-- item-->
                                    <a href="#" class="dropdown-item py-3">
                                        <small class="float-right text-muted pl-2">1 hr ago</small>
                                        <div class="media">
                                            <div class="avatar-md bg-soft-primary">
                                                <img src="assets/images/users/user-5.jpg" alt="" class="thumb-sm rounded-circle">
                                            </div>
                                            <div class="media-body align-self-center ml-2 text-truncate">
                                                <h6 class="my-0 font-weight-normal text-dark">Your order is placed</h6>
                                                <small class="text-muted mb-0">It is a long established fact that a reader.</small>
                                            </div><!--end media-body-->
                                        </div><!--end media-->
                                    </a><!--end-item-->
                                    <!-- item-->
                                    <a href="#" class="dropdown-item py-3">
                                        <small class="float-right text-muted pl-2">2 hrs ago</small>
                                        <div class="media">
                                            <div class="avatar-md bg-soft-primary">
                                                <i data-feather="check-circle" class="align-self-center icon-xs"></i>
                                            </div>
                                            <div class="media-body align-self-center ml-2 text-truncate">
                                                <h6 class="my-0 font-weight-normal text-dark">Payment Successfull</h6>
                                                <small class="text-muted mb-0">Dummy text of the printing.</small>
                                            </div><!--end media-body-->
                                        </div><!--end media-->
                                    </a><!--end-item-->
                                </div>
                                <!-- All-->
                                <a href="javascript:void(0);" class="dropdown-item text-center text-primary">
                                    View all <i class="fi-arrow-right"></i>
                                </a>
                            </div>
                        </li> --}}

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
