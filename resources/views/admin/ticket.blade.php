@extends('layouts.masterAdmin')
@section('content')

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<style>
    .lanes {
        display: flex;
        align-items: flex-start;
        justify-content: space-evenly;
        gap: 16px;

        /* padding: 24px 32px; */

        overflow: scroll;
        height: 100%;
    }

    .swim-lane {
        display: flex;
        flex-direction: column;
        gap: 5px;

        background: #f1f5fa;
        /* box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.25); */

        padding: 12px;
        margin-top: 15px;
        margin-bottom: 15px;
        border-radius: 4px;
        min-width: 225px;
        min-height: 120px;

        flex-shrink: 0;
    }
    .custom-popup-class {
        background-color: #f0f0f0; /* Change background color */
        width: 400px; /* Change width */
        height: 200px; /* Change height */
    }

    .search-add {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: flex-end;
        align-items: stretch;
    }

    .search {
        display: flex;
        align-items: center;
        margin-right:30px;
    }

    @media screen and (min-width: 1600px) {
        .swim-lane {
            width: 350px;
        }
    }
</style>


<!-- Page Content-->
<div class="page-content">
    <div class="container-fluid">

        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="row">
                        <div class="col-4">
                            <h4 class="page-title">Ticket</h4>
                        </div><!--end col-->
                        <div class="col-8 search-add">
                            <div class="search">
                                <label>Search: </label>
                                <div class="col-sm-10" style="padding-right: 0;">
                                    <input class="form-control form-control-sm" type="search" id="searchInput" name="search" autocomplete="off">
                                </div>
                            </div>

                            <a href="{{ route('createTicket') }}">
                                <button type="button" class="btn btn-soft-primary waves-effect waves-light">Add Ticket</button>
                            </a>
                        </div>
                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <!-- end page title end breadcrumb -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="lanes" id="kanban-board">
                        {{-- @foreach ($statuses as $index => $status)
                        <div id="{{$status->id}}" class="swim-lane">
                            <div class="kanban-main-card">
                                <div class="kanban-box-title">
                                    <h4 class="card-title mt-0 mb-3">{{ $status->status }} - {{$status->id}}</h4>
                                </div>
                                @foreach ($status->tickets as $ticket)

                                    <div class="task" style="cursor: move;" draggable="true">
                                        <div class="card" >
                                            <div class="card-body">
                                                <div class="dropdown d-inline-block float-right">
                                                    <a class="dropdown-toggle mr-n2 mt-n2" id="drop2" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                                        <i class="las la-ellipsis-v font-18 text-muted"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="drop2">
                                                        <a class="dropdown-item" href="{{ route('viewTicket', ['id' => $ticket->id]) }}">View</a>
                                                        <a class="dropdown-item" href="{{ route('editTicket', ['id' => $ticket->id]) }}">Edit</a>
                                                        <a class="dropdown-item" href="{{ route('deleteTicket', ['id' => $ticket->id]) }}">Delete</a>
                                                    </div>
                                                </div><!--end dropdown-->

                                                <div style="display:flex; align-items:flex-end;">
                                                    @if ($ticket->priority === 'High')
                                                        <i class="mdi mdi-circle-outline d-block mt-n2 font-18 text-danger"></i>
                                                    @elseif ($ticket->priority === 'Medium')
                                                        <i class="mdi mdi-circle-outline d-block mt-n2 font-18 text-warning"></i>
                                                    @elseif ($ticket->priority === 'Low')
                                                        <i class="mdi mdi-circle-outline d-block mt-n2 font-18 text-success"></i>
                                                    @else
                                                        <i class="mdi mdi-circle-outline d-block mt-n2 font-18 text-primary"></i>
                                                    @endif

                                                    <div style="margin-left: 10px;">
                                                        <h5 class="my-1 font-14">{{ Carbon\Carbon::parse($ticket->created_at)->format('d M Y') }}</h5>
                                                    </div>

                                                </div>

                                                <p class="text mt-3 m-0">{{ $ticket->ticket_no }} - {{$status->id}}</p>
                                                <p class="text m-0">{{ $ticket->sender_name }}</p>
                                                <p class="text mb-2">{{ $ticket->sender_email }}</p>
                                            </div><!--end card-body-->
                                        </div><!--end card-->
                                    </div><!--end project-list-left-->
                                @endforeach
                            </div><!--end /div-->
                        </div><!--end kanban-col-->
                        @endforeach --}}
                    </div><!--end kanban-board-->
                </div><!--end card-->
            </div><!--end col-->
        </div><!--end row-->

    </div><!-- container -->
