@extends('layouts.masterAdmin')
@section('content')

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12" style="padding: 15px;">
                {{-- <div class="page-title-box"> --}}
                    <div class="row">
                        <div class="col-8">
                            <h4 class="page-title mt-2">Performance</h4>
                        </div><!--end col-->
                        <div class="col-4"  style="display: flex; ">
                            <div class="col-sm-5">
                                <div class="form-group">
                                    @php
                                        $months = [
                                            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                                            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                                        ];
                                    @endphp
                                    <select class="form-control" name="filter_month" id="filtermonth">
                                        <option value="">Month</option>
                                        @foreach ($months as $month)
                                            <option value="{{ $month }}">{{ $month }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-group">
                                    @php
                                        $currentYear = now()->year;
                                        $startYear = max(2022, $currentYear - 2);
                                    @endphp
                                    <select class="form-control" name="filter_year" id="filteryear">
                                        <option value="">Year</option>
                                        @for ($year = $currentYear; $year >= $startYear; $year--)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2" >
                                <button type="button" class="btn btn-info waves-effect waves-light" id="reset">Reset</button>
                            </div><!--end col-->
                        </div>
                    </div><!--end row-->
                {{-- </div><!--end page-title-box--> --}}
            </div><!--end col-->
        </div><!--end row-->
        {{-- @php
            $colors = ['#bed3fe', '#e3e6f0', '#b8f4db', '#bde6fa', '#ffebc1', '#99a1b7', '#b2bfc2'];
            $colorIndex = 0;
        @endphp

        <div>
            @foreach ($supportCategories as $supportCategory)
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card" >
                            <div class="card-header" style="background-color: {{ $colors[$colorIndex] }}">
                                <h4 class="card-title">{!! $supportCategory->category_name !!}</h4>
                            </div><!--end card-header-->
                        </div><!--end card-->
                    </div><!--end col-->
                </div>

                <div class="row">

                    @foreach ($supportCategory->users as $user)
                        <div class="col-sm-3">
                            <div class="card">
                                <a href="{{ route('viewPerformance', ['id' => $user->id]) }}">
                                    <div class="card-header">
                                        <h4 class="card-title">{{ $user->name }}</h4>
                                        <div style="display: flex; align-items: center;">
                                            <h4 class="card-title" style="margin-right: 5px;">Total Tickets</h4>
                                            <span>:</span>
                                            <h4 class="card-title" style="margin-left: 5px;">{{ $user->tickets_count }}</h4>
                                        </div>
                                    </div><!--end card-header-->
                                    <div class="card-body">
                                        @foreach ($ticketStatuses as $status)
                                            <div style="display: flex; align-items: center;">
                                                <h6 style="margin-right: 5px;">{{ $status->status }}</h6>
                                                <span>:</span>
                                                <h6 style="margin-left: 5px;">{{ $user->tickets()->where('status_id', $status->id)->count() }}</h6>
                                            </div>
                                        @endforeach
                                    </div><!--end card-body-->
                                </a>
                            </div><!--end card-->
                        </div><!--end col-->
                    @endforeach
                </div><!--end row-->

                @php
                    $colorIndex = ($colorIndex + 1) % count($colors); // Cycle through colors
                @endphp
            @endforeach
        </div> --}}
        <div id="performanceContainer"></div>
    </div>
</div>
<!-- end page content -->

<script>
    $(document).ready(function() {

        // Event listener for changes in select options
        $('#filtermonth, #filteryear').on('change', function(e) {
            e.preventDefault();
            var month = $('#filtermonth').val();
            var year = $('#filteryear').val();

            // Check if the month is empty
            if (month.trim() === '') {
                // Set the month to the current month
                var currentDate = new Date();
                var monthNumber = currentDate.getMonth() + 1; // Months are zero-based, so we add 1
                month = monthNumber.toString();
            }

            // Check if the year is empty
            if (year.trim() === '') {
                // Set the year to the current year
                var currentDate = new Date();
                year = currentDate.getFullYear().toString();
            }

            console.log('Month',month);
            console.log('Year',year);
            loadPerformance(month, year);
        });

        $('#reset').click(function(e) {
            e.preventDefault();
            // Reset the input values
            $('#filtermonth').val('');
            $('#filteryear').val('');

            loadPerformance();
        });

        function loadPerformance(month, year) {

            $.ajax({
                url: '/get-performance',
                method: 'GET',
                data: {
                    filter_month: month,
                    filter_year: year
                },
                success: function(response) {
                    // Clear previous data
                    $('#performanceContainer').empty();

                    // Iterate through support categories
                    response.supportCategories.forEach(function(supportCategory) {

                        var categoryHtml = '';
                        // Create a row for the support category
                        categoryHtml += '<div class="row m-0">';
                        categoryHtml += '<div class="col-sm-12">';
                        categoryHtml += '<div class="card">';
                        categoryHtml += '<div class="card-header" style="background-color: ' + response.colors[supportCategory.id % response.colors.length] + '">';
                        categoryHtml += '<h4 class="card-title">' + supportCategory.category_name + '</h4>';
                        categoryHtml += '</div>'; // end card-header
                        categoryHtml += '</div>'; // end card
                        categoryHtml += '</div>'; // end col
                        categoryHtml += '</div>'; // end row

                        categoryHtml += '<div class="row">';
                            // Iterate through users in the support category
                            supportCategory.users.forEach(function(user) {
                                // Create a column for each user
                                categoryHtml += '<div class="col-sm-3">';
                                categoryHtml += '<div class="card" style="margin-left: 11px;">';
                                categoryHtml += '<a href="/view-performance/' + user.id + '">';
                                categoryHtml += '<div class="card-header">';
                                categoryHtml += '<h4 class="card-title">' + user.name + '</h4>';
                                categoryHtml += '<div style="display: flex; align-items: center;">';
                                categoryHtml += '<h4 class="card-title" style="margin-right: 5px;">Total Tickets</h4>';
                                categoryHtml += '<span>:</span>';
                                categoryHtml += '<h4 class="card-title" style="margin-left: 5px;">' + user.totalTickets + '</h4>';
                                categoryHtml += '</div>'; // end flex container
                                categoryHtml += '</div>'; // end card-header
                                categoryHtml += '<div class="card-body">';
                                // Iterate through ticket counts for each status
                                response.ticketStatuses.forEach(function(status) {
                                    categoryHtml += '<div style="display: flex; align-items: center;">';
                                    categoryHtml += '<h6 style="margin-right: 5px;">' + status.status + '</h6>';
                                    categoryHtml += '<span>:</span>';
                                    categoryHtml += '<h6 style="margin-left: 5px;">' + user.ticketCounts[status.status] + '</h6>';
                                    categoryHtml += '</div>'; // end flex container
                                });
                                categoryHtml += '</div>'; // end card-body
                                categoryHtml += '</a>';
                                categoryHtml += '</div>'; // end card
                                categoryHtml += '</div>'; // end col
                            });
                        categoryHtml += '</div>'; // end row

                        // Append the category HTML to the container
                        $('#performanceContainer').append(categoryHtml);
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }

        loadPerformance();
    });
</script>


@endsection
