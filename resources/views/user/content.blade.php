@extends('layouts.masterMember')
@section('content')

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Page Content-->
<div class="page-content">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="row">
                    <div class="col" style="display: flex; justify-content: flex-end; align-items: flex-end;">
                        <button type="button" class="btn" id="exportButton">
                            <i data-feather="download"></i>
                        </button>
                    </div>
                </div><!--end row-->
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div><!--end row-->
    @foreach ($titles as $title)
        <section class="bg-home">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">{{ $title->title_name }}</h4>
                            <hr class="hr-dashed">
                            @foreach ($title->subtitles as $subtitle)
                                <h5 class="subtitle-name">{{ $subtitle->subtitle_name }}</h5>
                                @foreach ($subtitle->contents as $content)
                                    <div class="content-name" id="{{ $content->id }}">
                                        <p>{!! $content->content_name !!}</p>
                                    </div>
                                    <div class="text-center">
                                        <b>No image</b>
                                    </div>
                                    <br>
                                @endforeach
                            @endforeach
                        </div><!--end card-body-->
                    </div><!--end card-->
                </div><!--end col-->
            </div><!--end row-->
        </section>
    @endforeach

</div>
<!-- end page content -->


@endsection



