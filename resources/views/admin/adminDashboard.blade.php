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
                            <h4 class="page-title">Dashboard</h4>
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <!-- end page title end breadcrumb -->

        <div class="row">
            <div class="col-lg-12">
                <div class="row">

                    @php
                        $icons = ['tag', 'package', 'check-circle', 'archive', 'activity'];
                        $iconIndex = 0;
                    @endphp

                    @foreach ($ticketStatuses as $ticketStatus)
                        <div class="col-md-6 col-lg-3">
                            <div class="card report-card">
                                <div class="card-body">
                                    <a href="{{ route('ticketSumm', ['status' => $ticketStatus->id]) }}">
                                        <div class="row d-flex justify-content-center">
                                            <div class="col">
                                                    <p class="text-dark mb-1 font-weight-semibold">{{ $ticketStatus->status }}</p>
                                                    <h3 class="my-0">{{ $ticketCounts[$ticketStatus->id]['total'] }}</h3>
                                            </div>
                                            <div class="col-auto align-self-center">
                                                <div class="report-main-icon bg-light-alt">
                                                    <i data-feather="{{ $icons[$iconIndex] }}" class="align-self-center text-muted icon-md"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="hr-dashed">
                                        <div class="text-center">
                                            <h6 class="text-primary bg-soft-danger p-3 mb-0 font-11 rounded">
                                                High: {{ $ticketCounts[$ticketStatus->id]['High'] }}
                                            </h6>
                                            <h6 class="text-primary bg-soft-warning p-3 mb-0 font-11 rounded">
                                                Medium: {{ $ticketCounts[$ticketStatus->id]['Medium'] }}
                                            </h6>
                                            <h6 class="text-primary bg-soft-primary p-3 mb-0 font-11 rounded">
                                                Low: {{ $ticketCounts[$ticketStatus->id]['Low'] }}
                                            </h6>
                                        </div>
                                    </a>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div> <!--end col-->

                        @php
                            $iconIndex = ($iconIndex + 1) % count($icons);
                        @endphp
                    @endforeach

                </div><!--end row-->
            </div><!-- end col-->
        </div><!--end row-->

        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-9">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h4 class="card-title">Unassigned Tickets</h4>
                                    </div><!--end col-->
                                </div>  <!--end row-->
                            </div><!--end card-header-->
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th class="border-top-0">Date</th>
                                                    <th class="border-top-0">Ticket No</th>
                                                    <th class="border-top-0">Category</th>
                                                    <th class="border-top-0">Priority</th>
                                                    <th class="border-top-0">Actions</th>
                                                </tr><!--end tr-->
                                            </thead>
                                            <tbody>
                                                @foreach ($unassignedTickets as $ticket)
                                                    <tr>
                                                        <td>{{ Carbon\Carbon::parse($ticket->created_at)->format('d M Y') }}</td>
                                                        <td>{{ $ticket->ticket_no}}</td>
                                                        <td>{!! $ticket->supportCategories->category_name !!}</td>
                                                        <td style ="{{ $ticket->priority === 'Medium' ? 'color: orange; font-weight: bold;' : ($ticket->priority === 'Low' ? 'color: #84f542; font-weight: bold;' : 'color: red; font-weight: bold;') }}">
                                                            {{ $ticket->priority }}
                                                        </td>
                                                        <td class="text-center" style="display: flex; justify-content: center; gap: 10px;">
                                                            <a href="{{ route('viewTicket', ['id' => $ticket->id]) }}" class="btn btn-sm btn-soft-purple btn-circle">
                                                                <i class="dripicons-preview"></i>
                                                            </a>

                                                            @if (Auth::user()->role_id == 1)
                                                                <a href="{{ route('editTicket', ['id' => $ticket->id]) }}" class="btn btn-sm btn-soft-success btn-circle">
                                                                    <i class="dripicons-pencil"></i>
                                                                </a>

                                                                <form action="{{ route('deleteTicket', ['id' => $ticket->id]) }}" method="POST" id="deleteForm{{ $ticket->id }}" data-ticket-id="{{ $ticket->id }}">
                                                                    @method('DELETE')
                                                                    @csrf
                                                                    <button type="button" class="btn btn-sm btn-soft-danger btn-circle" onclick="confirmDelete('deleteForm{{ $ticket->id }}')">
                                                                        <i class="dripicons-trash"></i>
                                                                    </button>
                                                                </form>
                                                            @elseif (Auth::user()->role_id !== 1 && Auth::user()->manage_ticket_in_category == 1 && Auth::user()->category_id == $ticket->category_id)
                                                                <a href="{{ route('editTicket', ['id' => $ticket->id]) }}" class="btn btn-sm btn-soft-success btn-circle">
                                                                    <i class="dripicons-pencil"></i>
                                                                </a>

                                                                <form action="{{ route('deleteTicket', ['id' => $ticket->id]) }}" method="POST" id="deleteForm{{ $ticket->id }}" data-ticket-id="{{ $ticket->id }}">
                                                                    @method('DELETE')
                                                                    @csrf
                                                                    <button type="button" class="btn btn-sm btn-soft-danger btn-circle" onclick="confirmDelete('deleteForm{{ $ticket->id }}')">
                                                                        <i class="dripicons-trash"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table> <!--end table-->
                                    </div><!--end /div-->
                                </div>
                            </div><!--end card-body-->
                        </div><!--end card-->
                    </div> <!--end col-->
                    <div class="col-md-6 col-lg-3">
                        <div class="card report-card">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center">
                                    <div class="col">
                                            <p class="text-dark mb-1 font-weight-semibold">Unassigned</p>
                                            <h3 class="my-0">{{ $totalUnassigned }}</h3>
                                    </div>
                                    <div class="col-auto align-self-center">
                                        <div class="report-main-icon bg-light-alt">
                                            <i data-feather="alert-triangle" class="align-self-center text-muted icon-md"></i>
                                        </div>
                                    </div>
                                </div>
                                <hr class="hr-dashed">
                                <div class="text-center">
                                    <h6 class="text-primary bg-soft-danger p-3 mb-0 font-11 rounded">
                                        High: {{ $unassignedHigh }}
                                    </h6>
                                    <h6 class="text-primary bg-soft-warning p-3 mb-0 font-11 rounded">
                                        Medium: {{ $unassignedMedium }}
                                    </h6>
                                    <h6 class="text-primary bg-soft-primary p-3 mb-0 font-11 rounded">
                                        Low: {{ $unassignedLow }}
                                    </h6>
                                </div>
                            </div><!--end card-body-->
                        </div><!--end card-->
                    </div> <!--end col-->
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">Tickets Group By Status</h4>
                            </div><!--end col-->
                            <div class="col-auto">
                                <div class="dropdown">
                                    <a href="#" class="btn btn-sm btn-outline-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Year<i class="las la-angle-down ml-1"></i>
                                    </a>
                                    <form id="yearFormStatus" method="GET" action="/dashboard">
                                        <div class="dropdown-menu dropdown-menu-right" id="yearDropdownStatus">
                                            @php
                                                $currentYear = now()->year;
                                                $startYear = max(2022, $currentYear - 2);
                                            @endphp
                                            @for ($year = $currentYear; $year >= $startYear; $year--)
                                                <a class="dropdown-item" href="#" data-year="{{ $year }}">{{ $year }}</a>
                                            @endfor
                                        </div>
                                        <input type="hidden" id="selectedYearStatus" name="year_status">
                                    </form>
                                </div>
                            </div><!--end col-->
                        </div>  <!--end row-->
                    </div><!--end card-header-->
                    <div class="card-body">
                        <div class="col-9">
                            <div id="Tickets_Status" class="apex-charts"></div>
                        </div>
                    </div><!--end card-body-->
                </div><!--end card-->
            </div><!--end col-->
        </div><!--end row-->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">Tickets Group by Category</h4>
                            </div><!--end col-->
                            <div class="col-auto">
                                <div class="dropdown">
                                    <a href="#" class="btn btn-sm btn-outline-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Year<i class="las la-angle-down ml-1"></i>
                                    </a>
                                    <form id="yearFormCategory" method="GET" action="/dashboard">
                                        <div class="dropdown-menu dropdown-menu-right" id="yearDropdownCategory">
                                            @php
                                                $currentYear = now()->year;
                                                $startYear = max(2022, $currentYear - 2);
                                            @endphp
                                            @for ($year = $currentYear; $year >= $startYear; $year--)
                                                <a class="dropdown-item" href="#" data-year="{{ $year }}">{{ $year }}</a>
                                            @endfor
                                        </div>
                                        <input type="hidden" id="selectedYearCategory" name="year_category">
                                    </form>
                                </div>
                            </div><!--end col-->
                        </div>  <!--end row-->
                    </div><!--end card-header-->
                    <div class="card-body">
                        <div class="">
                            <div id="ana_dash_1" class="apex-charts"></div>
                        </div>
                    </div><!--end card-body-->
                </div><!--end card-->
            </div> <!--end col-->
        </div><!--end row-->

    </div><!-- container -->
</div>
<!-- end page content -->
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
        @endif
    });
</script>

<script>
    var ticketStatusData = {!! json_encode($ticketsByStatus) !!};
    var ticketCategoryData = {!! json_encode($ticketsByCategory) !!};
</script>

<script>
    $(document).ready(function() {
        $('#yearDropdownStatus').on('click', '.dropdown-item', function(e) {
            e.preventDefault();
            var year = $(this).data('year');
            $('#selectedYearStatus').val(year);
            $('#yearFormStatus').submit();
        });

        $('#yearDropdownCategory').on('click', '.dropdown-item', function(e) {
            e.preventDefault();
            var year = $(this).data('year');
            $('#selectedYearCategory').val(year);
            $('#yearFormCategory').submit();
        });
    });
</script>

<script src="{{ asset('assets/plugins/apex-charts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/pages/jquery.helpdesk-dashboard.init.js') }}"></script>
<script src="{{ asset('assets/pages/jquery.analytics_dashboard.init.js') }}"></script>


@endsection


