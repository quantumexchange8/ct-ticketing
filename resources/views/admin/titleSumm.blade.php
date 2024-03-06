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
                                                    <a href="{{ route('editTitle', ['id' => $title->id]) }}" class="btn btn-sm btn-soft-success btn-circle">
                                                        <i class="dripicons-pencil"></i>
                                                    </a>
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
                            <a href="{{ route('createTitle', ['project' => $project->id]) }}">
                                <button class="btn btn-danger mt-2">Add New Title</button>
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

