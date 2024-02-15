@extends('layouts.masterAdmin')
@section('content')

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

{{-- <!-- Page Content-->
<div class="page-content">
    <div class="container-fluid">
        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="row">
                        <div class="col">
                            <h4 class="page-title">Kanban Board</h4>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Dastyle</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Advanced UI</a></li>
                                <li class="breadcrumb-item active">Kanban Board</li>
                            </ol>
                        </div><!--end col-->
                        <div class="col-auto align-self-center">
                            <a href="#" class="btn btn-sm btn-outline-primary" id="Dash_Date">
                                <span class="day-name" id="Day_Name">Today:</span>&nbsp;
                                <span class="" id="Select_date">Jan 11</span>
                                <i data-feather="calendar" class="align-self-center icon-xs ml-1"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                <i data-feather="download" class="align-self-center icon-xs"></i>
                            </a>
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <!-- end page title end breadcrumb -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="kanban-board">
                            <div class="kanban-col">
                                <div class="kanban-main-card">
                                    <div class="kanban-box-title">
                                        <h4 class="card-title mt-0 mb-3">To Do</h4>
                                    </div>

                                    <div id="project-list-left" class="pb-1">
                                        <div class="card">
                                            <div class="card-body">
                                                <i class="mdi mdi-circle-outline d-block mt-n2 font-18 text-warning"></i>
                                                <h5 class="my-1 font-14">Mobile Account Setting</h5>
                                                <p class="text-muted mb-2">Mobile App</p>
                                            </div><!--end card-body-->
                                        </div><!--end card-->

                                        <div class="card">
                                            <div class="card-body">
                                                <i class="mdi mdi-circle-outline d-block mt-n2 font-18 text-success"></i>
                                                <h5 class="my-1">Mobile Account Setting</h5>
                                                <p class="text-muted mb-2">Mobile App</p>
                                            </div><!--end card-body-->
                                        </div><!--end card-->


                                    </div><!--end project-list-left-->
                                    <button type="button" class="btn btn-block btn-soft-primary btn-sm">Add Task</button>
                                </div><!--end /div-->
                            </div><!--end kanban-col-->

                            <div class="kanban-col">
                                <div class="kanban-main-card">
                                    <div class="kanban-box-title">
                                        <h4 class="card-title mt-0 mb-3">In Progress</h4>
                                    </div>
                                    <div id="project-list-center-left" class="pb-1">
                                        <div class="card">
                                            <div class="card-body">
                                                <i class="mdi mdi-circle-outline d-block mt-n2 font-18 text-warning"></i>
                                                <h5 class="my-1">Mobile Account Setting</h5>
                                                <p class="text-muted mb-2">Mobile App</p>
                                            </div><!--end card-body-->
                                        </div><!--end card-->

                                        <div class="card">
                                            <div class="card-body">
                                                <i class="mdi mdi-circle-outline d-block mt-n2 font-18 text-success"></i>
                                                <h5 class="my-1">Mobile Account Setting</h5>
                                                <p class="text-muted mb-2">Mobile App</p>
                                            </div><!--end card-body-->
                                        </div><!--end card-->

                                    </div><!--end project-list-left-->
                                    <button type="button" class="btn btn-block  btn-soft-primary btn-sm">Add Task</button>
                                </div><!--end /div-->
                            </div><!--end kanban-col-->

                            <div class="kanban-col">
                                <div class="kanban-main-card">
                                    <div class="kanban-box-title">
                                        <h4 class="card-title mt-0 mb-3">Code Review</h4>
                                    </div>

                                    <div id="project-list-center-right" class="pb-1">
                                        <div class="card">
                                            <div class="card-body">
                                                <i class="mdi mdi-circle-outline d-block mt-n2 font-18 text-warning"></i>
                                                <h5 class="my-1">Mobile Account Setting</h5>
                                                <p class="text-muted mb-2">Mobile App</p>
                                            </div><!--end card-body-->
                                        </div><!--end card-->


                                    </div><!--end project-list-right-->
                                    <button type="button" class="btn btn-block  btn-soft-primary btn-sm">Add Task</button>
                                </div><!--end /div-->
                            </div><!--end kanban-col-->

                            <div class="kanban-col">
                                <div class="kanban-main-card">
                                    <div class="kanban-box-title">
                                        <h4 class="card-title mt-0 mb-3">Done</h4>
                                    </div>

                                    <div id="project-list-right" class="pb-1">
                                        <div class="card">
                                            <div class="card-body">
                                                <i class="mdi mdi-circle-outline d-block mt-n2 font-18 text-warning"></i>
                                                <h5 class="my-1">Mobile Account Setting</h5>
                                                <p class="text-muted mb-2">Mobile App</p>
                                            </div><!--end card-body-->
                                        </div><!--end card-->

                                        <div class="card">
                                            <div class="card-body">
                                                <i class="mdi mdi-circle-outline d-block mt-n2 font-18 text-purple"></i>
                                                <h5 class="my-1">Mobile Account Setting</h5>
                                                <p class="text-muted mb-2">Mobile App</p>
                                                </div><!--end row-->
                                            </div><!--end card-body-->
                                        </div><!--end card-->
                                    </div><!--end project-list-right-->
                                    <button type="button" class="btn btn-block  btn-soft-primary btn-sm">Add Task</button>
                                </div><!--end /div-->
                            </div><!--end kanban-col-->
                        </div><!--end kanban-board-->
                    </div><!--end card-body-->
                </div><!--end card-->
            </div><!--end col-->
        </div><!--end row-->

    </div><!-- container -->


</div>
<!-- end page content --> --}}

