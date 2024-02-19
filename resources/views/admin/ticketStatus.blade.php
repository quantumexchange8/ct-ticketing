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
                            <h4 class="page-title">Ticket Status</h4>
                        </div><!--end col-->
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
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ticketStatuses as $ticketStatus)
                                    <tr>
                                        <td>{{ $ticketStatus->status }}</td>
                                        <td class="text-center" style="display: flex; justify-content: center; gap: 10px;">
                                            <a href="{{ route('editTicketStatus', ['id' => $ticketStatus->id]) }}" class="btn btn-sm btn-soft-success btn-circle">
                                                <i class="dripicons-pencil"></i>
                                            </a>

                                            <form action="{{ route('deleteTicketStatus', ['id' => $ticketStatus->id]) }}" method="POST" id="deleteForm{{ $ticketStatus->id }}" data-status-id="{{ $ticketStatus->id }}">
                                                @method('DELETE')
                                                @csrf
                                                <button type="button" class="btn btn-sm btn-soft-danger btn-circle" onclick="confirmDelete('deleteForm{{ $ticketStatus->id }}')">
                                                    <i class="dripicons-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <span class="float-right">
                            {{-- <button id="but_add" class="btn btn-danger">Add New Status</button>
                            <button class="btn  btn-primary" id="submit_data" data-endpoint="update-ticket-status" >Submit</button> --}}

                            <a href="{{ route('createTicketStatus') }}">
                                <button class="btn btn-danger mt-2">Add New Ticket Status</button>
                            </a>
                        </span><!--end table-->
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
        var ticketStatusId = document.getElementById(formId).getAttribute('data-status-id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This action will delete the status.',
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

