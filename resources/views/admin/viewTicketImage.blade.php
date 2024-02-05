@extends('layouts.masterAdmin')
@section('content')

{{-- JQuery --}}
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                {{-- <div class="page-title-box"> --}}
                    <div class="row" style="padding:10px;">
                        <div class="col">
                            <h4 class="page-title mt-2">Ticket Image - ({{ $tickets->ticket_no }})</h4>
                        </div><!--end col-->
                    </div><!--end row-->
                {{-- </div><!--end page-title-box--> --}}
            </div><!--end col-->
        </div><!--end row-->

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
        </div><!--end row-->
    </div>
</div>
<!-- end page content -->

<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
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
