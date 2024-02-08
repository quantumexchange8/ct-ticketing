@extends('layouts.masterAdmin')
@section('content')

    <!-- Page Content-->
    <div class="page-content">
        <div class="container-fluid">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="row">
                            <div class="col">
                                <h4 class="page-title mt-2">Ticket</h4>
                            </div><!--end col-->
                        </div><!--end row-->
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div><!--end row-->
            <!-- end page title end breadcrumb -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ $supportCategory->category_name }}</h4>
                        </div><!--end card-header-->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatable2" class="table table-bordered" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th style="display:none;">Category ID</th>
                                            <th style="display:none;">Ticket ID</th>
                                            <th>Date</th>
                                            <th>Ticket No.</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            {{-- <th>Subject</th>
                                            <th>Message</th> --}}
                                            <th>Status</th>
                                            <th>Priority</th>
                                            <th>PIC</th>
                                            <th>Remarks</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($supportCategory->tickets as $ticket)
                                        <tr>
                                            <td style="display:none;">{{ $supportCategory->id }}</td>
                                            <td style="display:none;">{{ $ticket->id }}</td>
                                            <td>{{ Carbon\Carbon::parse($ticket->created_at)->format('d M Y') }}</td>
                                            <td>{{ $ticket->ticket_no }}</td>
                                            <td>{{ $ticket->sender_name }}</td>
                                            <td>{{ $ticket->sender_email }}</td>
                                            {{-- <td>{{ $ticket->subject }}</td>
                                            <td>{{ $ticket->message }}</td> --}}
                                            <td>{!! $ticket->ticketStatus->status !!}</td>
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
        console.log('test');
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
        // console.log('Ticket ID:', ticketId);

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
</script>
@endsection

