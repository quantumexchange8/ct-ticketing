@extends('layouts.masterAdmin')
@section('content')

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Support start -->
<section class="section-sm">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    {{-- <div class="page-title-box"> --}}
                        <div class="row" style="padding:10px;">
                            <div class="col">
                                <h4 class="page-title mt-2">Invoice</h4>
                            </div><!--end col-->
                            {{-- <div class="col-auto align-self-center">
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
                            </div><!--end col--> --}}
                        </div><!--end row-->
                    {{-- </div><!--end page-title-box--> --}}
                </div><!--end col-->
            </div><!--end row-->

            <div class="row">
                @foreach ($projects as $project)
                    <div class="col-sm-3">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ $project->project_name }}</h4>
                            </div><!--end card-header-->
                            <div class="card-body">
                                {{-- <div style="display: flex; justify-content: center; gap:10px;">
                                    <a href="#" class="btn btn-primary btn-m">Invoice</a>
                                    <a href="{{ route('invoiceSumm', ['project' => $project->id]) }}" class="btn btn-primary btn-m">Order List</a>
                                </div> --}}
                                <div style="display: flex; justify-content: center; gap:10px;">
                                    <a href="{{ route('orderItemSumm', ['project' => $project->id]) }}" class="btn btn-primary btn-m">Order List</a>
                                    <a href="{{ route('invoiceSumm', ['project' => $project->id]) }}" class="btn btn-primary btn-m">Invoice List</a>
                                </div>
                            </div><!--end card-body-->
                        </div><!--end card-->
                    </div><!--end col-->
                @endforeach
            </div><!--end row-->

        </div>
    </div>
    <!-- end page content -->
</section>
<!-- Support end -->

<script>
    $(document).ready(function () {
        $('#searchInput').on('input', function (e) {
            e.preventDefault();
            var searchTerm = $(this).val().trim();

            $.ajax({
                url: '/search-support-tools',
                type: 'GET',
                data: { searchTerm: searchTerm },
                success: function (response) {
                    // console.log('Search Term:', searchTerm);
                    // $('#searchResults').html(response);

                    var matchedSubCategoryIds = response.matchedSubCategoryIds;
                    var unmatchedSubCategoryIds = response.unmatchedSubCategoryIds;
                    var allSubCategoryIds = response.allSubCategoryIds;

                    // console.log('Matched Sub-Category IDs:', matchedSubCategoryIds);
                    // console.log('Unmatched Sub-Category IDs:', unmatchedSubCategoryIds);

                    // Remove background color for all card headers
                    $('[id^=subcategory_]').css('background-color', '');

                    if (matchedSubCategoryIds.length > 0) {
                        matchedSubCategoryIds.forEach(function (subCategoryId) {
                            var cardHeader = $('#' + subCategoryId);

                            if (cardHeader.length > 0) {
                                // if (cardHeader.css('background-color') !== 'rgba(0, 0, 0, 0)') {
                                // }

                                cardHeader.css('background-color', '#cFFCAB1');
                            }
                        });
                    }

                    if (unmatchedSubCategoryIds.length > 0) {
                        unmatchedSubCategoryIds.forEach(function (subCategoryId) {
                            var cardHeader = $('#' + subCategoryId);

                            if (cardHeader.length > 0) {
                                // if (cardHeader.css('background-color') !== 'rgba(0, 0, 0, 0)') {
                                // }

                                cardHeader.css('background-color', 'white');
                            }
                        });
                    }

                    if (searchTerm.trim() === '') {
                        allSubCategoryIds.forEach(function (subCategoryId) {
                            var cardHeader = $('#' + subCategoryId);

                            if (cardHeader.length > 0) {
                                // if (cardHeader.css('background-color') !== 'rgba(0, 0, 0, 0)') {
                                // }

                                cardHeader.css('background-color', 'white');
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

@endsection
