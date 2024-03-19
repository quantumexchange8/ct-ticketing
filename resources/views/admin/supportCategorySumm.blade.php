@extends('layouts.masterAdmin')
@section('content')

<!-- Page Content-->
<div class="page-content">
    <div class="container-fluid">
        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="row" style="display: flex; align-items: center;">
                        <div class="col-8">
                            <h4 class="page-title">{{ $project->project_name }} - FAQ</h4>
                        </div><!--end col-->
                        <div class="col-4" style="display: flex; justify-content: flex-end;">
                            <a href="{{route('createSub', ['project' => $project])}}">
                                <button type="button" class="btn btn-soft-primary waves-effect waves-light">Add FAQ</button>
                            </a>
                        </div>
                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <!-- end page title end breadcrumb -->
        @php
            $colors = ['#bed3fe', '#e3e6f0', '#b8f4db', '#bde6fa', '#ffebc1', '#99a1b7', '#b2bfc2'];
            $colorIndex = 0;
        @endphp

        @foreach ($supportCategories as $supportCategory)
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header" style="background-color: {{ $colors[$colorIndex] }}">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <h4 class="card-title">{!! $supportCategory->category_name !!}</h4>
                                {{-- <a href="{{ route('supportSubSumm', ['supportCategory' => $supportCategory->id, 'project' => $project->id]) }}">
                                    <i data-feather="edit-3" class="align-self-center menu-icon"></i>
                                </a> --}}
                            </div>
                        </div><!--end card-header-->
                    </div><!--end card-->
                </div><!--end col-->
            </div>

            <div class="row">
                @foreach ($supportSubCategoriesByCategory[$supportCategory->id] as $subcategory)
                    <div class="col-sm-3">
                        <div class="card">
                            <div class="card-header" id="{{ $subcategory->id }}">

                                <div class="dropdown d-inline-block float-right">
                                    <a class="dropdown-toggle mr-n2 mt-n2" id="drop3" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                        <i class="las la-ellipsis-v font-18 text-muted"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="drop3">
                                        <a class="dropdown-item" href="{{ route('editSub', ['id' => $subcategory->id]) }}">Edit</a>


                                        <form action="{{ route('deleteSub', ['id' => $subcategory->id]) }}" method="POST" id="{{ 'deleteForm' . $subcategory->id }}" data-subcategory-id="{{ $subcategory->id }}">
                                            @method('DELETE')
                                            @csrf
                                            <a class="dropdown-item" href="javascript:void(0);" onclick="confirmDelete('{{ 'deleteForm' . $subcategory->id }}')">Delete</a>
                                        </form>
                                    </div>
                                </div><!--end dropdown-->
                                <h4 class="card-title">{{ $subcategory->sub_name }}</h4>
                            </div><!--end card-header-->
                            <div class="card-body">
                                <p class="card-text text-muted">{{ $subcategory->sub_description }}</p>
                            </div><!--end card-body-->
                        </div><!--end card-->
                    </div><!--end col-->
                @endforeach
            </div><!--end row-->

            @php
                $colorIndex = ($colorIndex + 1) % count($colors); // Cycle through colors
            @endphp
        @endforeach
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
        Swal.fire({
            title: 'Are you sure?',
            text: 'This action will delete the category and associated subcategories.',
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
