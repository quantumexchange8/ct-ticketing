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
                            <h4 class="page-title">Edit Ticket</h4>
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('updateTicket', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

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

                                    <div class="form-group">
                                        <label for="priority">Priority</label>
                                        <select class="form-control" name="priority">
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
                                        <label for="status_id">Status</label>
                                        <select class="form-control" name="status_id">
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
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="message">Message</label>
                                        <textarea type="text" class="form-control" name="message" placeholder="Message" rows="12" readonly>{{ $ticket->message }}</textarea>
                                        @error('message')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="category_id">Category</label>
                                        <select class="form-control" name="category_id">
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



                                    {{-- <div class="form-group">
                                        <label for="remarks">Remarks</label>
                                        <textarea type="text" class="form-control" name="remarks" placeholder="Remarks" rows="5" >{{ $ticket->remarks }}</textarea>
                                        @error('remarks')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div> --}}
                                </div>

                                <div class="col-lg-6">


                                    <div class="form-group">
                                        <label for="pic_id">PIC</label>
                                        <select class="form-control" name="pic_id">
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

                            {{-- <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="t_image">Images</label>
                                        @foreach ($ticketImages as $ticketImage)
                                        <div>
                                            <a href="{{ asset('storage/tickets/' . $ticketImage->t_image) }}" class="file-modal-link">{{$ticketImage->t_image}}</a>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div> --}}

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

                            <div class="col-12 text-right">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>

                        </form>
                    </div><!--end card-body-->
                </div><!--end card-->
            </div><!--end col-->

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Notes</h4>
                    </div><!--end card-header-->
                    <div class="card-body">
                        <div class="slimscroll activity-scroll">
                            <div class="activity">
                                @if(!$notes->isEmpty())
                                    @foreach ($notes as $note)
                                        <div class="activity-info">
                                            <div class="icon-info-activity">
                                                @if ($note->sent == 0)
                                                    <i class="las la-check-circle bg-soft-primary"></i>
                                                @else
                                                    <i class="las la-comment-dots bg-soft-primary"></i>
                                                @endif
                                            </div>
                                            <div class="activity-info-text">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="m-0 w-75">{{ $note->note_no }}</h6>
                                                    <span class="text-muted d-block">{{Carbon\Carbon::parse($note->created_at)->format('d M Y') }}</span>
                                                </div>
                                                <p class="text-muted mt-3">{{ $note->note_title }}</p>
                                                <p class="text-muted mt-3">{{ $note->note_description }}
                                                    <a href="#" class="text-info edit-notes" data-note-id="{{ $note->id }}">[Edit]</a>
                                                    <a href="#" class="text-info" onclick="confirmDelete('{{ $note->id }}')">[Delete]</a>
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                <div class="col-12 text-right">
                                    <a href="#" class="btn  btn-primary" id="addnotes">
                                        Add New Notes
                                    </a>
                                </div>
                            </div><!--end activity-->
                        </div><!--end activity-scroll-->
                    </div>  <!--end card-body-->
                </div><!--end card-->
            </div><!--end col-->
        </div><!--end row-->
    </div>
</div>
<!-- end page content -->

<!-- Add notes modal -->
<div class="modal fade" id="noteModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg" role="document" style="max-width: 500px; height: 500px; margin-top: 150px;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModalLabel">Add New Notes</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('addNote') }}" method="POST" >
                @csrf
                {{-- <div class="row"> --}}
                    {{-- <div class="col-lg-6"> --}}
                        <div class="form-group">
                            <label for="note_title">Title</label>
                            {{-- <input type="text" class="form-control" name="note_title" autocomplete="off"> --}}
                            <textarea type="text" class="form-control" name="note_title" rows="5" autocomplete="off"></textarea>
                            @error('note_title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                        </div>
                    {{-- </div> --}}
                    {{-- <div class="col-lg-6"> --}}
                        <div class="form-group">
                            <label for="note_description">Description</label>
                            <textarea type="text" class="form-control" name="note_description" rows="5" autocomplete="off"></textarea>
                            @error('note_description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group" style="display: none;">
                            <label for="note_description">Ticket ID</label>
                            <input type="text" class="form-control" name="ticket_id" value="{{ $ticket->id }}">
                        </div>
                    {{-- </div> --}}
                {{-- </div> --}}
                <div class="col-12 text-right">
                    <button type="submit" class="btn btn-primary px-4">Submit</button>
                </div>
            </form>
        </div>
      </div>
    </div>
</div>

<!-- Edit notes modal -->
<div class="modal fade" id="editNoteModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg" role="document" style="max-width: 500px; height: 500px; margin-top: 150px;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModalLabel">Edit Notes</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{ isset($note) ? route('updateNote', $note->id) : '#' }}" method="POST">
                @csrf
                    <div class="form-group">
                        <label for="note_title">Title</label>
                        <textarea type="text" class="form-control" name="note_title" rows="5" autocomplete="off"></textarea>
                        @error('note_title')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                    </div>

                    <div class="form-group">
                        <label for="note_description">Description</label>
                        <textarea type="text" class="form-control" name="note_description" rows="5" autocomplete="off"></textarea>
                        @error('note_description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group" style="display: none;">
                        <label for="note_description">Ticket ID</label>
                        <input type="text" class="form-control" name="ticket_id" value="{{ $ticket->id }}">
                    </div>
                <div class="col-12 text-right">
                    <button type="submit" class="btn btn-primary px-4" name="action" value="save_only">Save Only</button>
                    <button type="submit" class="btn btn-primary px-4" name="action" value="save_and_send_email">Save and Send Email</button>
                </div>
            </form>
        </div>
      </div>
    </div>
</div>

<!-- Image modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 700px;">
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

        $('.file-modal-link').on('click', function(event) {
            event.preventDefault();
            var imageUrl = $(this).attr('href');
                $('#previewImage').attr('src', imageUrl);
                $('#imageModal').modal('show');
        });

        $('#addnotes').on('click', function(event) {
            event.preventDefault();
            $('#noteModal').modal('show');
        });

        $('#noteModal').on('hidden.bs.modal', function () {
            $(this).find('input[type=text]').val('');
        });

        $('.edit-notes').on('click', function(event) {
            event.preventDefault();
            var noteId = $(this).data('note-id');

            $.ajax({
                url: '/edit-note/' + noteId,
                method: 'GET',
                success: function(response) {

                    if (response.note) {
                        $('#editNoteModal textarea[name=note_title]').val(response.note.note_title);
                        $('#editNoteModal textarea[name=note_description]').val(response.note.note_description);

                        $('#editNoteModal').modal('show');
                    } else {
                        console.error('Note data not found in response');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });

    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    function confirmDelete(noteId) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This action will delete the note.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                console.log(noteId);
                $.ajax({
                    url: '/delete-note/' + noteId,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                        // Reload the page or update the UI as required
                        // window.location.reload();

                        // Swal.fire({
                        //         icon: 'success',
                        //         title: 'Success',
                        //         showConfirmButton: false,
                        //         timer: 5000,

                        //     });
                        window.location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }
        });
    }
</script>
@endsection
