@extends('layouts.masterAdmin')
@section('content')

<!-- Page Content-->
<div class="page-content">
    <section class="bg-home" id="{{ $title->title_name }}">

        <div class="row">
            <div class="col-lg-12">
                <div class="card my-4">
                    <div class="card-body">
                        <div class="button-items">
                            @php
                                $colors = ['#bed3fe', '#e3e6f0', '#b8f4db', '#bde6fa', '#ffebc1', '#99a1b7', '#b2bfc2'];
                                $colorIndex = 0;
                            @endphp

                            @foreach ($title->subtitles as $subtitle)
                                <a href="#{{ $subtitle->subtitle_name }}" >
                                    <button type="button" class="btn btn-round waves-effect waves-light" style="background-color: {{ $colors[$colorIndex] }}">{{ $subtitle->subtitle_name }}</button>
                                </a>
                                @php
                                    $colorIndex = ($colorIndex + 1) % count($colors); // Cycle through colors
                                @endphp
                            @endforeach
                        </div>
                    </div><!--end card-body-->
                </div><!--end card-->
            </div><!--end col-->
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="header-title">{{ $title->title_name }}</h4>
                        <hr class="hr-dashed">

                        @foreach ($title->subtitles as $subtitle)
                            <h5 class="subtitle-name" id="{{ $subtitle->subtitle_name }}">{{ $subtitle->subtitle_name }}</h5>
                            @foreach ($subtitle->contents as $content)
                                <p>{!! $content->content_name !!}</p>

                                <div class="text-center">
                                    @forelse($content->documentationImages as $image)
                                    <img src="{{ asset('storage/documentations/' . $image->d_image) }}" alt="Image" class="image-fluid w-50 h-50">
                                    @empty
                                    {{-- <b>No image</b> --}}
                                    @endforelse
                                </div>

                                <br>
                            @endforeach
                        @endforeach
                    </div><!--end card-body-->
                </div><!--end card-->
            </div><!--end col-->
        </div><!--end row-->

    </section>
</div>
<!-- end page content -->


@endsection

