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

        <div class="filter-options" style="display: none;">
            <div class="row" style="flex-direction: row; align-items: center;">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="username">Date</label>
                        <input type="date" class="form-control" id="datepick" name="date">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="username">Category</label>
                        <select class="form-control" name="category_id" id="category">
                            <option value="">Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{!! $category->category_name !!}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- <div class="col-sm-2">
                    <div class="form-group">
                        <label for="useremail">Operator</label>
                        <select class="form-control" name="operator" id="operator">
                            <option value="">Operator</option>
                            <option value="AND" {{ old('operator') == 'AND' ? 'selected' : '' }}>AND</option>
                            <option value="OR" {{ old('operator') == 'OR' ? 'selected' : '' }}>OR</option>
                        </select>
                    </div>
                </div> --}}

                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="useremail">Priority</label>
                        <select class="form-control" name="priority" id="priority">
                            <option value="">Priority</option>
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
        </div><!--end row-->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row" style="display: flex; flex-direction: row; flex-wrap: wrap; align-items: center; align-content: flex-end;">
                            <div class="col-sm-12 col-md-6">
                                <div style="display: flex; align-items: flex-end; flex-wrap: nowrap; gap:8px; margin-bottom: 8px;">
                                    <label>Show</label>
                                        <div style="width:60px;">
                                            <select id="perPageSelect" name="datatable2_length" aria-controls="datatable2" class="custom-select custom-select-sm form-control form-control-sm">
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                                <option value="-1">All</option>
                                            </select>
                                        </div>
                                    <label>Entries</label>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <div style="display: flex; align-items: center; justify-content: flex-end; margin-bottom: 5px;">
                                    <label>Search: </label>
                                    <div class="col-sm-5">
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
                                        {{-- <th>Status</th> --}}
                                        <th>PIC</th>
                                        <th>Remarks</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="ticketTableBody">

                                </tbody>
                            </table><!--end /table-->
                        </div><!--end /tableresponsive-->


                        <div style="display:flex; flex-direction: row; flex-wrap: wrap; align-items: center;">
                            <div id="entriesDisplay" class="col-md-6">

                            </div>
                            <div id="pagination" class="col-md-6" style="display: flex; justify-content: flex-end;">
                                <nav aria-label="...">
                                    <ul id="paginationLinks" class="pagination">
                                        <li class="page-item">
                                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                                        </li>

                                        <li class="page-item active">
                                            <a class="page-link" href="#">1</a>
                                        </li>

                                        <li class="page-item">
                                            <a class="page-link" href="#">Next</a>
                                        </li>
                                    </ul><!--end pagination-->
                                </nav><!--end nav-->
                            </div>
                        </div>
                    </div><!--end card-body-->
                </div><!--end card-->
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div><!-- container -->

</div>
<!-- end page content -->



