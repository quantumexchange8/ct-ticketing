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
                            <h4 class="page-title mt-2">{{$tickets->id}}</h4>
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

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Ticket No.</th>
                                    </tr>
                                </thead>
                                <tbody id="ticketTableBody">

                                </tbody>
                            </table><!--end /table-->
                        </div><!--end /tableresponsive-->
                    </div><!--end card-body-->
                </div><!--end card-->
            </div> <!-- end col -->
        </div> <!-- end row -->


    </div><!-- container -->


</div>
<!-- end page content -->

<script>
    $(document).ready(function() { // Corrected the typo in 'function'
        $.ajax({
            url: '/get-ticket',
            method: 'GET',
            success: function(response) {

                var tickets = response.tickets;

                console.log(tickets);

                // Clear existing table rows
                $('#ticketTableBody').empty();

                tickets.forEach(function(ticketId) {
                    var row = '<tr>' +
                        '<td>' + ticketId + '</td>' +
                        '</tr>';

                    // Append the row to the tbody
                    $('#ticketTableBody').append(row);
                });

            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                console.error('Error fetching tickets:', error);
            }
        });

    });
</script>


{{-- <script>
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
</script> --}}

@endsection

