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
                            <h4 class="page-title">Enhancement</h4>
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
                        <h4 class="card-title">Title</h4>
                    </div><!--end card-header-->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Version</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($enhancements as $enhancement)
                                    <tr>
                                        <td>{{ $enhancement->enhancement_title }}</td>
                                        <td>{{ $enhancement->enhancement_description ?? null }}</td>
                                        <td>{{ $enhancement->version ?? null }}</td>
                                        <td class="text-center">
                                            <div style="display: flex; justify-content: center; gap: 10px;">
                                                {{-- <a href="{{ route('editEnhancement', ['id' => $enhancement->id]) }}" class="btn btn-sm btn-soft-success btn-circle"> --}}
                                                    <button class="btn btn-sm btn-soft-success btn-circle edit-enhancement" data-enhancement-id="{{ $enhancement->id }}">
                                                        <i class="dripicons-pencil"></i>
                                                    </button>
                                                {{-- </a> --}}

                                                <form action="{{ route('deleteEnhancement', ['id' => $enhancement->id]) }}" method="POST" id="deleteForm{{ $enhancement->id }}" data-enhancement-id="{{ $enhancement->id }}">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="button" class="btn btn-sm btn-soft-danger btn-circle" onclick="confirmDelete('deleteForm{{ $enhancement->id }}')">
                                                        <i class="dripicons-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <span class="float-right">
                            {{-- <button id="but_add" class="btn btn-danger">Add New Title</button>
                            <button class="btn  btn-primary" id="submit_data" data-endpoint="update-title" >Submit</button> --}}
                            {{-- <a href="{{ route('createEnhancement') }}"> --}}
                                <button class="btn btn-danger mt-2" id="addEnhancement">Add New Enhancement</button>
                            {{-- </a> --}}
                        </span><!--end table-->
                    </div><!--end card-body-->
                </div><!--end card-->
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div><!-- container -->
</div>
<!-- end page content -->

{{-- Add Enhancement --}}
<div id="addEnhancementModal" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModalLabel">Create New Enhancement</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('addEnhancement') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="enhancement_title">Title</label>
                            <input type="text" class="form-control" name="enhancement_title" placeholder="Enter Title" autocomplete="off" value="{{ old('enhancement_title') }}">
                            @error('enhancement_title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="enhancement_description">Description</label>
                            <input type="text" class="form-control" name="enhancement_description" placeholder="Enter Description" autocomplete="off" value="{{  old('enhancement_description') }}">
                            @error('enhancement_description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4">
                        <div class="checkbox checkbox-primary">
                            <input id="major_update" name="major_update" type="checkbox" value="1">
                            <label for="major_update">
                                Major Update, Structure Update
                            </label>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="checkbox checkbox-primary">
                            <input id="table_migrate" name="table_migrate" type="checkbox" value="1">
                            <label for="table_migrate">
                                Table Migrate
                            </label>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="checkbox checkbox-primary">
                            <input id="minor_update" name="minor_update" type="checkbox" value="1">
                            <label for="minor_update">
                                Minor Update
                            </label>
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

{{-- Edit Enhancement --}}
<div id="editEnhancementModal" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModalLabel">Edit Enhancement</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{ isset($enhancement) ? route('updateEnhancement', $enhancement->id) : '#' }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="enhancement_title">Title</label>
                            <input type="text" class="form-control" id="enhancement_title" name="enhancement_title" placeholder="Enter Title" autocomplete="off" value="{{ isset($enhancement) ? $enhancement->enhancement_title : '' }}">
                            @error('enhancement_title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="enhancement_description">Description</label>
                            <input type="text" class="form-control" id="enhancement_description" name="enhancement_description" placeholder="Enter Description" autocomplete="off" value="{{ isset($enhancement) ? $enhancement->enhancement_description : '' }}">
                            @error('enhancement_description')
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

<script>
    $(document).ready(function() {
        // Get the modal
        var addEnhancementModal = document.getElementById("addEnhancementModal");

        // Get the export button
        var addEnhancement = document.getElementById("addEnhancement");

        // When the user clicks the export button, display the modal
        addEnhancement.addEventListener("click", function() {
            $('#addEnhancementModal').modal('show');
        });

        // When the user clicks anywhere outside of the modal, close it
        $(document).on('click', function(event) {
            if ($(event.target).closest('.modal').length === 0) {
                $('#addEnhancementModal').modal('hide');
            }
        });

        // When the user clicks the edit button, fetch the title data and display it in the modal
        $('.edit-enhancement').click(function() {
            var enhancementId = $(this).data('enhancement-id');

            // Fetch the title data via AJAX
            $.ajax({
                url: '/edit-enhancement/' + enhancementId,
                type: 'GET',
                data: { id: enhancementId },
                success: function(response) {
                    // Update the modal content with the fetched title data
                    $('#enhancement_title').val(response.enhancement.enhancement_title);
                    $('#enhancement_description').val(response.enhancement.enhancement_description);

                    // Show the modal
                    $('#editEnhancementModal').modal('show');
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
        var enhancementId = document.getElementById(formId).getAttribute('data-enhancement-id');
        // console.log('Title ID:', titleId);

        Swal.fire({
            title: 'Are you sure?',
            text: 'This action will delete the data.',
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

