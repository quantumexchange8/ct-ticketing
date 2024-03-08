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
                            <h4 class="page-title">{{ $project->project_name}}</h4>
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
                                        <th style="display: none;">Title ID</th>
                                        <th>Title Sequence</th>
                                        <th>Title</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($titles as $title)
                                    <tr>
                                        <td style="display: none;">{{ $title->id }}</td>
                                        <td>{{ $title->t_sequence }}</td>
                                        <td>{{ $title->title_name }}</td>
                                        <td class="text-center">
                                            <div style="display: flex; justify-content: center; gap: 10px;">

                                                @if (Auth::user()->manage_subtitle == 1)
                                                    <a href="{{ route('viewMoreSubtitle', ['id' => $title->id]) }}" class="btn btn-sm btn-soft-purple btn-circle">
                                                        <i class="dripicons-preview"></i>
                                                    </a>
                                                @endif

                                                @if (Auth::user()->manage_title == 1)
                                                    {{-- <a href="{{ route('editTitle', ['id' => $title->id]) }}" class="btn btn-sm btn-soft-success btn-circle edit-title" data-title-id="{{ $title->id }}"> --}}
                                                        <button class="btn btn-sm btn-soft-success btn-circle edit-title" data-title-id="{{ $title->id }}">
                                                            <i class="dripicons-pencil"></i>
                                                        </button>
                                                    {{-- </a> --}}
                                                @endif

                                                @if (Auth::user()->manage_title == 1)
                                                    <form action="{{ route('deleteTitle', ['id' => $title->id]) }}" method="POST" id="deleteForm{{ $title->id }}" data-title-id="{{ $title->id }}">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="button" class="btn btn-sm btn-soft-danger btn-circle" onclick="confirmDelete('deleteForm{{ $title->id }}')">
                                                            <i class="dripicons-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
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
                            {{-- <a href="{{ route('createTitle', ['project' => $project->id]) }}"> --}}
                                <button class="btn btn-danger mt-2" id="addTitle">Add New Title</button>
                            {{-- </a> --}}
                        </span><!--end table-->
                    </div><!--end card-body-->
                </div><!--end card-->
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div><!-- container -->
</div>
<!-- end page content -->

{{-- Add Title --}}
<div id="addTitleModal" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModalLabel">Create New Title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('addTitle', ['project' => $project]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="title-name">Title</label>
                            <input type="text" class="form-control" name="title_name" placeholder="Enter Title" autocomplete="off" value="{{ old('title_name') }}">

                            @error('title_name')
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

{{-- Edit Title --}}
<div id="editTitleModal" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModalLabel">Edit Title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{ isset($title) ? route('updateTitle', $title->id) : '#' }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="t_sequence">Title Sequence</label>
                            <input type="number" class="form-control" name="t_sequence" id="t_sequence" placeholder="Enter Title Sequence" autocomplete="off" value="{{ isset($title) ? $title->t_sequence : '' }}">
                            @error('t_sequence')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="title_name">Title</label>
                            <input type="text" class="form-control" name="title_name" id="title_name" placeholder="Enter Title" autocomplete="off" value="{{ isset($title) ? $title->title_name : '' }}">
                            @error('title_name')
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
        var addTitleModal = document.getElementById("addTitleModal");

        // Get the export button
        var addTitle = document.getElementById("addTitle");

        // When the user clicks the export button, display the modal
        addTitle.addEventListener("click", function() {
            $('#addTitleModal').modal('show');
        });

        // When the user clicks anywhere outside of the modal, close it
        $(document).on('click', function(event) {
            if ($(event.target).closest('.modal').length === 0) {
                $('#addTitleModal').modal('hide');
            }
        });

        // When the user clicks the edit button, fetch the title data and display it in the modal
        $('.edit-title').click(function() {
            var titleId = $(this).data('title-id');

            // Fetch the title data via AJAX
            $.ajax({
                url: '/edit-title/' + titleId,
                type: 'GET',
                data: { id: titleId },
                success: function(response) {
                    // Update the modal content with the fetched title data
                    $('#t_sequence').val(response.title.t_sequence);
                    $('#title_name').val(response.title.title_name);

                    // Show the modal
                    $('#editTitleModal').modal('show');
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
        var titleId = document.getElementById(formId).getAttribute('data-title-id');
        // console.log('Title ID:', titleId);

        Swal.fire({
            title: 'Are you sure?',
            text: 'This action will delete the title and its associated subtitles and contents.',
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

