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
                            <h4 class="page-title">Create Sub-Category</h4>
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
                                {{-- @if($errors->any())
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif --}}

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="title-name">Name</label>
                                        <input type="text" class="form-control" name="sub_name" placeholder="Enter Name" value="{{ old('sub_name') }}">
                                        @error('sub_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Description</label>
                                        <input type="text" class="form-control" name="sub_description" placeholder="Enter Description" value="{{ old('sub_description') }}">
                                        @error('sub_description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="title-name">Related Topic</label>
                                        <select class="form-control" name="content_id">
                                            <option>Select Related Topic</option>
                                            @foreach ($contents as $content)
                                                <option value="{{ $content->id }}" {{ old('content_id') == $content->id ? 'selected' : '' }}>
                                                    {{ $content->title->title_name }} - {{ $content->subtitle_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('content_id')
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
