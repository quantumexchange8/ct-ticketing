<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $ticket->subject }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/current-tech-logo-white.png') }}">

    <!-- App css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />


</head>
<body>
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card-body">

                        <h4 class="mt-0 font-15">{{ $ticket->subject }}</h4>

                        <p>From: {{ $ticket->sender_name }}</p>
                        <p>Email: {{ $ticket->sender_email }}</p>
                        <p>{{ $ticket->message }}</p>
                        <hr/>

                        <div class="row">
                            <div class="col-auto">
                                <div class="card">
                                    @if ($ticket->ticketImages->count() > 0)
                                        @foreach ($ticket->ticketImages as $image)
                                            <div class="py-2 text-center">
                                                {{-- <img src="{{ asset('storage/tickets/' . $image->t_image) }}" alt="Image" class="w-50 h-50"> --}}
                                                <a href="{{ asset('storage/tickets/' . $image->t_image) }}">{{$image->t_image}}</a>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--end row-->
        </div>
    </div>

    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
</body>
</html>



