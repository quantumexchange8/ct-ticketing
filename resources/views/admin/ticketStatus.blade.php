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
                                            {{-- <a href="{{ route('editTicketStatus', ['id' => $ticketStatus->id]) }}" class="btn btn-sm btn-soft-success btn-circle"> --}}
                                                <button class="btn btn-sm btn-soft-success btn-circle edit-status" data-status-id="{{ $ticketStatus->id }}">
                                                    <i class="dripicons-pencil"></i>
                                                </button>
                                            {{-- </a> --}}

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

                            {{-- <a href="{{ route('createTicketStatus') }}"> --}}
                                <button class="btn btn-danger mt-2" id="addStatus">Add New Ticket Status</button>
                            {{-- </a> --}}
                        </span><!--end table-->
                    </div><!--end card-body-->
                </div><!--end card-->
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div><!-- container -->
</div>
<!-- end page content -->

{{-- Add Status --}}
<div id="addStatusModal" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModalLabel">Create New Status</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('addTicketStatus') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="status">Ticket Status</label>
                            <input type="text" class="form-control" name="status" placeholder="Enter Status" autocomplete="off" value="{{ old('status') }}">
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-12 text-right">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
      </div>
    </div>
</div>

{{-- Edit Status --}}
<div id="editStatusModal" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModalLabel">Edit Status</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{ isset($ticketStatus) ? route('updateTicketStatus', $ticketStatus->id) : '#' }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="status">Ticket Status</label>
                            <input type="text" class="form-control" name="status" id="status" placeholder="Enter Status" autocomplete="off" value="{{ isset($ticketStatus) ? $ticketStatus->status : '' }}">
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <input type="hidden" class="form-control" name="id" id="id" placeholder="Enter Status" autocomplete="off" value="{{ isset($ticketStatus) ? $ticketStatus->id : '' }}">
                <div class="col-12 text-right">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
      </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Get the modal
        var addStatusModal = document.getElementById("addStatusModal");

        // Get the export button
        var addStatus = document.getElementById("addStatus");

        // When the user clicks the export button, display the modal
        addStatus.addEventListener("click", function() {
            $('#addStatusModal').modal('show');
        });

        // When the user clicks anywhere outside of the modal, close it
        $(document).on('click', function(event) {
            if ($(event.target).closest('.modal').length === 0) {
                $('#addStatusModal').modal('hide');
            }
        });

        // When the user clicks the edit button, fetch the title data and display it in the modal
        $('.edit-status').click(function() {
            var statusId = $(this).data('status-id');

            // Fetch the title data via AJAX
            $.ajax({
                url: '/edit-ticket-status/' + statusId,
                type: 'GET',
                data: { id: statusId },
                success: function(response) {
                    // Update the modal content with the fetched title data
                    $('#status').val(response.ticketStatus.status);
                    $('#id').val(response.ticketStatus.id);

                    // Show the modal
                    $('#editStatusModal').modal('show');
                }
            });
        });
    });
</script>

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