<!-- Page Content-->
<div class="page-content">
    <div class="container-fluid">

        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="row">
                        <div class="col-10">
                            <h4 class="page-title">Ticket</h4>
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <!-- end page title end breadcrumb -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="kanban-board">
                            @foreach ($statuses as $index => $status)
                            <div class="kanban-col" id="{{$status->id}}">
                                <div class="kanban-main-card">
                                    <div class="kanban-box-title">
                                        <h4 class="card-title mt-0 mb-3">{{ $status->status }} - {{$status->id}}</h4>
                                    </div>

                                    @foreach ($status->tickets as $ticket)

                                    @php
                                        $divId = ''; // Default value
                                        if ($index === 0) {
                                            $divId = 'project-list-left';
                                        } elseif ($index === 1) {
                                            $divId = 'project-list-center-left';
                                        } elseif ($index === 2) {
                                            $divId = 'project-list-center-right';
                                        } elseif ($index === 3) {
                                            $divId = 'project-list-right';
                                        }
                                    @endphp

                                        <div id="{{ $divId }}" class="pb-1">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="dropdown d-inline-block float-right">
                                                        <a class="dropdown-toggle mr-n2 mt-n2" id="drop2" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                                            <i class="las la-ellipsis-v font-18 text-muted"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="drop2">
                                                            <a class="dropdown-item" href="{{ route('viewTicket', ['id' => $ticket->id]) }}">View</a>
                                                            <a class="dropdown-item" href="{{ route('editTicket', ['id' => $ticket->id]) }}">Edit</a>
                                                            <a class="dropdown-item" href="{{ route('deleteTicket', ['id' => $ticket->id]) }}">Delete</a>
                                                        </div>
                                                    </div><!--end dropdown-->

                                                    @if ($ticket->priority === 'High')
                                                        <i class="mdi mdi-circle-outline d-block mt-n2 font-18 text-danger"></i>
                                                    @elseif ($ticket->priority === 'Medium')
                                                        <i class="mdi mdi-circle-outline d-block mt-n2 font-18 text-warning"></i>
                                                    @elseif ($ticket->priority === 'Low')
                                                        <i class="mdi mdi-circle-outline d-block mt-n2 font-18 text-success"></i>
                                                    @else
                                                        <i class="mdi mdi-circle-outline d-block mt-n2 font-18 text-primary"></i>
                                                    @endif

                                                    <h5 class="my-1 font-14">{{ Carbon\Carbon::parse($ticket->created_at)->format('d M Y') }}</h5>
                                                    <p class="text mt-3 m-0">{{ $ticket->ticket_no }} - {{$status->id}}</p>
                                                    <p class="text m-0">{{ $ticket->sender_name }}</p>
                                                    <p class="text mb-2">{{ $ticket->sender_email }}</p>
                                                </div><!--end card-body-->
                                            </div><!--end card-->
                                        </div><!--end project-list-left-->
                                    @endforeach

                                    <a href="{{ route('createTicket') }}">
                                        <button type="button" class="btn btn-block btn-soft-primary btn-sm">Add Ticket</button>
                                    </a>

                                </div><!--end /div-->
                            </div><!--end kanban-col-->
                            @endforeach
                        </div><!--end kanban-board-->
                    </div><!--end card-body-->
                </div><!--end card-->
            </div><!--end col-->
        </div><!--end row-->

    </div><!-- container -->

</div>
<!-- end page content -->









@endsection

