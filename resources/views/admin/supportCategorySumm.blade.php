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
                            <h4 class="page-title">Support Tool</h4>
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
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($supportCategories as $supportCategory)
                                    <tr>
                                        <td>{!! $supportCategory->category_name !!}</td>
                                        <td class="text-center">
                                            <div style="display: flex; justify-content: center; gap: 10px;">
                                                @if (Auth::user()->manage_support_subcategory == 1)
                                                    <a href="{{ route('supportSubSumm', ['supportCategory' => $supportCategory->id]) }}" class="btn btn-sm btn-soft-purple btn-circle">
                                                        <i class="dripicons-preview"></i>
                                                    </a>
                                                @endif

                                                @if (Auth::user()->manage_support_category == 1)
                                                    <a href="{{ route('editCategory', ['id' => $supportCategory->id]) }}" class="btn btn-sm btn-soft-success btn-circle">
                                                        <i class="dripicons-pencil"></i>
                                                    </a>
                                                @endif

                                                @if (Auth::user()->manage_support_category == 1)
                                                    <form action="{{ route('deleteCategory', ['id' => $supportCategory->id]) }}" method="POST" id="deleteForm{{ $supportCategory->id }}" data-category-id="{{ $supportCategory->id }}">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="button" class="btn btn-sm btn-soft-danger btn-circle" onclick="confirmDelete('deleteForm{{ $supportCategory->id }}')">
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
                            {{-- <button id="but_add" class="btn btn-danger">Add New Category</button>
                            <button class="btn  btn-primary" id="submit_data" data-endpoint="update-category" >Submit</button> --}}
                            <a href="{{ route('createCategory') }}">
                                <button class="btn btn-danger mt-2">Add New Category</button>
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
                timer: 1000,
                showConfirmButton: false,
            });
        @endif
    });
</script>

<script>
    function confirmDelete(formId) {
        var categoryId = document.getElementById(formId).getAttribute('data-category-id');

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
