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
                            <h4 class="page-title">Create Ticket</h4>
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('addTicket') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="sender_name">Name</label>
                                        <input type="text" class="form-control" name="sender_name" placeholder=" Enter Name" autocomplete="off" value="{{ old('sender_name') }}">
                                        @error('sender_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="subject">Subject</label>
                                        <input type="text" class="form-control" name="subject" placeholder="Enter Subject" autocomplete="off" value="{{  old('subject') }}">
                                        @error('subject')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="sender_email">Email</label>
                                        <input type="email" class="form-control" name="sender_email" placeholder="Enter Email" autocomplete="off" value="{{ old('sender_email') }}">
                                        @error('sender_email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="category_id">Category</label>
                                        <select class="form-control" name="category_id">
                                            <option value="">Select Category</option>
                                            @foreach($supportCategories as $supportCategory)
                                                <option value="{{ $supportCategory->id }}" {{ old('category_id') == $supportCategory->id ? 'selected' : '' }}>
                                                    {!! $supportCategory->category_name !!}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="message">Message</label>
                                        <textarea type="text" class="form-control" name="message" placeholder="Enter Message" autocomplete="off" rows="5">{{ old('message') }}</textarea>
                                        @error('message')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="priority">Priority</label>
                                        <select class="form-control" name="priority">
                                            <option value="">Select Priority</option>
                                            <option value="Low" {{ old('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                                            <option value="Medium" {{ old('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
                                            <option value="High" {{ old('priority') == 'High' ? 'selected' : '' }}>High</option>
                                        </select>
                                        @error('priority')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="remarks">Project</label>
                                        <select class="form-control" name="project_id">
                                            <option value="">Select Project</option>
                                            @foreach($projects as $project)
                                                <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                                    {!! $project->project_name !!}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('project_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    {{-- <div class="form-group">
                                        <label for="message">Status</label>
                                        <select class="form-control" name="status_id">
                                            <option value="">Select Status</option>
                                            @foreach($ticketStatuses as $ticketStatus)
                                                <option value="{{ $ticketStatus->id }}" {{ old('status_id') == $ticketStatus->id ? 'selected' : '' }}>
                                                    {{ $ticketStatus->status }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('message')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div> --}}

                                    <div class="form-group">
                                        <label for="pic_id">PIC</label>
                                        <select class="form-control" name="pic_id">
                                            <option value="">Select PIC</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ old('pic_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->category_name }} - {!! $user->name !!}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('pic_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <fieldset>
                                <div class="repeater-default">
                                    <div data-repeater-list="car">
                                        <div data-repeater-item="">
                                            <div class="form-group row d-flex align-items-end">

                                                <div class="col-sm-11">
                                                    <input type="file" id="input-file-now" name="car[][t_image]">
                                                </div><!--end col-->

                                                <div class="col-sm-1">
                                                    <span data-repeater-delete="" class="btn btn-danger btn-sm">
                                                        <span class="far fa-trash-alt mr-1"></span> Delete
                                                    </span>
                                                </div><!--end col-->
                                            </div><!--end row-->
                                        </div><!--end /div-->
                                    </div><!--end repet-list-->
                                    <div class="form-group mb-0 row float-right">
                                        <div class="col-sm-12">
                                            <span data-repeater-create="" class="btn btn-secondary">
                                                <span class="fas fa-plus"></span> Add
                                            </span>
                                            <button type="submit" class="btn btn-primary px-4">Submit</button>
                                        </div><!--end col-->
                                    </div><!--end row-->
                                </div> <!--end repeter-->
                            </fieldset><!--end fieldset-->
                        </form>
                    </div><!--end card-body-->
                </div><!--end card-->
            </div><!--end col-->
        </div><!--end row-->
    </div>
</div>
<!-- end page content -->

@endsection
