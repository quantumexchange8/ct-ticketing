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
                            <h4 class="page-title">FAQ</h4>
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
                        <h4 class="card-title">{!! $supportSubCategories->first()->supportCategories->category_name !!}</h4>
                    </div><!--end card-header-->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="display: none;">Category ID</th>
                                        <th style="display: none;">Subcategory ID</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($supportSubCategories as $supportSubCategory)
                                        <tr>
                                            <td style="display: none;">{{ $supportSubCategory->category_id }}</td>
                                            <td style="display: none;">{{ $supportSubCategory->id }}</td>
                                            <td>{{ $supportSubCategory->sub_name }}</td>
                                            <td>{!! $supportSubCategory->sub_description !!}</td>
                                            <td class="text-center">
                                                <div style="display: flex; justify-content: center; gap: 10px;">
                                                    <a href="{{ route('editSub', ['id' => $supportSubCategory->id]) }}" class="btn btn-sm btn-soft-success btn-circle">
                                                        <i class="dripicons-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('deleteSub', ['id' => $supportSubCategory->id]) }}" method="POST" id="deleteForm{{ $supportSubCategory->id }}" data-subcategory-id="{{ $supportSubCategory->id }}">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="button" class="btn btn-sm btn-soft-danger btn-circle" onclick="confirmDelete('deleteForm{{ $supportSubCategory->id }}')">
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
                            <a href="{{route('createSub', ['supportCategory' => $supportSubCategories->first()->supportCategories, 'project' => $project])}}">
                                <button class="btn btn-danger mt-2">Add New Content</button>
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
        var subcategoryId = document.getElementById(formId).getAttribute('data-subcategory-id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This action will delete the subcategory.',
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

