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
                        <div class="col-md-6 col-lg-4">
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
                    <div class="col-md-6 col-lg-4">
                        <div class="card report-card">
                            <div class="card-body">
                                <a href="{{ route('unassignedTicket') }}">
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
                                </a>
                            </div><!--end card-body-->
                        </div><!--end card-->
                    </div> <!--end col-->

                </div><!--end row-->
            </div><!-- end col-->
        </div><!--end row-->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">Tickets Group By Project</h4>
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

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">Sales Group By Project</h4>
                            </div><!--end col-->
                            <div class="col-auto">
                                <div class="dropdown">
                                    <a href="#" class="btn btn-sm btn-outline-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Year<i class="las la-angle-down ml-1"></i>
                                    </a>
                                    <form id="yearFormProject" method="GET" action="/dashboard">
                                        <div class="dropdown-menu dropdown-menu-right" id="yearDropdownProject">
                                            @php
                                                $currentYear = now()->year;
                                                $startYear = max(2022, $currentYear - 2);
                                            @endphp
                                            @for ($year = $currentYear; $year >= $startYear; $year--)
                                                <a class="dropdown-item" href="#" data-year="{{ $year }}">{{ $year }}</a>
                                            @endfor
                                        </div>
                                        <input type="hidden" id="selectedYearProject" name="year_project">
                                    </form>
                                </div>
                            </div><!--end col-->
                        </div>  <!--end row-->
                    </div><!--end card-header-->
                    <div class="card-body">
                        <div class="">
                            <div id="ana_dash_2" class="apex-charts"></div>
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
        @elseif(session('error'))
            Swal.fire({
                title: 'Error',
                text: '{{ session('error') }}',
                icon: 'error',
                timer: 1000,
                showConfirmButton: false,
            });
        @endif
    });
</script>

<script>
    var ticketStatusData = {!! json_encode($ticketsByStatus) !!};
    var ticketCategoryData = {!! json_encode($ticketsByCategory) !!};
    var salesProjectData = {!! json_encode($salesByProject) !!};
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

        $('#yearDropdownProject').on('click', '.dropdown-item', function(e) {
            e.preventDefault();
            var year = $(this).data('year');
            $('#selectedYearProject').val(year);
            $('#yearFormProject').submit();
        });
    });
</script>

<script src="{{ asset('assets/plugins/apex-charts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/pages/jquery.helpdesk-dashboard.init.js') }}"></script>
<script src="{{ asset('assets/pages/jquery.analytics_dashboard.init.js') }}"></script>


@endsection


