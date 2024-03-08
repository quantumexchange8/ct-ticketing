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
                            <h4 class="page-title mt-2">Support Category</h4>
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
                        <h4 class="card-title">{{ $supportCategory->category_name }}</h4>
                    </div><!--end card-header--> --}}
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
                                    @foreach ($supportCategories as $category)
                                    <tr>
                                        <td>{!! $category->category_name !!}</td>
                                        <td class="text-center">
                                            <div style="display: flex; justify-content: center; gap: 10px;">
                                                @if (Auth::user()->manage_support_category == 1)
                                                    {{-- <a href="{{ route('editCategory', ['id' => $category->id]) }}" class="btn btn-sm btn-soft-success btn-circle"> --}}
                                                        <button class="btn btn-sm btn-soft-success btn-circle edit-category" data-category-id="{{ $category->id }}">
                                                            <i class="dripicons-pencil"></i>
                                                        </button>
                                                    {{-- </a> --}}
                                                @endif

                                                @if (Auth::user()->manage_support_category == 1)
                                                    <form action="{{ route('deleteCategory', ['id' => $category->id]) }}" method="POST" id="deleteForm{{ $category->id }}" data-category-id="{{ $category->id }}">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="button" class="btn btn-sm btn-soft-danger btn-circle" onclick="confirmDelete('deleteForm{{ $category->id }}')">
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
                            {{-- <a href="{{ route('createCategory') }}"> --}}
                                <button class="btn btn-danger mt-2" id="addCategory">Add New Category</button>
                            {{-- </a> --}}
                        </span>
                    </div><!--end card-body-->
                </div><!--end card-->
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div><!-- container -->
</div>
<!-- end page content -->

{{-- Add Category --}}
<div id="addCategoryModal" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModalLabel">Create New Category</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('addCategory') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="title-name">Category</label>
                            <input type="text" class="form-control" name="category_name" placeholder="Enter Category Name" autocomplete="off" value="{{ old('category_name') }}">
                            @error('category_name')
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

{{-- Edit Category --}}
<div id="editCategoryModal" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModalLabel">Edit Category</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{ isset($category) ? route('updateCategory', $category->id) : '#' }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="category_name">Category</label>
                            <input type="text" class="form-control" name="category_name" id="category_name" placeholder="Enter Category Name" autocomplete="off" value="{{ isset($category) ? $category->category_name : '' }}">
                            @error('category_name')
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
        var addCategoryModal = document.getElementById("addCategoryModal");

        // Get the export button
        var addCategory = document.getElementById("addCategory");

        // When the user clicks the export button, display the modal
        addCategory.addEventListener("click", function() {
            $('#addCategoryModal').modal('show');
        });

        // When the user clicks anywhere outside of the modal, close it
        $(document).on('click', function(event) {
            if ($(event.target).closest('.modal').length === 0) {
                $('#addCategoryModal').modal('hide');
            }
        });

        // When the user clicks the edit button, fetch the title data and display it in the modal
        $('.edit-category').click(function() {
            var categoryId = $(this).data('category-id');

            // Fetch the title data via AJAX
            $.ajax({
                url: '/edit-category/' + categoryId,
                type: 'GET',
                data: { id: categoryId },
                success: function(response) {
                    // Update the modal content with the fetched title data
                    $('#category_name').val(response.category.category_name);

                    // Show the modal
                    $('#editCategoryModal').modal('show');
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

