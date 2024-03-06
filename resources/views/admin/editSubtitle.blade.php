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
                            <h4 class="page-title">{{ $project->project_name}} -> {{ $title->title_name }}</h4>
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Subtitle</h4>
                    </div><!--end card-header-->
                    <div class="card-body">
                        <form action="{{ route('updateSubtitle', $subtitle->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="s_sequence">Subtitle Sequence</label>
                                        <input type="number" class="form-control" name="s_sequence" placeholder="Enter Subtitle Sequence" autocomplete="off" value="{{ $subtitle->s_sequence }}">
                                        @error('s_sequence')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="subtitle_name">Subtitle</label>
                                        <input type="text" class="form-control" name="subtitle_name" placeholder="Enter Title" autocomplete="off" value="{{ $subtitle->subtitle_name }}">
                                        @error('subtitle_name')
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
