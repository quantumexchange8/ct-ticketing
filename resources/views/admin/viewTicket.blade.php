@extends('layouts.masterAdmin')
@section('content')

{{-- JQuery --}}
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Page Content-->
<div class="page-content">
    <div class="container-fluid">
        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="row">
                        <div class="col">
                            <h4 class="page-title">Ticket</h4>
                            {{-- <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('ticketSumm', ['status' => $ticket->ticketStatus->id])}}">{{ $ticket->ticketStatus->status }}</a></li>
                                <li class="breadcrumb-item active">View More</li>
                            </ol> --}}
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="ticket_no">Ticket No</label>
                                    <input type="text" class="form-control" name="ticket_no" placeholder="Ticket No" value="{{ $ticket->ticket_no }}" readonly>
                                    @error('ticket_no')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="subject">Subject</label>
                                    <input type="text" class="form-control" name="subject" placeholder="Subject" value="{{ $ticket->subject }}" readonly>
                                    @error('subject')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="sender_name">Name</label>
                                    <input type="text" class="form-control" name="sender_name" placeholder="Name" value="{{ $ticket->sender_name }}" readonly>
                                    @error('sender_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="sender_email">Email</label>
                                    <input type="email" class="form-control" name="sender_email" placeholder="Email" value="{{ $ticket->sender_email }}" readonly>
                                    @error('sender_email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="message">Message</label>
                                    <textarea type="text" class="form-control" name="message" placeholder="Message" rows="5" readonly>{{ $ticket->message }}</textarea>
                                    @error('message')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="message">Category</label>
                                    <select class="form-control" name="category_id" disabled>
                                        <option value="">Select Category</option>
                                        @foreach($supportCategories as $supportCategory)
                                            <option value="{{ $supportCategory->id }}" {{ $ticket->supportCategories->id == $supportCategory->id ? 'selected' : '' }}>
                                                {!! $supportCategory->category_name !!}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="message">Status</label>
                                    <select class="form-control" name="status_id" disabled>
                                        <option value="">Select Status</option>
                                        @foreach($ticketStatuses as $ticketStatus)
                                            <option value="{{ $ticketStatus->id }}" {{ $ticket->ticketStatus->id == $ticketStatus->id ? 'selected' : '' }}>
                                                {{ $ticketStatus->status }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('message')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="message">Remarks</label>
                                    <textarea type="text" class="form-control" name="remarks" placeholder="Remarks" rows="5" readonly>{{ $ticket->remarks }}</textarea>
                                    @error('remarks')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="message">Priority</label>
                                    <select class="form-control" name="priority" disabled>
                                        <option value="">Select Priority</option>
                                        <option value="Low" {{ $ticket->priority  === 'Low' ? 'selected' : '' }}>Low</option>
                                        <option value="Medium" {{ $ticket->priority  === 'Medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="High" {{ $ticket->priority  === 'High' ? 'selected' : '' }}>High</option>
                                    </select>
                                    @error('message')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="message">PIC</label>
                                    <select class="form-control" name="pic_id" disabled>
                                        <option value="">Select PIC</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ optional($ticket->users)->id == $user->id ? 'selected' : '' }}>
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

                        <div class="row">
                            @foreach ($ticketImages as $ticketImage)
                                <div class="col-sm-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div>
                                                <a href="{{ asset('storage/tickets/' . $ticketImage->t_image) }}" class="file-modal-link">
                                                    <img src="{{ asset('storage/tickets/' . $ticketImage->t_image) }}" alt="Ticket Image" style="width: 100%; height: 100%">
                                                </a>
                                            </div>
                                        </div><!--end card-body-->
                                    </div><!--end card-->
                                </div><!--end col-->
                            @endforeach
                        </div>

                        @can('update', $ticket)
                        <div class="col-12 text-right">
                            <a href="{{ route('editTicket', ['id' => $ticket->id]) }}" class="btn  btn-primary">
                                Edit Ticket
                            </a>
                        </div>
                        @endcan
                    </div><!--end card-body-->
                </div><!--end card-->
            </div><!--end col-->
        </div><!--end row-->
    </div>
</div>
<!-- end page content -->

<!-- Modal Markup -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg" role="document" style="max-width: 700px; height: 500px;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <img id="previewImage" src="" alt="Image Preview" class="img-fluid">
        </div>
      </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        console.log('Jquery is working');
        $('.file-modal-link').on('click', function(event) {
        event.preventDefault();
        var imageUrl = $(this).attr('href');
        $('#previewImage').attr('src', imageUrl);
        $('#imageModal').modal('show');
        });
    });
</script>

@endsection


