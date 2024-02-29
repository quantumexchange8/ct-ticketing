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
                            <h4 class="page-title">Subtitle - {{ $title->title_name }}</h4>
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <!-- end page title end breadcrumb -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    {{-- <div class="card-header">
                        <h4 class="card-title">{{ $title->title_name }}</h4>
                    </div><!--end card-header--> --}}
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Subtitle Sequence</th>
                                        <th>Subtitle</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($title->subtitles as $subtitle)
                                        <tr>
                                            <td>{{ $subtitle->s_sequence }}</td>
                                            <td>{{ $subtitle->subtitle_name }}</td>
                                            <td class="text-center">
                                                <div style="display: flex; justify-content: center; gap: 10px;">
                                                    @if (Auth::user()->manage_content == 1)
                                                        <a href="{{ route('viewMoreContent', ['id' => $subtitle->id]) }}" class="btn btn-sm btn-soft-purple btn-circle">
                                                            <i class="dripicons-preview"></i>
                                                        </a>
                                                    @endif

                                                    @if (Auth::user()->manage_subtitle == 1)
                                                        <a href="{{ route('editSubtitle', ['id' => $subtitle->id]) }}" class="btn btn-sm btn-soft-success btn-circle">
                                                            <i class="dripicons-pencil"></i>
                                                        </a>
                                                    @endif

                                                    @if (Auth::user()->manage_subtitle == 1)
                                                        <form action="{{ route('deleteSubtitle', ['id' => $subtitle->id]) }}" method="POST" id="deleteForm{{ $subtitle->id }}" data-subtitle-id="{{ $subtitle->id }}">
                                                            @method('DELETE')
                                                            @csrf
                                                            <button type="button" class="btn btn-sm btn-soft-danger btn-circle" onclick="confirmDelete('deleteForm{{ $subtitle->id }}')">
                                                                <i class="dripicons-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table><!--end /table-->
                        </div><!--end /tableresponsive-->
                        <span class="float-right">
                            <a href="{{route('createSubtitle', ['title' => $title])}}">
                                <button class="btn btn-danger mt-2">Add New Subtitle</button>
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
                timer: 1000,
                showConfirmButton: false,
            });
        @endif
    });
</script>

<script>
    function confirmDelete(formId) {
        var subtitleId = document.getElementById(formId).getAttribute('data-subtitle-id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This action will delete the subtitle and its associated contents.',
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

