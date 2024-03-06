@extends('layouts.masterMember')
@section('content')

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


<!-- Page Content-->
<div class="page-content">
    <section class="bg-home" >
        <div class="row">
            <div class="col-lg-12">
                <div class="card my-4">
                    <div class="card-body">
                        <div class="button-items">
                            @php
                                $colors = ['#bed3fe', '#e3e6f0', '#b8f4db', '#bde6fa', '#ffebc1', '#99a1b7', '#b2bfc2'];
                                $colorIndex = 0;
                            @endphp

                            @foreach ($project->titles as $title)
                                @foreach ($title->subtitles as $subtitle)
                                    <a href="#{{ $subtitle->subtitle_name }}" >
                                        <button type="button" class="btn btn-round waves-effect waves-light" style="background-color: {{ $colors[$colorIndex] }}">{{ $subtitle->subtitle_name }}</button>
                                    </a>
                                    @php
                                        $colorIndex = ($colorIndex + 1) % count($colors); // Cycle through colors
                                    @endphp
                                @endforeach
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
                                <h4 class="header-title">{{ $title->title_name ?? null}}</h4>
                            </div>

                            <div style="display: flex; justify-content: flex-end;">
                                <div>
                                    <button type="button" class="btn" id="exportButton">
                                        <i data-feather="download"></i>
                                    </button>
                                </div>

                                <div>
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
                            <!-- Your buttons and dropdowns -->
                        </div>

                        <hr class="hr-dashed">

                        @foreach ($project->titles as $title)
                            @foreach ($title->subtitles as $subtitle)
                            <h5 class="subtitle-name">{{ $subtitle->subtitle_name }}</h5>
                                @foreach ($subtitle->contents as $content)
                                    <div class="content-name" id="{{$content->id}}" style="text-align: justify; ">
                                        <p>{!! $content->content_name !!}</p>
                                    </div>

                                    <div class="text-center">
                                        @forelse($content->documentationImages as $image)
                                            <img src="{{ asset('storage/documentations/' . $image->d_image) }}" alt="Image" class="image-fluid w-50 h-50">
                                        @empty
                                            {{-- No image --}}
                                        @endforelse
                                    </div>

                                    <br>
                                @endforeach
                            @endforeach
                        @endforeach
                    </div><!--end card-body-->
                </div><!--end card-->
            </div><!--end col-->
        </div><!--end row-->
    </section>
</div>
<!-- end page content -->

{{-- Content to print --}}
<div id="allContentToPrint" class="page-content" style="display: none;">
    @foreach ($allContents as $project)
        <section class="bg-home" style="page-break-before: always;">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">{{ $project->title_name }}</h4>
                            <hr class="hr-dashed">
                            @foreach ($project->subtitles as $subtitle)
                                <h5 class="subtitle-name">{{ $subtitle->subtitle_name }}</h5>
                                @foreach ($subtitle->contents as $content)
                                    <div class="content-name" id="{{ $content->id }}" style="text-align: justify;">
                                        <p>{!! $content->content_name !!}</p>
                                    </div>
                                    <div class="text-center">
                                        @forelse($content->documentationImages as $image)
                                        <img src="{{ asset('storage/documentations/' . $image->d_image) }}" alt="Image" class="image-fluid" width="250" height="250" style="display: block; margin: 0 auto;">
                                        @empty

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
    @endforeach
</div>


<div id="contentToPrint" class="card-body" style="display: none;">
    <div class="row">
        <div class="col">
            <h4 class="header-title">{{ $title->title_name }}</h4>
        </div>
    </div>

    <hr class="hr-dashed">

    @foreach ($singleProject->titles as $title)
        @foreach ($title->subtitles as $subtitle)
        <h5 class="subtitle-name">{{ $subtitle->subtitle_name }}</h5>
            @foreach ($subtitle->contents as $content)
                <div class="content-name" id="{{$content->id}}" style="text-align: justify; ">
                    <p>{!! $content->content_name !!}</p>
                </div>

                <div class="text-center">
                    @forelse($content->documentationImages as $image)
                        <img src="{{ asset('storage/documentations/' . $image->d_image) }}" alt="Image" class="image-fluid w-50 h-50">
                    @empty
                        {{-- No image --}}
                    @endforelse
                </div>

                <br>
            @endforeach
        @endforeach
    @endforeach
</div><!--end card-body-->


<!-- Modal -->
<div id="myModal" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg" role="document" style="width: 400px; height: 200px; margin-top: 200px;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModalLabel">Choose Content to Print</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="display: flex; justify-content: center; gap: 20px;">
            <button id="printContent" type="button" class="btn btn-soft-primary waves-effect waves-light">Current Content</button>
            <button id="printAllContent" type="button" class="btn btn-soft-info waves-effect waves-light">All Content</button>
        </div>
      </div>
    </div>
</div>

{{-- Print --}}
<script>
    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the export button
    var exportButton = document.getElementById("exportButton");

    // When the user clicks the export button, display the modal
    exportButton.addEventListener("click", function() {
        $('#myModal').modal('show');
    });

    // When the user clicks anywhere outside of the modal, close it
    $(document).on('click', function(event) {
        if ($(event.target).closest('.modal').length === 0) {
            $('#myModal').modal('hide');
        }
    });

    // Print contentToPrint or allContentToPrint based on user selection
    document.getElementById("printContent").addEventListener("click", function() {
        PrintElem("contentToPrint");
        $('#myModal').modal('hide');
    });

    document.getElementById("printAllContent").addEventListener("click", function() {
        PrintElem("allContentToPrint");
        $('#myModal').modal('hide');
    });


    function PrintElem(elem) {
        var mywindow = window.open('', 'PRINT', 'height=400,width=600');
        mywindow.document.write('<html><head><title>' + document.title + '</title>');
        mywindow.document.write('</head><body>');
        mywindow.document.write(document.getElementById(elem).innerHTML);
        mywindow.document.write('</body></html>');

        // Wait for images to load before printing
        var images = mywindow.document.getElementsByTagName('img');
        if (images.length === 0) { // If no images, proceed to print
            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10
            mywindow.print();
            mywindow.close();
            return;
        }

        var loaded = 0;
        for (var i = 0; i < images.length; i++) {
            images[i].onload = function() {
                loaded++;
                if (loaded === images.length) {
                    mywindow.document.close(); // necessary for IE >= 10
                    mywindow.focus(); // necessary for IE >= 10
                    mywindow.print();
                    mywindow.close();
                }
            };
        }
        return true;
    }
</script>


{{-- Search --}}
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
                    // console.log('Search Term:', searchTerm);

                    var matchedContentIds = response.matchedContentIds;
                    var unmatchedContentIds = response.unmatchedContentIds;
                    var allContentIds = response.allContentIds;

                    // console.log('Matched IDs:', matchedContentIds);
                    // console.log('Unmatched IDs:', unmatchedContentIds);

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