</div>
<!-- end page content -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    $(document).ready(function() {

        $('#searchInput').on('input', function(e) {
            e.preventDefault();
            var searchTerm = $(this).val().trim();

            // Call the function to fetch and display tickets with the new search term
            fetchAndDisplayTickets(searchTerm);
        });

        // Function to fetch tickets by status and update kanban board
        function fetchAndDisplayTickets(searchTerm) {
            $.ajax({
                url: '/get-ticket-by-status',
                data: {
                    searchTerm: searchTerm
                },
                method: 'GET',
                success: function(response) {
                    var statuses = response.statuses;

                    updateKanbanBoard(statuses);

                    // Enable drag and drop functionality after updating kanban board
                    enableDragAndDrop();
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching tickets:', error);
                }
            });
        }

        // Function to update the kanban board with tickets
        function updateKanbanBoard(statuses) {
            var kanbanBoard = $('#kanban-board');

            // Clear existing content in the kanban board
            kanbanBoard.empty();

            // Loop through each status
            statuses.forEach(function(statuses) {
                var swimLane = $('<div class="swim-lane" id="' + statuses.status.id + '">');
                var kanbanMainCard = $('<div class="kanban-main-card">');
                var kanbanBoxTitle = $('<div class="kanban-box-title">').append('<h4 class="card-title mt-0 mb-3">' + statuses.status.status +  ' (' + statuses.ticket_count + ')' + '</h4>');

                // Append kanban box title to kanban main card
                kanbanMainCard.append(kanbanBoxTitle);

                // Loop through tickets for this status
                statuses.tickets.forEach(function(ticket) {

                    var createdAt = new Date(ticket.created_at);
                    var formattedDate = createdAt.toLocaleDateString('en-GB', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    });

                    var ticketId = ticket.id;
                    var picId = ticket.pic_id;
                    var picName = (ticket.name) ? ticket.name : '';

                    var viewRoute = "/view-ticket/" + ticketId;
                    var editRoute = "/edit-ticket/" + ticketId;
                    var deleteRoute = "/delete-ticket/" + ticketId;

                    var deleteForm = $('<form>')
                        .attr('action', deleteRoute)
                        .attr('method', 'POST')
                        .attr('id', 'deleteForm' + ticketId)
                        .attr('data-ticket-id', ticketId)
                        .on('submit', function(event) {
                            event.preventDefault();

                            $(this).submit(); // Submit the form
                        });

                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

                    deleteForm.append(
                        $('<input>').attr('type', 'hidden').attr('name', '_method').val('DELETE'),
                        $('<input>').attr('type', 'hidden').attr('name', '_token').val(csrfToken),
                    );

                    var deleteButton = $('<button>')
                        .addClass('dropdown-item')
                        .text('Delete')
                        .on('click', function(event) {
                            event.preventDefault();

                            // Get the ticket ID
                            var ticketId = $(this).closest('.task').attr('id');

                            var deleteRoute = "/delete-ticket/" + ticketId;

                            // Call the deleteTicket function with the ticket ID
                            deleteTicket(ticketId, deleteRoute);
                        });

                    deleteButton.appendTo(deleteForm);

                    var currentTime = new Date();
                    var cardStyle = '';
                    var tooltipMessage = '';


                    if (picId == null) {
                        cardStyle = 'background: #edf3ff';
                        tooltipMessage = 'Please assign PIC to the ticket.';
                    } else if (ticket.priority === 'High' && ticket.status !== 'Solved' && ticket.status !== 'Closed' && createdAt && currentTime - createdAt > 2 * 60 * 60 * 1000) {
                        cardStyle = 'background: #f4cccc';
                        tooltipMessage = 'Ticket must solve in 2 hours';
                    } else if (ticket.priority === 'Medium' && ticket.status !== 'Solved' && ticket.status !== 'Closed' && createdAt && currentTime - createdAt > 12 * 60 * 60 * 1000) {
                        cardStyle = 'background: #f4cccc';
                        tooltipMessage = 'Ticket must solve in 12 hours';
                    } else if (ticket.priority === 'Low' && ticket.status !== 'Solved' && ticket.status !== 'Closed' && createdAt && currentTime - createdAt > 24 * 60 * 60 * 1000) {
                        cardStyle = 'background: #f4cccc';
                        tooltipMessage = 'Ticket must solve in 24 hours';
                    }

                    // if (picId == null) {
                    //     cardStyle = 'background: #edf3ff';
                    //     tooltipMessage = 'Please assign PIC to the ticket.';
                    // } else if (ticket.priority === 'High' && ticket.status !== 'Solved' && ticket.status !== 'Closed' && createdAt && currentTime - createdAt > 2 * 60 * 60 * 1000) {
                    //     cardStyle = 'background: #f4cccc';
                    //     tooltipMessage = 'Ticket must solve in 2 hours';
                    // } else if (ticket.priority === 'Medium' && ticket.status !== 'Solved' && ticket.status !== 'Closed' && createdAt && currentTime - createdAt > 12 * 60 * 60 * 1000) {
                    //     cardStyle = 'background: #fff9ee';
                    //     tooltipMessage = 'Ticket must solve in 12 hours';
                    // } else if (ticket.priority === 'Low' && ticket.status !== 'Solved' && ticket.status !== 'Closed' && createdAt && currentTime - createdAt > 24 * 60 * 60 * 1000) {
                    //     cardStyle = 'background: #d9ead3';
                    //     tooltipMessage = 'Ticket must solve in 24 hours';
                    // }

                    // var threeDaysAgo = new Date();
                    // var sevenDaysAgo = new Date();

                    // threeDaysAgo.setDate(threeDaysAgo.getDate() - 3);
                    // sevenDaysAgo.setDate(sevenDaysAgo.getDate() - 7);

                    // if (picId == null) {
                    //     cardStyle = 'background: #edf3ff';
                    //     tooltipMessage = 'Please assign PIC to the ticket.';
                    // } else if (picId !== null && ticket.status == 'Pending' && createdAt < sevenDaysAgo) {
                    //     cardStyle = 'background: #fcc0cf';
                    //     tooltipMessage = 'Ticket is pending for more than 7 days';
                    // } else if (picId !== null && ticket.status == 'Pending' && createdAt < threeDaysAgo) {
                    //     cardStyle = 'background: #fff9ee';
                    //     tooltipMessage = 'Ticket is pending for more than 3 days';
                    // }



                    var task = $('<div class="task" id="' + ticketId + '" style="cursor: move;" draggable="true">');
                    var card = $('<div class="card ' + priorityIconClass + '" style="' + cardStyle + '" title="' + (cardStyle ? tooltipMessage : '') + '">');
                    var cardBody = $('<div class="card-body">');
                    var dropdown = $('<div class="dropdown d-inline-block float-right">').append('<a class="dropdown-toggle mr-n2 mt-n2" id="drop2" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false"><i class="las la-ellipsis-v font-18 text-muted"></i></a>');
                    var dropdownMenu = $('<div class="dropdown-menu dropdown-menu-right" aria-labelledby="drop2">').append(
                        '<a class="dropdown-item" href="' + viewRoute + '">View</a>',
                        '<a class="dropdown-item" href="' + editRoute + '">Edit</a>',
                        deleteForm
                    );
                    dropdown.append(dropdownMenu);

                    var priorityIconClass = getPriorityIconClass(ticket.priority);

                    cardBody.append(
                        dropdown,
                        $('<div style="display: flex; align-items: flex-end;">').append(
                            $('<i>').addClass(priorityIconClass),
                            $('<div style="margin-left: 10px; font-weight: bold;">').addClass('my-1 font-14').text(formattedDate)
                        ),
                        '<p class="text mt-3 m-0" style="font-weight: bold;">' + ticket.category_name + '</p>',
                        '<p class="text m-0" style="font-weight: bold;">' + picName + '</p>',
                        '<p class="text mt-2 m-0 ">' + ticket.ticket_no + '</p>',
                        '<p class="text m-0">' + ticket.sender_name + '</p>',
                        '<p class="text mb-2">' + ticket.sender_email + '</p>'
                    );

                    card.append(cardBody);
                    task.append(card);

                    kanbanMainCard.append(task);
                });

                // Append kanban main card to swim lane
                swimLane.append(kanbanMainCard);

                // Append swim lane to kanban board
                kanbanBoard.append(swimLane);
            });
        }

        // Function to enable drag and drop functionality
        function enableDragAndDrop() {
            const draggables = document.querySelectorAll(".task");
            const droppables = document.querySelectorAll(".swim-lane");

            draggables.forEach((task) => {
                task.addEventListener("dragstart", (e) => {
                    // Capture the ticket ID and original status
                    const ticketId = task.id;
                    const originalStatus = task.closest(".swim-lane").id;

                    // Set data in the dataTransfer object
                    e.dataTransfer.setData("text/plain", ticketId);
                    e.dataTransfer.setData("status", originalStatus);

                    // Log the ticket ID and original status
                    // console.log("Dragged Ticket ID:", ticketId);
                    // console.log("Original Status:", originalStatus);
                });
            });

            droppables.forEach((zone) => {
                zone.addEventListener("dragover", (e) => {
                    e.preventDefault();
                });

                zone.addEventListener("drop", (e) => {
                    e.preventDefault();

                    // Get the ticket ID and original status from the dataTransfer object
                    const ticketId = e.dataTransfer.getData("text/plain");
                    const originalStatus = e.dataTransfer.getData("status");

                    // Get the new status from the current swim-lane ID
                    const newStatus = zone.id;

                    // console.log("Dropped Ticket ID:", ticketId);
                    // console.log("New Status:", newStatus);

                    $.ajax({
                        url: '/update-ticket-kanban',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            ticketId: ticketId,
                            newStatus: newStatus
                        },
                        success: function(response) {

                            // Swal.fire({
                            //     icon: 'success',
                            //     title: 'Success',
                            //     showConfirmButton: false,
                            //     timer: 1000,
                            //     customClass: {
                            //         popup: 'custom-popup-class'
                            //     }
                            // });

                            fetchAndDisplayTickets();
                        },
                        error: function(xhr, status, error) {
                            console.error('Error updating ticket status:', error);
                        }
                    });

                    // Move the task to the new column
                    const curTask = document.getElementById(ticketId);
                    zone.appendChild(curTask);
                });
            });
        }

        // Function to get priority icon class based on priority
        function getPriorityIconClass(priority) {
            switch (priority) {
                case 'High':
                    return 'mdi mdi-circle-outline d-block mt-n2 font-18 text-danger';
                case 'Medium':
                    return 'mdi mdi-circle-outline d-block mt-n2 font-18 text-warning';
                case 'Low':
                    return 'mdi mdi-circle-outline d-block mt-n2 font-18 text-success';
                default:
                    return 'mdi mdi-circle-outline d-block mt-n2 font-18 text-primary';
            }
        }

        function deleteTicket(ticketId, deleteRoute) {

            // Display confirmation modal
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this ticket!',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'No, cancel!',
                confirmButtonText: 'Yes, delete it!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send AJAX request to delete
                    $.ajax({
                        url: deleteRoute,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            // Optionally, update UI or reload data
                            fetchAndDisplayTickets();
                        },
                        error: function(xhr, status, error) {
                            // console.error('Error deleting ticket:', error);
                            fetchAndDisplayTickets();
                        }
                    });
                }
            });
        }

        // Initial call to fetch and display tickets
        fetchAndDisplayTickets();
    });


</script>
@endsection

