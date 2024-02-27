@extends('layouts.masterAdmin')
@section('content')

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="row">
                        <div class="col-10">
                            <h4 class="page-title mt-2">Performance</h4>
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        @php
            $colors = ['#bed3fe', '#e3e6f0', '#b8f4db', '#bde6fa', '#ffebc1', '#99a1b7', '#b2bfc2'];
            $colorIndex = 0;
        @endphp

        <div>
            @foreach ($supportCategories as $supportCategory)
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card" >
                            <div class="card-header" style="background-color: {{ $colors[$colorIndex] }}">
                            {{-- <div class="card-header"> --}}
                                <h4 class="card-title">{!! $supportCategory->category_name !!}</h4>
                            </div><!--end card-header-->
                        </div><!--end card-->
                    </div><!--end col-->
                </div>

                <div class="row">

                    @foreach ($supportCategory->users as $user)
                        <div class="col-sm-3">
                            <div class="card">
                                <a href="{{ route('viewPerformance', ['id' => $user->id]) }}">
                                    <div class="card-header">
                                        <h4 class="card-title">{{ $user->name }}</h4>
                                        {{-- <h4 class="card-title">Total Tickets: {{ $user->tickets_count }}</h4> --}}
                                        <div style="display: flex; align-items: center;">
                                            <h4 class="card-title" style="margin-right: 5px;">Total Tickets</h4>
                                            <span>:</span>
                                            <h4 class="card-title" style="margin-left: 5px;">{{ $user->tickets_count }}</h4>
                                        </div>
                                    </div><!--end card-header-->
                                    <div class="card-body">
                                        @foreach ($ticketStatuses as $status)
                                            <div style="display: flex; align-items: center;">
                                                <h6 style="margin-right: 5px;">{{ $status->status }}</h6>
                                                <span>:</span>
                                                <h6 style="margin-left: 5px;">{{ $user->tickets()->where('status_id', $status->id)->count() }}</h6>
                                            </div>
                                        @endforeach
                                    </div><!--end card-body-->
                                </a>
                            </div><!--end card-->
                        </div><!--end col-->
                    @endforeach
                </div><!--end row-->

                @php
                    $colorIndex = ($colorIndex + 1) % count($colors); // Cycle through colors
                @endphp
            @endforeach
        </div>

    </div>
</div>
<!-- end page content -->


@endsection
