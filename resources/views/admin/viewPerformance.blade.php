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
                            <h4 class="page-title mt-2">Tickets managed by: {{ $users->name }}</h4>
                        </div><!--end col-->
                        <div class="col-2" style="display: flex; justify-content: flex-end; align-items: flex-end;">

                            <button type="button" class="btn" id="exportButton">
                                <i data-feather="download"></i>
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
                            <table id="datatable2" class="table table-bordered" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Ticket No.</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        {{-- <th>Subject</th>
                                        <th>Message</th> --}}
                                        <th>Category</th>
                                        <th>Project</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($tickets as $ticket)

                                    @php
                                        $createdAt = Carbon\Carbon::parse($ticket->created_at);

                                        $priorityStyle = '';
                                        $tooltipMessage = '';

                                        if ($ticket->priority === 'High' && $ticket->ticketStatus->status == 'Pending'  && $createdAt->diffInHours(now()) > 2) {
                                            $priorityStyle = 'color: red';
                                            $tooltipMessage = 'The ticket has been unsolved for 2 hours.';
                                        } elseif ($ticket->priority === 'Medium' && $ticket->ticketStatus->status == 'Pending'  && $createdAt->diffInHours(now()) > 12) {
                                            $priorityStyle = 'color: red';
                                            $tooltipMessage = 'The ticket has been unsolved for 12 hours.';
                                        } elseif ($ticket->priority === 'Low' && $ticket->ticketStatus->status == 'Pending'  && $createdAt->diffInHours(now()) > 24) {
                                            $priorityStyle = 'color: red';
                                            $tooltipMessage = 'The ticket has been unsolved for 24 hours.';
                                        }
                                    @endphp

                                    <tr>
                                        <td style="{{ $priorityStyle }}"
                                            @if ($priorityStyle)
                                                title="{{ $tooltipMessage }}"
                                            @endif>
                                            {{ $createdAt->format('d M Y') }}
                                        </td>
                                        <td>{{ $ticket->ticket_no }}</td>
                                        <td>{{ $ticket->sender_name }}</td>
                                        <td>{{ $ticket->sender_email }}</td>
                                        {{-- <td>{{ $ticket->subject }}</td>
                                        <td>{{ $ticket->message }}</td> --}}
                                        <td>{!! $ticket->supportCategories->category_name !!}</td>
                                        <td>{{ $ticket->projects->project_name }}</td>
                                        <td style ="{{ $ticket->priority === 'Medium' ? 'color: orange; font-weight: bold;' : ($ticket->priority === 'Low' ? 'color: #84f542; font-weight: bold;' : 'color: red; font-weight: bold;') }}">
                                            {{ $ticket->priority }}
                                        </td>
                                        <td>
                                            <span class="{{
                                                $ticket->ticketStatus->status === 'New' ? 'badge badge-md badge-boxed  badge-soft-primary' : (
                                                    $ticket->ticketStatus->status === 'Pending' ? 'badge badge-md badge-boxed  badge-soft-warning' : (
                                                        $ticket->ticketStatus->status === 'Solved' ? 'badge badge-md badge-boxed  badge-soft-success' : 'badge badge-md badge-boxed  badge-soft-danger'
                                                    )
                                                )
                                            }}">
                                                {{ $ticket->ticketStatus->status }}
                                            </span>
                                        </td>
                                        <td>{{ $ticket->remarks }}</td>

                                        <td class="text-center" style="display: flex; justify-content: center; gap: 10px;">
                                            <a href="{{ route('viewTicket', ['id' => $ticket->id]) }}" class="btn btn-sm btn-soft-purple btn-circle">
                                                <i class="dripicons-preview"></i>
                                            </a>

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
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table><!--end /table-->
                        </div><!--end /tableresponsive-->
                        <span class="float-right">
                            <a href="{{ route('createTicket') }}">
                                <button class="btn btn-danger mt-2">Add New Ticket</button>
                            </a>
                        </span>
                    </div><!--end card-body-->
                </div><!--end card-->
            </div> <!-- end col -->
        </div> <!-- end row -->

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
                timer: 1000, // 3000 milliseconds (3 seconds)
                showConfirmButton: false, // Hide the "OK" button
            });
        @endif
    });
</script>

<script>
    function confirmDelete(formId) {
        var ticketId = document.getElementById(formId).getAttribute('data-ticket-id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This action will delete the ticket.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }

    $('#exportButton').click(function() {
        var status = $('.page-title').text().trim();

        exportToExcel(status);
    });

    function exportToExcel(status) {
        var tableData = [];

        // Get table headers
        var headers = [
            "Date",
            "Ticket No.",
            "Name",
            "Email",
            "Subject",
            "Message",
            "Category",
            "Priority",
            "Remarks"
        ];
        tableData.push(headers);

        // Get table body data
        @foreach($tickets as $ticket)
            var rowData = [
                "{{ Carbon\Carbon::parse($ticket->created_at)->format('d M Y') }}",
                "{{ $ticket->ticket_no }}",
                "{{ $ticket->sender_name }}",
                "{{ $ticket->sender_email }}",
                "{{ $ticket->subject }}",
                "{{ $ticket->message }}",
                "{!! $ticket->supportCategories->category_name !!}",
                "{{ $ticket->priority }}",
                "{{ $ticket->remarks }}"
            ];
            tableData.push(rowData);
        @endforeach

        // Create workbook and worksheet
        var wb = XLSX.utils.book_new();
        var ws = XLSX.utils.aoa_to_sheet(tableData);

        // Add worksheet to workbook
        XLSX.utils.book_append_sheet(wb, ws, 'Tickets');

        // Construct filename with status
        var filename = '' + status.toLowerCase().replace(/\s+/g, '-') + '.xlsx';

        // Save workbook as Excel file
        XLSX.writeFile(wb, filename);
    }
</script>
@endsection

