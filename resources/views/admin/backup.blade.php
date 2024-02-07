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
                        <div class="col-10">
                            <h4 class="page-title mt-2">Helpdesk</h4>
                        </div><!--end col-->

                        <div class="col-2" style="display: flex; justify-content: flex-end; align-items: flex-end;">
                            <button type="button" class="btn" id="filterButton">
                                <i data-feather="filter"></i>
                            </button>
                        </div>



                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <!-- end page title end breadcrumb -->

        {{-- <div class="filter-options" style="display: none;">
            <div class="row" style="flex-direction: row; align-items: center;">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="username">Category</label>
                        <select class="form-control" name="category_id" id="category">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{!! $category->category_name !!}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="useremail">Operator</label>
                        <select class="form-control" name="operator" id="operator">
                            <option value="">Select Operator</option>
                            <option value="AND" {{ old('operator') == 'AND' ? 'selected' : '' }}>AND</option>
                            <option value="OR" {{ old('operator') == 'OR' ? 'selected' : '' }}>OR</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="useremail">Priority</label>
                        <select class="form-control" name="priority" id="priority">
                            <option value="">Select Priority</option>
                            <option value="Low" {{ old('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                            <option value="Medium" {{ old('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="High" {{ old('priority') == 'High' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-info waves-effect waves-light" id="reset">Reset</button>
                </div><!--end col-->
            </div>

            <div class="row">
               <div class="col-md-3">
                <p>Return: <span id="matchedCount">0</span></p>
               </div>
            </div>
        </div><!--end row--> --}}

        {{-- <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable2" class="table table-bordered" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Ticket No.</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Category</th>
                                        <th>Priority</th>
                                        <th>Assignee</th>
                                        <th>Remarks</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="ticketTableBody">
                                    @foreach($tickets as $ticket)
                                    <tr id="{{ $ticket->id }}">
                                        <td>{{ Carbon\Carbon::parse($ticket->created_at)->format('d M Y') }}</td>
                                        <td>{{ $ticket->ticket_no }}</td>
                                        <td>{{ $ticket->sender_name }}</td>
                                        <td>{{ $ticket->sender_email }}</td>
                                        <td>{!! $ticket->supportCategories->category_name !!}</td>
                                        <td style ="{{ $ticket->priority === 'Medium' ? 'color: orange; font-weight: bold;' : ($ticket->priority === 'Low' ? 'color: #84f542; font-weight: bold;' : 'color: red; font-weight: bold;') }}">
                                            {{ $ticket->priority }}
                                        </td>
                                        <td>{{ $ticket->pic_id }}</td>
                                        <td>{{ $ticket->remarks }}</td>
                                        <td class="text-center" style="display: flex; justify-content: center; gap: 10px;">
                                            <a href="{{ route('viewTicket', ['id' => $ticket->id]) }}" class="btn btn-sm btn-soft-purple btn-circle">
                                                <i class="dripicons-preview"></i>
                                            </a>

                                            <a href="{{ route('editTicket', ['id' => $ticket->id]) }}" class="btn btn-sm btn-soft-success btn-circle">
                                                <i class="dripicons-pencil"></i>
                                            </a>

                                            <form action="{{ route('deleteTicket', ['id' => $ticket->id]) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-soft-danger btn-circle">
                                                    <i class="dripicons-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table><!--end /table-->
                            <livewire:tickets/>
                        </div><!--end /tableresponsive-->
                    </div><!--end card-body-->
                </div><!--end card-->
            </div> <!-- end col -->
        </div> <!-- end row --> --}}


        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row" style="display: flex; flex-direction: row; flex-wrap: wrap; align-items: center; align-content: flex-end;">
                            <div class="col-sm-12 col-md-6">
                                {{-- <div class="btn-group mb-2 mb-md-0">
                                    <button type="button" class="btn btn-soft-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Show Entries<i class="mdi mdi-chevron-down"></i></button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" data-value="10">10</a>
                                        <a class="dropdown-item" href="#" data-value="20">20</a>
                                        <a class="dropdown-item" href="#" data-value="50">50</a>
                                        <a class="dropdown-item" href="#" data-value="-1">All</a>
                                    </div>
                                </div><!-- /btn-group --> --}}
                                <div style="display: flex; align-items: flex-end; flex-wrap: nowrap; gap:8px; margin-bottom: 8px;">
                                    <label>Show</label>
                                        <div style="width:60px;">
                                            <select id="perPageSelect" name="datatable2_length" aria-controls="datatable2" class="custom-select custom-select-sm form-control form-control-sm">
                                                <option value="10" data-value="10">10</option>
                                                <option value="25" data-value="20">25</option>
                                                <option value="50" data-value="50">50</option>
                                                <option value="100" data-value="-1">All</option>
                                            </select>
                                        </div>
                                    <label>Entries</label>
                                </div>
                            </div>
                            {{-- <div class="col-auto align-self-center">
                                <a class="nav-link dropdown-toggle arrow-none waves-light waves-effect" data-toggle="dropdown" href="#" role="button"
                                    aria-haspopup="false" aria-expanded="false">
                                    <i data-feather="search" class="topbar-icon"></i>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right dropdown-lg p-0">
                                    <!-- Top Search Bar -->
                                    <div class="app-search-topbar">
                                            <div>
                                                <input type="search" name="search" id="searchInput" class="from-control top-search mb-0" autocomplete="off" placeholder="Type text...">
                                                <button id="search-button" type="button"><i class="ti-search"></i></button>
                                            </div>
                                    </div>
                                </div>
                            </div><!--end col--> --}}
                            <div class="col-sm-12 col-md-6">
                                <div style="display: flex; align-items: center; justify-content: flex-end; margin-bottom: 5px;">
                                    <label>Search: </label>
                                    <div class="col-sm-3">
                                        <input class="form-control form-control-sm" type="search" id="searchInput" name="search" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Ticket No.</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Category</th>
                                        <th>Priority</th>
                                        <th>Assignee</th>
                                        <th>Remarks</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="ticketTableBody">
                                    @foreach($tickets as $ticket)
                                    <tr id="{{ $ticket->id }}">
                                        <td>{{ Carbon\Carbon::parse($ticket->created_at)->format('d M Y') }}</td>
                                        <td>{{ $ticket->ticket_no }}</td>
                                        <td>{{ $ticket->sender_name }}</td>
                                        <td>{{ $ticket->sender_email }}</td>
                                        <td>{!! $ticket->supportCategories->category_name !!}</td>
                                        <td style ="{{ $ticket->priority === 'Medium' ? 'color: orange; font-weight: bold;' : ($ticket->priority === 'Low' ? 'color: #84f542; font-weight: bold;' : 'color: red; font-weight: bold;') }}">
                                            {{ $ticket->priority }}
                                        </td>
                                        <td>{{ $ticket->pic_id }}</td>
                                        <td>{{ $ticket->remarks }}</td>
                                        <td class="text-center" style="display: flex; justify-content: center; gap: 10px;">
                                            <a href="{{ route('viewTicket', ['id' => $ticket->id]) }}" class="btn btn-sm btn-soft-purple btn-circle">
                                                <i class="dripicons-preview"></i>
                                            </a>

                                            <a href="{{ route('editTicket', ['id' => $ticket->id]) }}" class="btn btn-sm btn-soft-success btn-circle">
                                                <i class="dripicons-pencil"></i>
                                            </a>

                                            <form action="{{ route('deleteTicket', ['id' => $ticket->id]) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-soft-danger btn-circle">
                                                    <i class="dripicons-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table><!--end /table-->
                        </div><!--end /tableresponsive-->

                        @if ($perPage != -1)
                            <div style="display: flex; justify-content: flex-end;">
                                <nav aria-label="...">
                                    <ul id="paginationLinks" class="pagination" data-page="{{ $totalPages }}">
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $tickets->previousPageUrl() }}" tabindex="-1">Previous</a>
                                        </li>
                                        @for ($i = 1; $i <= $totalPages; $i++)
                                        <li class="page-item {{ $i == $tickets->currentPage() ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $tickets->url($i) }}">{{ $i }}</a>
                                        </li>
                                        @endfor
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $tickets->nextPageUrl() }}">Next</a>
                                        </li>
                                    </ul><!--end pagination-->
                                </nav><!--end nav-->
                            </div>
                        @endif
                    </div><!--end card-body-->
                </div><!--end card-->
            </div> <!-- end col -->
        </div> <!-- end row -->


    </div><!-- container -->


</div>
<!-- end page content -->

{{-- Pagination --}}
<script>
    $(document).ready(function() {
        // Add onchange event listener to select element
        $('#perPageSelect').on('change', function() {
            var perPage = $(this).val(); // Get the selected value
            var url = "{{ route('helpdesk') }}?perPage=" + perPage;
            window.location.href = url; // Redirect to the constructed URL
        });
    });
</script>



{{-- Filter --}}
<script>
    $(document).ready(function() {

        console.log('Jquery is working');

        $('#filterButton').click(function(event) {
            $('.filter-options').toggle();

            updateURL('-1');
        });

        function updateURL() {
            var perPage = $('.filter-options').is(':visible') ? -1 : 10;
            var url = "{{ route('helpdesk') }}?perPage=" + perPage;

            window.location.href = url;
        }

        var params = new URLSearchParams(window.location.search);
        var perPage = params.get('perPage');

        if (perPage == -1) {
            // Show filter options if perPage is -1
            $('.filter-options').show();
        }

        $('#reset').click(function(e) {
            e.preventDefault();

            $('tbody tr').css('background-color', 'white');

            // Reset the input values
            $('#category').val('');
            $('#priority').val('');
            $('#operator').val('');
        });

        $('#category, #priority, #operator').change(function(e) {
            e.preventDefault();

            var category_id = $('#category').val();
            var priority = $('#priority').val();
            var operator = $('#operator').val();

            // console.log(category_id, priority, operator);

            // AJAX request
            $.ajax({
                url: '/filter-ticket',
                method: 'GET',
                data: {
                    category_id: category_id,
                    priority: priority,
                    operator: operator,
                },
                success: function(response) {
                    var allTicketIds = response.allTicketIds;
                    var matchedTicketIds = response.matchedTicketIds;
                    var unmatchedTicketIds = response.unmatchedTicketIds;
                    var currentPage = parseInt($('#paginationLinks').find('.active').text());
                    var totalPages = parseInt($('#paginationLinks').data('page'));

                    console.log(currentPage);
                    console.log(totalPages);
                    console.log('Matched IDs:', matchedTicketIds);
                    console.log('Unmatched IDs:', unmatchedTicketIds);

                    // Display the count of matched tickets
                    $('#matchedCount').text(matchedTicketIds.length);

                    if (matchedTicketIds.length > 0) {
                        matchedTicketIds.forEach(function (ticketId) {
                            var tr = $('#' + ticketId);

                            if (tr.length > 0) {
                                // if (cardHeader.css('background-color') !== 'rgba(0, 0, 0, 0)') {
                                // }

                                tr.css('background-color', '#ffebc1');

                            }
                        });
                    }

                    if (unmatchedTicketIds.length > 0) {
                        unmatchedTicketIds.forEach(function (ticketId) {
                            var tr = $('#' + ticketId);

                            if (tr.length > 0) {
                                // if (cardHeader.css('background-color') !== 'rgba(0, 0, 0, 0)') {
                                // }

                                tr.css('background-color', 'white');

                            }
                        });
                    }

                    // Append rows for white tickets first
                    matchedTicketIds.forEach(function(ticketId) {
                        var row = $('#' + ticketId);
                        if (row.css('background-color') === 'rgb(255, 255, 255)') {
                            $('#ticketTableBody').append(row);
                        }
                    });

                    // Append rows for non-white tickets next
                    matchedTicketIds.forEach(function(ticketId) {
                        var row = $('#' + ticketId);
                        if (row.css('background-color') !== 'rgb(255, 255, 255)') {
                            $('#ticketTableBody').append(row);
                        }
                    });

                    // Append unmatched tickets at the end
                    unmatchedTicketIds.forEach(function(ticketId) {
                        var row = $('#' + ticketId);
                        $('#ticketTableBody').append(row);
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });
    });
</script>

{{-- Search --}}
<script>
    $(document).ready(function () {

        $('#searchInput').on('input', function (e) {
            e.preventDefault();
            var searchTerm = $(this).val().trim();

            $.ajax({
                url: '/search-documentation',
                type: 'GET',
                data: {
                    searchTerm: searchTerm,
                },
                success: function (response) {
                    console.log('Search Term:', searchTerm);

                    var matchedContentIds = response.matchedContentIds;
                    var unmatchedContentIds = response.unmatchedContentIds;
                    var allContentIds = response.allContentIds;

                    console.log('Matched IDs:', matchedContentIds);
                    console.log('Unmatched IDs:', unmatchedContentIds);

                    if (matchedContentIds.length > 0) {
                        matchedContentIds.forEach(function (contentId) {
                            var contentName = $('#' + contentId);

                            if (contentName.length > 0) {
                                contentName.css('background-color', '#cFFCAB1');
                            }
                        });
                    }

                    if (unmatchedContentIds.length > 0) {
                        unmatchedContentIds.forEach(function (contentId) {
                            var contentName = $('#' + contentId);

                            if (contentName.length > 0) {
                                contentName.css('background-color', 'white');
                            }
                        });
                    }

                    if (searchTerm.trim() === '') {
                        allContentIds.forEach(function (contentId) {
                            var contentName = $('#' + contentId);

                            if (contentName.length > 0) {
                                contentName.css('background-color', 'white');
                            }
                        });
                    }
                },
                error: function (error) {
                    console.error('Error:', error);
                }
            });


        });
    });
</script>

@endsection

