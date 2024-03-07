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
                            <h4 class="page-title">Create Subcategory</h4>
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('addSub', ['supportCategory' => $supportCategory]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="sub_name">Name</label>
                                        <input type="text" class="form-control" name="sub_name" placeholder="Enter Name" autocomplete="off" value="{{ old('sub_name') }}">
                                        @error('sub_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="content_id">Category</label>
                                        <select class="form-control" name="category_id">
                                            @foreach ($supportCategories as $category)
                                                <option value="{{ $category->id }}" {{ $category->id == $supportCategory->id ? 'selected' : '' }}>
                                                    {{ $category->category_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="content_id">Project</label>
                                        <select class="form-control" name="project_id">
                                            @foreach ($projects as $projectSelected)
                                                <option value="{{ $projectSelected->id }}" {{ $projectSelected->id == $project->id ? 'selected' : '' }}>
                                                    {{ $projectSelected->project_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Description</label>
                                        <textarea type="text" class="form-control" name="sub_description" placeholder="Enter Description" rows="9" autocomplete="off">{{ old('sub_description') }}</textarea>
                                        @error('sub_description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 text-right">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div><!--end card-body-->
                </div><!--end card-->
            </div><!--end col-->
        </div><!--end row-->
    </div>
</div>
<!-- end page content -->

@endsection
