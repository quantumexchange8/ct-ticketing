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
                                <h4 class="page-title mt-2">Enhancement</h4>
                            </div><!--end col-->
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
                                <div style="display: flex; justify-content: flex-end;">
                                    <a href="{{ route('enhancementSumm', ['project' => $project->id]) }}" class="btn btn-primary btn-sm">Go somewhere</a>
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
@endsection
