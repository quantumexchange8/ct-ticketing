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
                            <h4 class="page-title">Edit Content</h4>
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('updateContent', $content->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="c_sequence">Content Sequence</label>
                                        <input type="number" class="form-control" name="c_sequence" placeholder="Enter Sequence" value="{{ $content->c_sequence }}">
                                        @error('c_sequence')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="subtitle_id">Subtitle</label>
                                        <select class="form-control" name="subtitle_id">
                                            @foreach ($subtitles as $subtitle)
                                                <option value="{{ $subtitle->id }}" {{ (int)$content->subtitle_id === $subtitle->id ? 'selected' : '' }}>
                                                    {{ $subtitle->title->title_name}} - {{ $subtitle->subtitle_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('subtitle_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="content_name">Content</label>
                                        <textarea id="elm1" name="content_name">{!! $content->content_name !!}</textarea>
                                        @error('content_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card-body text-center">
                                        @if($content->documentationImages->isNotEmpty())
                                            <input type="file" id="input-file-now" name="d_image" class="dropify" data-default-file="{{ asset('storage/documentations/' . $content->documentationImages[0]->image_name) }}" />
                                        @else
                                            <input type="file" id="input-file-now" name="d_image" class="dropify"/>
                                        @endif
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

@endsection
