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
                            <h4 class="page-title">
                                <a href="{{ route('titleSumm', ['project' => $project->id]) }}">{{ $project->project_name }}</a>
                                 - {{ $subtitles->first() ? $subtitles->first()->title->title_name : '' }}
                            </h4>

                            {{-- <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('titleSumm', ['project' => $project->id]) }}">Title ({{ $subtitles->first() ? $subtitles->first()->title->title_name : '' }})</a></li>
                                <li class="breadcrumb-item active">
                                    Subtitle
                                </li>
                            </ol> --}}
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
                        <h4 class="card-title">Subtitle</h4>
                    </div><!--end card-header-->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Subtitle Sequence</th>
                                        <th>Title</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($subtitles as $subtitle)
                                    <tr>
                                        <td>{{ $subtitle->s_sequence }}</td>
                                        <td>{{ $subtitle->subtitle_name }}</td>
                                        <td class="text-center">
                                            <div style="display: flex; justify-content: center; gap: 10px;">

                                                <a href="{{ route('viewMoreContent', ['id' => $subtitle->id]) }}" class="btn btn-sm btn-soft-purple btn-circle">
                                                    <i class="dripicons-preview"></i>
                                                </a>
                                                {{-- <a href="{{ route('editSubtitle', ['id' => $subtitle->id]) }}" class="btn btn-sm btn-soft-success btn-circle"> --}}
                                                    <button class="btn btn-sm btn-soft-success btn-circle edit-subtitle" data-subtitle-id="{{ $subtitle->id }}">
                                                        <i class="dripicons-pencil"></i>
                                                    </button>
                                                {{-- </a> --}}

                                                <form action="{{ route('deleteSubtitle', ['id' => $subtitle->id]) }}" method="POST" id="deleteForm{{ $subtitle->id }}" data-subtitle-id="{{ $subtitle->id }}">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="button" class="btn btn-sm btn-soft-danger btn-circle" onclick="confirmDelete('deleteForm{{ $subtitle->id }}')">
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

                            {{-- <a href="{{route('createSubtitle', ['title' => $title->id])}}"> --}}
                                <button class="btn btn-danger mt-2" id="addSubtitle">Add New Subtitle</button>
                            {{-- </a> --}}
                        </span><!--end table-->
                    </div><!--end card-body-->
                </div><!--end card-->
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div><!-- container -->
</div>
<!-- end page content -->

{{-- Add Subtitle --}}
<div id="addSubtitleModal" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModalLabel">Create New Subitle</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('addSubtitle', ['title' => $title]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="title-name">Subtitle</label>
                            <input type="text" class="form-control" name="subtitle_name" placeholder="Enter Subtitle" autocomplete="off" value="{{ old('subtitle_name') }}">
                            @error('subtitle_name')
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

{{-- Edit Subtitle --}}
<div id="editSubtitleModal" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModalLabel">Edit Subtitle</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{ isset($subtitle) ? route('updateSubtitle', $subtitle->id) : '#'}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="s_sequence">Subtitle Sequence</label>
                            <input type="number" class="form-control" name="s_sequence" id="s_sequence" placeholder="Enter Subtitle Sequence" autocomplete="off" value="{{ isset($subtitle) ? $subtitle->s_sequence : ''}}">
                            @error('s_sequence')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="subtitle_name">Subtitle</label>
                            <input type="text" class="form-control" name="subtitle_name" id="subtitle_name" placeholder="Enter Title" autocomplete="off" value="{{ isset($subtitle) ? $subtitle->subtitle_name : ''}}">
                            @error('subtitle_name')
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
        var addSubtitleModal = document.getElementById("addSubtitleModal");

        // Get the export button
        var addSubtitle = document.getElementById("addSubtitle");

        // When the user clicks the export button, display the modal
        addSubtitle.addEventListener("click", function() {
            $('#addSubtitleModal').modal('show');
        });

        // When the user clicks anywhere outside of the modal, close it
        $(document).on('click', function(event) {
            if ($(event.target).closest('.modal').length === 0) {
                $('#addSubtitleModal').modal('hide');
            }
        });

        // When the user clicks the edit button, fetch the title data and display it in the modal
        $('.edit-subtitle').click(function() {
            var subtitleId = $(this).data('subtitle-id');

            // Fetch the title data via AJAX
            $.ajax({
                url: '/edit-subtitle/' + subtitleId,
                type: 'GET',
                data: { id: subtitleId },
                success: function(response) {
                    console.log(response.subtitle.s_sequence);
                    // Update the modal content with the fetched title data
                    $('#s_sequence').val(response.subtitle.s_sequence);
                    $('#subtitle_name').val(response.subtitle.subtitle_name);

                    // Show the modal
                    $('#editSubtitleModal').modal('show');
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
        var subtitleId = document.getElementById(formId).getAttribute('data-subtitle-id');
        // console.log('Title ID:', titleId);

        Swal.fire({
            title: 'Are you sure?',
            text: 'This action will delete the subtitles and its associated contents.',
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

