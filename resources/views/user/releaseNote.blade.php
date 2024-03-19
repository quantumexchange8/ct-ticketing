@extends('layouts.masterMember')
@section('content')

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Support start -->
<section class="section-sm" id="Support">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    {{-- <div class="page-title-box"> --}}
                        <div class="row" style="padding:10px;">
                            <div class="col">
                                <h4 class="page-title mt-2">Release Notes</h4>
                            </div><!--end col-->
                        </div><!--end row-->
                    {{-- </div><!--end page-title-box--> --}}
                </div><!--end col-->
            </div><!--end row-->
            @php
                $colors = ['#bed3fe', '#e3e6f0', '#b8f4db', '#bde6fa', '#ffebc1', '#99a1b7', '#b2bfc2'];
                $colorIndex = 0;
            @endphp

            @foreach ($groupedTicketLogs as $date => $ticketLogs)
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header" style="background-color: {{ $colors[$colorIndex] }}">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <h4 class="card-title">{{ $date }} - {{ $versionNumber ?? null}}</h4>
                                </div>
                            </div><!--end card-header-->
                        </div><!--end card-->
                    </div><!--end col-->
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Ticket No.</th>
                                                <th>Subject</th>
                                                <th>Message</th>
                                                <th>Category</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($ticketLogs as $ticketLog)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>{{ $ticketLog->ticket_no }}</td>
                                                    <td>{{ $ticketLog->tickets->subject }}</td>
                                                    <td>{{ $ticketLog->tickets->message }}</td>
                                                    <td>{{ $ticketLog->tickets->supportCategories->category_name }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table><!--end /table-->
                                </div><!--end /tableresponsive-->
                            </div><!--end card-body-->
                        </div><!--end card-->
                    </div> <!-- end col -->
                </div> <!-- end row -->

                <!-- Display enhancements for this date -->
                @if ($groupedEnhancements->has($date))
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Enhancement Title</th>
                                                    <th>Description</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($groupedEnhancements[$date] as $enhancement)
                                                    <tr>
                                                        <td>{{ $loop->index + 1 }}</td>
                                                        <td>{{ $enhancement->enhancement_title }}</td>
                                                        <td>{{ $enhancement->enhancement_description }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table><!--end /table-->
                                    </div><!--end /tableresponsive-->
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                @endif

                @php
                    $colorIndex = ($colorIndex + 1) % count($colors); // Cycle through colors
                @endphp
            @endforeach

        </div>
    </div>
    <!-- end page content -->
</section>
<!-- Support end -->
@endsection
