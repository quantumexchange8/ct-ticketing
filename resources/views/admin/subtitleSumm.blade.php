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
                                <h4 class="page-title">Subtitle</h4>
                                {{-- <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">{{ $title->title_name }}</a></li>
                                    <li class="breadcrumb-item active"></li>
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
                            <h4 class="card-title">{{ $title->title_name }}</h4>
                        </div><!--end card-header-->
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
                                                    <div style="display: flex; justify-content: space-evenly;">

                                                        {{-- <a href="{{ route('viewMoreContent', ['id' => $subtitle->id]) }}" class="btn btn-sm btn-soft-purple btn-circle">
                                                            <i class="dripicons-preview"></i>
                                                        </a> --}}

                                                        <a href="{{ route('editSubtitle', ['id' => $subtitle->id]) }}" class="btn btn-sm btn-soft-success btn-circle">
                                                            <i class="dripicons-pencil"></i>
                                                        </a>

                                                        <form action="{{ route('deleteSubtitle', ['id' => $subtitle->id]) }}" method="POST">
                                                            @method('DELETE')
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-soft-danger btn-circle">
                                                                <i class="dripicons-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table><!--end /table-->
                            </div><!--end /tableresponsive-->
                            <span class="float-right">
                                {{-- @if (count($title->contents) === 0) --}}
                                    <a href="{{route('createSubtitle', ['title' => $title])}}">
                                        <button class="btn btn-danger mt-2">Add New Subtitle</button>
                                    </a>
                                {{-- @else
                                    <button id="but_add" class="btn btn-danger">Add New Content</button>
                                @endif --}}

                                {{-- <button class="btn  btn-primary" id="submit_data" data-endpoint="update-content">Submit</button> --}}
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
@endsection