{{-- Load ticket with pagination and filter, search function --}}
<script>
    $(document).ready(function() {

        $('#filterButton').click(function(event) {
            $('.filter-options').toggle();

            if ($('.filter-options').is(':hidden')) {
                // If filter options are hidden, clear filter values
                $('#category').val('');
                $('#priority').val('');
                $('#operator').val('');
                $('#datepick').val('');
                // Trigger change event to ensure the loadTickets function is called with the updated filter values
                $('#category, #priority, #operator, #datepick').trigger('change');
            }
        });

        $('#reset').click(function(e) {
            e.preventDefault();
            // Reset the input values
            $('#category').val('');
            $('#priority').val('');
            $('#operator').val('');
            $('#datepick').val('');

             // Reload tickets with default values
            currentPage = 1;
            loadTickets(currentPage, perPage);
        });

        // Event listener for changes in select options
        $('#category, #priority, #operator, #datepick').on('change input', function(e) {
            e.preventDefault();
            currentPage = 1; // Reset to first page when changing select options
            clearInputValue(); // Clear input value when select options are used
            var categoryId = $('#category').val();
            var operator = $('#operator').val();
            var priority = $('#priority').val();
            var datepick = $('#datepick').val();

            console.log(datepick);
            loadTickets(currentPage, perPage, categoryId, operator, priority, datepick);
        });

        // Event listener for input field
        $('#searchInput').on('input', function(e) {
            e.preventDefault();
            currentPage = 1;
            clearSelectOptions(); // Clear select options when input field is used
            var searchTerm = $(this).val().trim();
            var categoryId = $('#category').val();
            var operator = $('#operator').val();
            var priority = $('#priority').val();
            var datepick = $('#datepick').val();
            loadTickets(currentPage, perPage, categoryId, operator, priority, datepick, searchTerm,);
        });

        // Function to clear select options
        function clearSelectOptions() {
            console.log('Clearing select options');
            $('#category').val('');
            $('#priority').val('');
            $('#operator').val('');
            $('#datepick').val('');
        }

        // Function to clear input value
        function clearInputValue() {
            $('#searchInput').val('');
        }

        var perPage = 10; // Default items per page
        var currentPage = 1; // Default current page
        var currentEntries;
        var totalEntries;

        function loadTickets(page, perPage, categoryId, operator, priority, datepick, searchTerm) {

            $.ajax({
                url: '/get-ticket',
                method: 'GET',
                data: {
                    page: page,
                    per_page: perPage,
                    category_id: categoryId,
                    operator: operator,
                    priority: priority,
                    filter_date: datepick,
                    searchTerm: searchTerm
                },
                success: function(response) {

                    // console.log('Search Term:', searchTerm);
                    // console.log('Category ID:', categoryId);
                    // console.log('Operator:', operator);
                    // console.log('Priority:', priority);
                    // console.log('Date:', datepick);

                    var tickets = response.tickets;

                    // Total entries before filtering or searching
                    totalEntries = response.total_entries;

                    // Total entries after filtering or searching
                    currentEntries = response.current_entries;

                    // console.log('Current entries: ', currentEntries);
                    // console.log('Total entries: ', totalEntries);

                    // Clear existing table rows
                    $('#ticketTableBody').empty();

                    tickets.forEach(function(ticket) {

                        var createdAt = new Date(ticket.t_created_at);
                        var formattedDate = createdAt.toLocaleDateString('en-GB', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric'
                        });

                        var categoryName = (ticket.category_name) ? ticket.category_name : '';
                        var picId = (ticket.pic_id) ? ticket.pic_id : '';
                        var remarks = (ticket.remarks) ? ticket.remarks : '';
                        var ticketId = (ticket.ticket_id) ? ticket.ticket_id : '';

                        var viewRoute = "/view-ticket/" + ticketId; // Construct the view route
                        var editRoute = "/edit-ticket/" + ticketId; // Construct the edit route
                        var deleteRoute = "/delete-ticket/" + ticketId;

                        var actions = `<a href="${viewRoute}" class="btn btn-sm btn-soft-purple btn-circle"><i class="dripicons-preview"></i></a>
                                    <a href="${editRoute}" class="btn btn-sm btn-soft-success btn-circle"><i class="dripicons-pencil"></i></a>
                                    <form action="${deleteRoute}" method="POST">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-sm btn-soft-danger btn-circle"><i class="dripicons-trash"></i></button>
                                    </form>`;

                        var priorityStyle = '';
                        if (ticket.priority === 'Medium') {
                            priorityStyle = 'color: orange; font-weight: bold;';
                        } else if (ticket.priority === 'Low') {
                            priorityStyle = 'color: #84f542; font-weight: bold;';
                        } else {
                            priorityStyle = 'color: red; font-weight: bold;';
                        }

                        var statusStyle = '';
                        if (ticket.status === 'Pending') {
                            statusStyle = 'color: orange; font-weight: bold;';
                        } else if (ticket.status === 'Solved') {
                            statusStyle = 'color: #84f542; font-weight: bold;';
                        } else if (ticket.status === 'New Ticket') {
                            statusStyle = 'color: #4684fc; font-weight: bold;';
                        } else {
                            statusStyle = 'color: red; font-weight: bold;';
                        }

                        var row = '<tr id="' + ticketId + '">' +
                                    '<td>' + formattedDate + '</td>' +
                                    '<td>' + ticket.ticket_no + '</td>' +
                                    '<td>' + ticket.sender_name + '</td>' +
                                    '<td>' + ticket.sender_email + '</td>' +
                                    '<td>' + categoryName + '</td>' +
                                    '<td style="' + priorityStyle + '">' + ticket.priority + '</td>' +
                                    // '<td style="' + statusStyle + '">' + ticket.status + '</td>' +
                                    '<td>' + picId + '</td>' +
                                    '<td>' + remarks + '</td>' +
                                    '<td class="text-center" style="display: flex; justify-content: center; gap: 10px;">' + actions + '</td>' +
                                '</tr>';

                        // Append the row to the table body
                        $('#ticketTableBody').append(row);
                    });

                    // Update the display of number of entries
                    updateEntriesDisplay(currentEntries, totalEntries, currentPage, perPage);

                     // Update pagination links
                    if (perPage === -1) {
                        // If "All" is selected, hide pagination links
                        $('#paginationLinks').empty();
                    } else {
                        // Update pagination links
                        updatePagination(response.total_pages, currentPage);
                    }

                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    console.error('Error fetching tickets:', error);
                }
            });
        }

        // Function to update pagination links
        function updatePagination(totalPages, currentPage) {
            var paginationLinks = $('#paginationLinks');
            paginationLinks.empty();
            // Add Previous button
            paginationLinks.append(`<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                                        <a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>
                                    </li>`);
            // Add page numbers
            for (var i = 1; i <= totalPages; i++) {
                paginationLinks.append(`<li class="page-item ${currentPage === i ? 'active' : ''}">
                                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                                        </li>`);
            }
            // Add Next button
            paginationLinks.append(`<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                                        <a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>
                                    </li>`);
        }

        // Event listener for pagination links
        $('#paginationLinks').on('click', 'a.page-link', function(e) {
            e.preventDefault();
            var page = parseInt($(this).data('page'));
            if (!isNaN(page)) {
                currentPage = page;
                loadTickets(currentPage, perPage);
            }
        });

        // Event listener for per page select
        $('#perPageSelect').on('change', function() {
            perPage = parseInt($(this).val());
            currentPage = 1; // Reset to first page when changing per page
            if (perPage === -1) {
                // If "All" is selected, load all tickets
                loadTickets(currentPage, perPage); // Pass 0 as perPage to indicate loading all tickets
            } else {
                loadTickets(currentPage, perPage);
                // Update the display of number of entries
                updateEntriesDisplay(currentEntries ,totalEntries, currentPage, perPage);
            }
        });

        // Function to update the display of number of entries
        function updateEntriesDisplay(currentEntries, totalEntries, currentPage, perPage) {

            var startIndex = 0;
            var endIndex = 0;
            var displayMessage;

            // Construct the display message
            if (totalEntries === currentEntries && perPage !== -1) {
                startIndex = (currentPage - 1) * perPage + 1;
                endIndex = Math.min(startIndex + perPage - 1, totalEntries);

                displayMessage = `Showing ${startIndex} to ${endIndex} of ${currentEntries} entries`;
            } else if (currentEntries == 0 ){
                startIndex = 0;
                endIndex = 0;

                displayMessage = `Showing ${startIndex} to ${endIndex} of ${currentEntries} entries (filtered by ${totalEntries} entries)`;
            } else if (currentEntries < totalEntries && currentEntries > 1){
                startIndex = (currentPage - 1) * perPage + 1;
                endIndex = Math.min(startIndex + currentEntries - 1, totalEntries);

                displayMessage = `Showing ${startIndex} to ${endIndex} of ${currentEntries} entries (filtered by ${totalEntries} entries)`;
            } else if (totalEntries === currentEntries && perPage === -1) {
                startIndex = 1;
                endIndex = currentEntries;

                displayMessage = `Showing ${startIndex} to ${endIndex} of ${totalEntries} entries`;
            } else if (totalEntries === currentEntries) {
                startIndex = (currentPage - 1) * perPage + 1;
                endIndex = Math.min(startIndex + perPage - 1, totalEntries);

                displayMessage = `Showing ${startIndex} to ${endIndex} of ${currentEntries} entries`;
            } else {
                startIndex = 0;
                endIndex = 0;

                displayMessage = `Showing ${startIndex} to ${endIndex} of ${currentEntries} entries`;
            }

            $('#entriesDisplay').text(displayMessage);
        }

        // function updateEntriesDisplay(currentEntries, totalEntries, currentPage, perPage) {

        //     // var startIndex = (currentPage - 1) * perPage + 1;
        //     // var endIndex = Math.min(startIndex + perPage - 1, totalEntries);
        //     var startIndex = 0;
        //     var endIndex = 0;
        //     var displayMessage;

        //     // Check if startIndex or endIndex is NaN, then fallback to 1
        //     // if (isNaN(startIndex) || isNaN(endIndex)) {
        //     //     startIndex = 0;
        //     //     endIndex = 0;
        //     // }

        //     // Construct the display message
        //     if (totalEntries === currentEntries) {
        //         startIndex = (currentPage - 1) * perPage + 1;
        //         endIndex = Math.min(startIndex + perPage - 1, totalEntries);
        //         displayMessage = `Showing ${startIndex} to ${endIndex} of ${currentEntries} entries`;
        //     } else {
        //         startIndex = (currentPage - 1) * perPage + 1;
        //         endIndex = Math.min(startIndex + currentEntries - 1, totalEntries);
        //         displayMessage = `Showing ${startIndex} to ${endIndex} of ${currentEntries} entries (filtered by ${totalEntries} entries)`;
        //     }

        //     $('#entriesDisplay').text(displayMessage);
        // }

        // Initial load
        loadTickets(currentPage, perPage);
        updateEntriesDisplay(currentPage, perPage);
    });
</script>

@endsection

