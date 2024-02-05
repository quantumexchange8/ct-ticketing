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

                        {{-- <div class="btn-group mb-md-0 mr-4">
                            <button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Export<i class="mdi mdi-chevron-down"></i></button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">Excel</a>
                                <a class="dropdown-item" href="#">PDF</a>
                            </div>
                        </div><!-- /btn-group --> --}}

                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <!-- end page title end breadcrumb -->

        <div class="filter-options" style="display: none;">
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
        </div><!--end row-->



        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable2" class="table table-bordered" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        {{-- <th>Ticket ID</th> --}}
                                        <th>Date</th>
                                        <th>Ticket No.</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        {{-- <th>Subject</th>
                                        <th>Message</th> --}}
                                        <th>Category</th>
                                        <th>Priority</th>
                                        <th>Assignee</th>
                                        <th>Remarks</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tickets as $ticket)
                                    <tr id="{{ $ticket->id }}">
                                        {{-- <td>{{ $ticket->id }}</td> --}}
                                        <td>{{ Carbon\Carbon::parse($ticket->created_at)->format('d M Y') }}</td>
                                        <td>{{ $ticket->ticket_no }}</td>
                                        <td>{{ $ticket->sender_name }}</td>
                                        <td>{{ $ticket->sender_email }}</td>
                                        {{-- <td>{{ $ticket->subject }}</td>
                                        <td>{{ $ticket->message }}</td> --}}
                                        <td>{!! $ticket->supportCategories->category_name !!}</td>
                                        <td style ="{{ $ticket->priority === 'Medium' ? 'color: orange; font-weight: bold;' : ($ticket->priority === 'Low' ? 'color: #84f542; font-weight: bold;' : 'color: red; font-weight: bold;') }}">
                                            {{ $ticket->priority }}
                                        </td>
                                        <td>{{ $ticket->pic_id }}</td>
                                        <td>{{ $ticket->remarks }}</td>
                                        <td class="text-center" style="display: flex; justify-content: center; gap: 10px;">
                                            {{-- @if ($ticket->ticketImages->isNotEmpty())
                                                <a href="{{ route('viewTicketImage', ['id' => $ticket->id]) }}" class="btn btn-sm btn-soft-purple btn-circle">
                                                    <i class="dripicons-preview"></i>
                                                </a>
                                            @endif --}}
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
                            {{-- <livewire:tickets/> --}}
                        </div><!--end /tableresponsive-->
                    </div><!--end card-body-->
                </div><!--end card-->
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div><!-- container -->


</div>
<!-- end page content -->

<script>
    $(document).ready(function() {

        console.log('Jquery is working');

        $('#filterButton').click(function() {
            $('.filter-options').toggle();
        });

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

            console.log(category_id, priority, operator);

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

                    // // Clear the existing table rows
                    // $('#table tbody').empty();

                    // // Iterate through matched ticket IDs and populate the table rows
                    // matchedTicketIds.forEach(function(ticketId) {
                    //     var ticket = response.tickets.find(function(item) {
                    //         return item.id === ticketId;
                    //     });


                    //     if (ticket) {
                    //         var row = '<tr>' +
                    //             '<td>' + ticket.date + '</td>' +
                    //             '<td>' + ticket.ticket_number + '</td>' +
                    //             '<td>' + ticket.name + '</td>' +
                    //             '<td>' + ticket.email + '</td>' +
                    //             '<td>' + ticket.category + '</td>' +
                    //             '<td>' + ticket.priority + '</td>' +
                    //             '<td>' + ticket.assignee + '</td>' +
                    //             '<td>' + ticket.remarks + '</td>' +
                    //             '<td>' + ticket.actions + '</td>' +
                    //             '</tr>';

                    //         $('#table tbody').append(row);
                    //     }else {
                    //         // Log a message if the ticket is not found (this can help with debugging)
                    //         console.log('Ticket with ID ' + ticketId + ' not found in response.tickets');
                    //     }
                    // });


                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });
    });
</script>

@endsection

