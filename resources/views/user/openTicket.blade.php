@extends('layouts.masterMember')
@section('content')

<!-- Support start -->
<section class="section-sm" id="Support">
    <div class="page-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card my-4">
                    <div class="card-header">
                        <h4 class="card-title">Support</h4>
                        <p class="text-muted mb-0">Open Ticket</p>
                    </div><!--end card-header-->
                    <div class="card-body">
                        <form action="{{route('submitTicket')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="username">Full Name</label>
                                        <input type="text" class="form-control" id="username" name="sender_name" autocomplete="off" value="{{ old('sender_name') }}" required>
                                        @error('sender_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="useremail">Email Address</label>
                                        <input type="email" class="form-control" id="useremail" name="sender_email" autocomplete="off" value="{{ old('sender_email') }}" required>
                                        @error('sender_email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="subject">Subject</label>
                                        <input type="text" class="form-control" id="subject" name="subject" autocomplete="off" value="{{ old('subject') }}" required>
                                        @error('subject')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="username">Category</label>
                                        <select class="form-control" name="category_id" required>
                                            <option value="">Select Category</option>
                                            @foreach($supportCategories as $supportCategory)
                                                <option value="{{ $supportCategory->id }}" {{ old('category_id') == $supportCategory->id ? 'selected' : '' }}>{!! $supportCategory->category_name !!}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="useremail">Priority</label>
                                        <select class="form-control" name="priority" required>
                                            <option value="">Select Priority</option>
                                            <option value="Low" {{ old('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                                            <option value="Medium" {{ old('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
                                            <option value="High" {{ old('priority') == 'High' ? 'selected' : '' }}>High</option>
                                        </select>
                                        @error('priority')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="message">Message</label>
                                        <textarea class="form-control" rows="5" id="message" name="message" autocomplete="off" value="{{ old('message') }}"  required></textarea>
                                        @error('title_id')
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
                                                    {{-- <input type="file" id="input-file-now" name="t_image" class="dropify" /> --}}
                                                    <input type="file" id="input-file-now" name="car[][t_image]">

                                                </div><!--end col-->

                                                <div class="col-sm-1">
                                                    <span data-repeater-delete="" class="btn btn-danger">
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
                                            <button type="submit" class="btn btn-primary px-4">Send Message</button>
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
    <!-- end page content -->
</section>
<!-- Support end -->

<!-- Sweet-Alert  -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        @if(session('success'))
            Swal.fire({
                title: 'Done',
                text: '{{ session('success') }}',
                icon: 'success',
                timer: 1000,
                showConfirmButton: false,
            });
        @endif
    });
</script>

@endsection
