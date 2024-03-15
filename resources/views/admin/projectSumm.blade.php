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
                            <h4 class="page-title">Project</h4>
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
                                        <th>Project Name</th>
                                        <th>Description</th>
                                        <th>Owner</th>
                                        <th>Phone Number</th>
                                        <th>Show</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($projects as $project)
                                    <tr>
                                        <td>{{ $project->project_name }}</td>
                                        <td>{{ $project->description }}</td>
                                        <td>{{ $project->project_owner }}</td>
                                        <td>{{ $project->project_telno }}</td>
                                        <td>{{ $project->show == 1 ? 'Yes' : 'No' }}</td>
                                        <td class="text-center">
                                            <div style="display: flex; justify-content: center; gap: 10px;">
                                                <button class="btn btn-sm btn-soft-success btn-circle edit-project" data-project-id="{{ $project->id }}">
                                                    <i class="dripicons-pencil"></i>
                                                </button>

                                                <form action="{{ route('deleteProject', ['id' => $project->id]) }}" method="POST" id="deleteForm{{ $project->id }}" data-project-id="{{ $project->id }}">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="button" class="btn btn-sm btn-soft-danger btn-circle" onclick="confirmDelete('deleteForm{{ $project->id }}')">
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
                            <button class="btn btn-danger mt-2" id="addProject">Add New Project</button>
                        </span><!--end table-->
                    </div><!--end card-body-->
                </div><!--end card-->
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div><!-- container -->
</div>
<!-- end page content -->

{{-- Add Project --}}
<div id="addProjectModal" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModalLabel">Create New Project</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('addProject') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="project_name">Project Name</label>
                            <input type="text" class="form-control" name="project_name" placeholder="Enter Project Name" autocomplete="off" value="{{ old('project_name') }}">
                            @error('project_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" name="description" placeholder="Enter Description" autocomplete="off" value="{{ old('description') }}">
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="project_owner">Owner</label>
                            <input type="text" class="form-control" name="project_owner" placeholder="Enter Project Owner" autocomplete="off" value="{{ old('project_owner') }}">
                            @error('project_owner')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="project_telno">Phone Number</label>
                            <input type="text" class="form-control" name="project_telno" placeholder="Enter Phone Number" autocomplete="off" value="{{ old('project_telno') }}">
                            @error('project_telno')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="show">Show?</label>
                            <select class="form-control" name="show">
                                <option value="1" {{ old('show') == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old('show') == '0' ? 'selected' : '' }}>No</option>
                            </select>
                            @error('category_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">

                </div>

                <div class="col-12 text-right">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
      </div>
    </div>
</div>

{{-- Edit Project --}}
<div id="editProjectModal" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModalLabel">Edit Project</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('updateProject', $project->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="project_name">Project Name</label>
                            <input type="text" class="form-control" name="project_name" id="project_name" placeholder="Enter Project Name" autocomplete="off" value="{{ $project->project_name }}">
                            @error('project_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" name="description" id="description" placeholder="Enter Description" autocomplete="off" value="{{ $project->description }}">
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="project_owner">Project Owner</label>
                            <input type="text" class="form-control" name="project_owner" id="project_owner" placeholder="Enter Project Owner" autocomplete="off" value="{{ $project->project_owner }}">
                            @error('project_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="project_telno">Phone Number</label>
                            <input type="text" class="form-control" name="project_telno" id="project_telno" placeholder="Enter Phone Number" autocomplete="off" value="{{ $project->project_telno }}">
                            @error('project_telno')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="show">Show?</label>
                            <select class="form-control" name="show" id="show">
                                <option value="1" {{ $project->show  === '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ $project->show  === '0' ? 'selected' : '' }}>No</option>
                            </select>
                            @error('message')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6" style="display: none;">
                        <div class="form-group">
                            <label for="id">ID</label>
                            <input type="text" class="form-control" name="id" id="id" placeholder="Enter Phone Number" autocomplete="off">
                            @error('id')
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
        var addProjectModal = document.getElementById("addProjectModal");

        // Get the export button
        var addProject = document.getElementById("addProject");

        // When the user clicks the export button, display the modal
        addProject.addEventListener("click", function() {
            $('#addProjectModal').modal('show');
        });

        // When the user clicks anywhere outside of the modal, close it
        $(document).on('click', function(event) {
            if ($(event.target).closest('.modal').length === 0) {
                $('#addProjectModal').modal('hide');
            }
        });

        // When the user clicks the edit button, fetch the title data and display it in the modal
        $('.edit-project').click(function() {
            var projectId = $(this).data('project-id');

            // Fetch the title data via AJAX
            $.ajax({
                url: '/edit-project/' + projectId,
                type: 'GET',
                data: { id: projectId },
                success: function(response) {

                    // Update the modal content with the fetched title data
                    $('#project_name').val(response.project.project_name);
                    $('#description').val(response.project.description);
                    $('#project_owner').val(response.project.project_owner);
                    $('#project_telno').val(response.project.project_telno);
                    $('#show').val(response.project.show);
                    $('#id').val(response.project.id);

                    // Show the modal
                    $('#editProjectModal').modal('show');
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
        var projectId = document.getElementById(formId).getAttribute('data-project-id');
        // console.log('Title ID:', titleId);

        Swal.fire({
            title: 'Are you sure?',
            text: 'This action will delete the project and associated documentations.',
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

