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
                            <h4 class="page-title">Create Content</h4>
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('addContent') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <div class="radio radio-purple radio-circle">
                                            <input type="radio" id="existing" name="subtitle_type" value="existing">
                                            <label for="existing">
                                                Existing Subtitle
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <div class="radio radio-purple radio-circle">
                                            <input type="radio" id="new" name="subtitle_type" value="new">
                                            <label for="new">
                                                New Subtitle
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                {{-- @if($errors->any())
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif --}}

                                <div class="col-lg-6" >
                                    <div class="form-group" id="existingSubtitle" style="display: none;">
                                        <label for="exampleInputPassword1">Existing Subtitle</label>
                                        <select class="form-control" name="subtitle_id">
                                            <option value="">Select Subtitle</option>
                                            @foreach ($subtitles as $subtitle)
                                                <option value="{{ $subtitle->id }}" {{ old('subtitle_id') == $subtitle->id ? 'selected' : '' }}>{{ $subtitle->title->title_name}} - {{ $subtitle->subtitle_name }} </option>
                                            @endforeach
                                        </select>
                                        @error('subtitle_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group" id="titleId" style="display: none;">
                                        <label for="exampleInputPassword1">Title</label>
                                        <select class="form-control" name="title_id">
                                            <option value="">Select Title</option>
                                            @foreach ($titles as $title)
                                                <option value="{{ $title->id }}" {{ old('title_id') == $title->id ? 'selected' : '' }}>{{ $title->title_name}}</option>
                                            @endforeach
                                        </select>
                                        @error('title_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group" id="newSubtitle" style="display: none;">
                                        <label for="exampleInputPassword1">New Subtitle</label>
                                        <input type="text" class="form-control" name="subtitle_name" placeholder="Enter Subtitle" autocomplete="off" value="{{ old('subtitle_name') }}">
                                        @error('subtitle_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1">Content</label>
                                        {{-- <textarea class="form-control" rows="5" name="content" value="{{ old('content') }}"></textarea> --}}
                                        <textarea id="elm1" name="content_name" autocomplete="off"></textarea>
                                        @error('content_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card-body">
                                        <input type="file" id="input-file-now" name="d_image" class="dropify" />
                                    </div><!--end card-body-->
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

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {

        $('input[type="radio"][name="subtitle_type"]').change(function() {
            if ($(this).val() === 'existing') {
                $('#existingSubtitle').show();
                $('#newSubtitle').hide();
                $('#titleId').hide();
            } else if ($(this).val() === 'new') {
                $('#existingSubtitle').hide();
                $('#newSubtitle').show();
                $('#titleId').show();
            }
        });

        $('input[type="radio"][name="subtitle_type"]:checked').trigger('change');

    });
</script>

@endsection
