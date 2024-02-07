@extends('layouts.masterMember')
@section('content')

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

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

                        <div class="row">
                            <div class="col">
                                <h4 class="header-title">{{ $title->title_name }}</h4>
                            </div>
                            <div class="col-auto align-self-center">
                                <a class="nav-link dropdown-toggle arrow-none waves-light waves-effect" data-toggle="dropdown" href="#" role="button"
                                    aria-haspopup="false" aria-expanded="false">
                                    <i data-feather="search" class="topbar-icon"></i>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right dropdown-lg p-0">
                                    <!-- Top Search Bar -->
                                    <div class="app-search-topbar">
                                        <div>
                                            <input type="search" name="search" id="searchInput" class="from-control top-search mb-0" autocomplete="off" placeholder="Type text...">
                                            <button id="search-button" type="button"><i class="ti-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div><!--end col-->
                        </div>

                        <hr class="hr-dashed">

                        @foreach ($title->subtitles as $subtitle)
                            <h5 class="subtitle-name">{{ $subtitle->subtitle_name }}</h5>
                            @foreach ($subtitle->contents as $content)
                                <div class="content-name" id="{{$content->id}}">
                                    <p>{!! $content->content_name !!}</p>
                                </div>

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

<script>
    $(document).ready(function () {

        $('#searchInput').on('input', function (e) {
            e.preventDefault();
            var searchTerm = $(this).val().trim();
            var titleId = {{ $title->id }};

            $.ajax({
                url: '/search-documentation',
                type: 'GET',
                data: {
                    searchTerm: searchTerm,
                    titleId: titleId,
                },
                success: function (response) {
                    console.log('Search Term:', searchTerm);

                    var matchedContentIds = response.matchedContentIds;
                    var unmatchedContentIds = response.unmatchedContentIds;
                    var allContentIds = response.allContentIds;

                    console.log('Matched IDs:', matchedContentIds);
                    console.log('Unmatched IDs:', unmatchedContentIds);

                    // var matchedSubtitleIds = Object.keys(matchedContentIds);
                    // var allSubtitleIds = Object.keys(allContentIds);

                    // if (matchedSubtitleIds.length > 0) {
                    //     matchedSubtitleIds.forEach(function (subtitleId) {
                    //         var subtitleName = $('#' + subtitleId);

                    //         if (subtitleName.length > 0) {
                    //             subtitleName.css('background-color', '#cFFCAB1');
                    //         }
                    //     });
                    // }

                    // if (searchTerm.trim() === '') {
                    //     allSubtitleIds.forEach(function (subtitleId) {
                    //         var subtitleName = $('#' + subtitleId);

                    //         if (subtitleName.length > 0) {
                    //             subtitleName.css('background-color', 'white');
                    //         }
                    //     });
                    // }

                    if (matchedContentIds.length > 0) {
                        matchedContentIds.forEach(function (contentId) {
                            var contentName = $('#' + contentId);

                            if (contentName.length > 0) {
                                contentName.css('background-color', '#cFFCAB1');
                            }
                        });
                    }

                    if (unmatchedContentIds.length > 0) {
                        unmatchedContentIds.forEach(function (contentId) {
                            var contentName = $('#' + contentId);

                            if (contentName.length > 0) {
                                contentName.css('background-color', 'white');
                            }
                        });
                    }

                    if (searchTerm.trim() === '') {
                        allContentIds.forEach(function (contentId) {
                            var contentName = $('#' + contentId);

                            if (contentName.length > 0) {
                                contentName.css('background-color', 'white');
                            }
                        });
                    }
                },
                error: function (error) {
                    console.error('Error:', error);
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
                timer: 1000, // 3000 milliseconds (3 seconds)
                showConfirmButton: false, // Hide the "OK" button
            });
        @endif
    });
</script>
@endsection



