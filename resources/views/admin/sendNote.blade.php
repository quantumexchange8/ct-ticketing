<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $note->note_title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

<!-- App css -->
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/jquery-ui.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/metisMenu.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Allura&family=Courgette&family=Grand+Hotel&family=Great+Vibes&family=Inter:wght@500&family=Parisienne&family=Sacramento&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card-body">

                        <h4 class="mt-0 font-15">{{ $note->note_title }}</h4>

                        {{-- <p>From: {{ $userName }}</p>
                        <p>Email: {{ $userEmail }}</p> --}}
                        <p>{!! $note->note_description !!}</p>
                        <hr/>

                        <div class="row">
                            <div class="col-lg-6">
                                <div style="font-size: {{ $emailSignature->font_size }}px;
                                    font-family: {{ $emailSignature->font_family }};
                                    color: {{ $emailSignature->font_color }}; ">
                                    {{ $emailSignature->sign_off }}
                                </div>
                                <div style="font-family: Palatino Linotype; font-size: 18px;" >{{ $user->name }}</div>
                                <div style="font-family: Book Antiqua; font-size: 15px;">Current Tech Industries Sdn Bhd | {{ $user->position }}</div>
                            </div>
                            {{-- <div class="col-lg-6">
                                <img src="{{ asset('assets/images/current-tech-logo-dark.png') }}" alt="logo-large"  width="90%" height="90%">
                            </div> --}}
                        </div>

                        <hr>
                        <div style="font-family: Book Antiqua; font-size: 13px;">Email: {{ $user->email }}</div>
                        <div style="font-family: Book Antiqua; font-size: 13px;">Phone Number: {{ $user->phone_number }}</div>

                        {{-- <a href="https://wa.link/96dzqq"><i class="fa fa-whatsapp" style="font-size:30px;color:green">Whatsapp</i></a> --}}
                        {{-- <a href="https://tttttt.me/amberlee_415"><i class="fa fa-whatsapp" style="font-size:30px;color:green"></i>Telegram</a> --}}
                        {{-- <a href="mailto: leejiaqi0415@gmail.com"><i class="fa fa-whatsapp" style="font-size:30px;color:green">Gmail</i></a> --}}

                        <div style="display:flex; flex-direction:row; gap:20px; margin-top:5px;">
                            <a href="https://{{ $user->whatsapp_me }}"><i class="fa-brands fa-square-whatsapp fa-2xl" style="color: #16da9f;"></i></a>
                            <a href="https://t.me/{{ $user->telegram_username }}"><i class="fa-brands fa-telegram fa-2xl" style="color: #74C0FC;"></i></a>
                        </div>

                    </div>
                </div>
            </div><!--end row-->
        </div>
    </div>

    <!-- jQuery  -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/metismenu.min.js') }}"></script>
    <script src="{{ asset('assets/js/waves.js') }}"></script>
    <script src="{{ asset('assets/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/js/moment.js') }}"></script>
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
</body>
</html>



