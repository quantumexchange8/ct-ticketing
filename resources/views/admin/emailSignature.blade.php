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
                            <h4 class="page-title">Email Signature</h4>
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <!-- end page title end breadcrumb -->
        <div class="row">
            @php
                $logoUrl = asset('assets/images/current-tech-logo-black.png');
            @endphp
            {{-- <div class="col-lg-6" id="emailSignatureData">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">Sign Off</h4>
                            </div><!--end col-->
                        </div>  <!--end row-->
                    </div><!--end card-header-->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="sign_off">Sign Off</label>
                                    <select class="form-control" name="sign_off">
                                        <option value="Best regards," {{ $emailSignature->sign_off  === 'Best regards,' ? 'selected' : '' }}>Best regards,</option>
                                        <option value="Sincerely," {{ $emailSignature->sign_off  === 'Sincerely,' ? 'selected' : '' }}>Sincerely,</option>
                                        <option value="Regards," {{ $emailSignature->sign_off  === 'Regards,' ? 'selected' : '' }}>Regards,</option>
                                        <option value="Best," {{ $emailSignature->sign_off  === 'Best,' ? 'selected' : '' }}>Best,</option>
                                        <option value="Kind regards," {{ $emailSignature->sign_off  === 'Kind regards,' ? 'selected' : '' }}>Kind regards,</option>
                                        <option value="Thanks," {{ $emailSignature->sign_off  === 'Thanks,' ? 'selected' : '' }}>Thanks,</option>
                                        <option value="Best wishes," {{ $emailSignature->sign_off  === 'Best wishes,' ? 'selected' : '' }}>Best wishes,</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="font_family">Font Family</label>
                                    <select class="form-control" name="font_family">
                                        <option value="Allura" {{ $emailSignature->font_family  === 'Allura' ? 'selected' : '' }}>Allura</option>
                                        <option value="Arial" {{ $emailSignature->font_family  === 'Arial' ? 'selected' : '' }}>Arial</option>
                                        <option value="Courgette"{{ $emailSignature->font_family  === 'Courgette' ? 'selected' : '' }}>Courgette</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="font_size">Font Size (px)</label>
                                    <input type="number" class="form-control" name="font_size" value="{{ $emailSignature->font_size }}">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="font_color">Font Color</label>
                                    <input type="color" class="form-control" name="font_color" value="{{ $emailSignature->font_color }}">
                                </div>
                            </div>
                        </div>
                    </div><!--end card-body-->
                </div><!--end card-->

                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">General</h4>
                            </div><!--end col-->
                        </div>  <!--end row-->
                    </div><!--end card-header-->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" name="name" value="{{ $user->name }}">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="position">Position</label>
                                    <input type="text" class="form-control" name="position" value="{{ $user->position }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="phone_number">Phone Number</label>
                                    <input type="text" class="form-control" name="phone_number" value="{{ $user->phone_number }}">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" name="email" value="{{ $user->email }}">
                                </div>
                            </div>
                        </div>

                    </div><!--end card-body-->
                </div><!--end card-->
            </div><!--end col--> --}}
            <div class="col-lg-6" id="emailSignatureData"></div>

            {{-- <div class="col-lg-6" id="realEmailSignature">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div style="font-size: {{ $emailSignature->font_size }}px;
                                            font-family: {{ $emailSignature->font_family }};
                                            color: {{ $emailSignature->font_color }}; ">
                                            {{ $emailSignature->sign_off }}
                                        </div>
                                        <div style="font-family: Palatino Linotype; font-size: 20px;" >{{ $user->name }}</div>
                                        <div style="font-family: Book Antiqua; font-size: 15px;">{{ $user->position }}</div>
                                    </div>
                                </div>

                                <hr>
                                <div style="font-family: Book Antiqua; font-size: 13px;">Email: {{$user->email }}</div>
                                <div style="font-family: Book Antiqua; font-size: 13px;">Phone Number: {{$user->phone_number }}</div>
                            </div>
                        </div>
                    </div><!--end card-body-->
                </div><!--end card-->
            </div><!--end col--> --}}
            <div class="col-lg-6" id="realEmailSignature"></div>
        </div><!--end row-->
    </div><!-- container -->
</div>
<!-- end page content -->



<script>
    // JavaScript for AJAX request
    window.addEventListener('DOMContentLoaded', function () {
        getEmailSignatureData();
    });

    function getEmailSignatureData() {
        // Make an AJAX request to your backend route
        fetch('/get-email-signature') // Update this with your actual backend route
            .then(response => response.json())
            .then(data => {
                // Handle the response data and update the HTML
                const emailSignature = data.emailSignature;
                const user = data.user;
                const logoUrl = '{{ $logoUrl }}';
                console.log(logoUrl);
                document.getElementById('emailSignatureData').innerHTML =
                    '<div class="card">' +
                        '<div class="card-header">' +
                            '<div class="row align-items-center">' +
                                '<div class="col">' +
                                '<h4 class="card-title">Sign Off</h4>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="card-body">' +
                            '<div class="row">' +
                                '<div class="col-lg-6">' +
                                    '<div class="form-group">' +
                                        '<label for="sign_off">Sign Off</label>' +
                                        '<select class="form-control" name="sign_off" onchange="updateEmailSignature(event)">' +
                                            '<option value="Best regards," ' + (emailSignature.sign_off === 'Best regards,' ? 'selected' : '') + '>Best regards,</option>' +
                                            '<option value="Sincerely," ' + (emailSignature.sign_off === 'Sincerely,' ? 'selected' : '') + '>Sincerely,</option>' +
                                            '<option value="Regards," ' + (emailSignature.sign_off === 'Regards,' ? 'selected' : '') + '>Regards,</option>' +
                                            '<option value="Best," ' + (emailSignature.sign_off === 'Best,' ? 'selected' : '') + '>Best,</option>' +
                                            '<option value="Thanks," ' + (emailSignature.sign_off === 'Thanks,' ? 'selected' : '') + '>Thanks,</option>' +
                                            '<option value="Best wishes," ' + (emailSignature.sign_off === 'Best wishes,' ? 'selected' : '') + '>Best wishes,</option>' +
                                        '</select>' +
                                    '</div>' +
                                '</div>' +
                                '<div class="col-lg-6">' +
                                    '<div class="form-group">' +
                                        '<label for="font_family">Font Family</label>' +
                                        '<select class="form-control" name="font_family" onchange="updateEmailSignature(event)">' +
                                            '<option value="Allura" ' + (emailSignature.font_family === 'Allura' ? 'selected' : '') + '>Allura</option>' +
                                            '<option value="Grand Hotel" ' + (emailSignature.font_family === 'Grand Hotel' ? 'selected' : '') + '>Grand Hotel</option>' +
                                            '<option value="Great Vibes" ' + (emailSignature.font_family === 'Great Vibes' ? 'selected' : '') + '>Great Vibes</option>' +
                                            '<option value="Parisienne" ' + (emailSignature.font_family === 'Parisienne' ? 'selected' : '') + '>Parisienne</option>' +
                                            '<option value="Courgette" ' + (emailSignature.font_family === 'Courgette' ? 'selected' : '') + '>Courgette</option>' +
                                        '</select>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="row">' +
                                '<div class="col-lg-6">' +
                                    '<div class="form-group">' +
                                        '<label for="font_size">Font Size (px)</label>' +
                                        '<input type="number" class="form-control" name="font_size" value="' + emailSignature.font_size + '" onchange="updateEmailSignature(event)">' +
                                    '</div>' +
                                '</div>' +
                                '<div class="col-lg-6">' +
                                    '<div class="form-group">' +
                                        '<label for="font_color">Font Color</label>' +
                                        '<input type="color" class="form-control" name="font_color" value="' + emailSignature.font_color + '" onchange="updateEmailSignature(event)">' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' + // Close the "Sign Off" card
                    '<div class="card">' +
                        '<div class="card-header">' +
                            '<div class="row align-items-center">' +
                                '<div class="col">' +
                                    '<h4 class="card-title">General</h4>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="card-body">' +
                            '<div class="row">' +
                                '<div class="col-lg-6">' +
                                    '<div class="form-group">' +
                                        '<label for="name">Name</label>' +
                                        '<input type="text" class="form-control" name="name" value="' + user.name + '" autocomplete="off" onchange="updateEmailSignature(event)">' +
                                    '</div>' +
                                '</div>' +
                                '<div class="col-lg-6">' +
                                    '<div class="form-group">' +
                                        '<label for="position">Position</label>' +
                                        '<input type="text" class="form-control" name="position" value="' + user.position + '" autocomplete="off" onchange="updateEmailSignature(event)">' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="row">' +
                                '<div class="col-lg-6">' +
                                    '<div class="form-group">' +
                                    '<label for="phone_number">Phone Number</label>' +
                                    '<input type="text" class="form-control" name="phone_number" value="' + user.phone_number + '" autocomplete="off" onchange="updateEmailSignature(event)">' +
                                    '</div>' +
                                '</div>' +
                                '<div class="col-lg-6">' +
                                    '<div class="form-group">' +
                                        '<label for="email">Email</label>' +
                                        '<input type="email" class="form-control" name="email" value="' + user.email + '" autocomplete="off" onchange="updateEmailSignature(event)">' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="row">' +
                                '<div class="col-lg-6">' +
                                    '<div class="form-group">' +
                                        '<div style="display: flex; flex-direction: row; align-items: baseline; gap: 5px;">' +
                                            '<label for="whatsapp_me">WhatsApp Me</label>' +
                                            '<a href="https://create.wa.link/"><i class="fa-solid fa-circle-info" title="How to create whatsapp me link?"></i></a>' +
                                        '</div>' +
                                        '<input type="text" class="form-control" name="whatsapp_me" value="' + user.whatsapp_me + '" autocomplete="off" placeholder="Ex: wa.link/u64q5m" onchange="updateEmailSignature(event)">' +
                                    '</div>' +
                                '</div>' +
                                '<div class="col-lg-6">' +
                                    '<div class="form-group">' +
                                        '<label for="telegram_username">Telegram Username</label>' +
                                        '<input type="text" class="form-control" name="telegram_username" value="' + user.telegram_username + '" autocomplete="off" placeholder="Ex: amberlee_415" onchange="updateEmailSignature(event)">' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="row" style="display: none;">' +
                                '<div class="col-lg-6">' +
                                    '<div class="form-group">' +
                                        '<label for="user_id">User ID</label>' +
                                        '<input type="text" class="form-control" name="user_id" value="' + user.id + '">' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>'; // Close the "General" card

                document.getElementById('realEmailSignature').innerHTML =
                    '<div class="card">' +
                        '<div class="card-body">' +
                            '<div class="row">' +
                                '<div class="col-lg-12">' +
                                    '<div class="row">' +
                                        '<div class="col-lg-12">' +
                                            '<div style="font-size: ' + emailSignature.font_size + 'px; font-family: ' + emailSignature.font_family + '; color: ' + emailSignature.font_color + '; ">' +
                                                 emailSignature.sign_off  +
                                            '</div>' +
                                            '<div style="font-family: Palatino Linotype; font-size: 20px;">' + user.name + '</div>' +
                                            '<div style="font-family: Book Antiqua; font-size: 15px;"> Current Tech Industries Sdn Bhd |' + user.position + '</div>' +
                                        '</div>' +
                                        // '<div class="col-lg-6">' +
                                        //     '<img src="' + logoUrl + '" alt="logo-large"  width="90%" height="90%">' +
                                        // '</div>' +
                                    '</div>' +
                                    '<hr>' +
                                    '<div style="font-family: Book Antiqua; font-size: 13px;">Email: ' + user.email + '</div>' +
                                    '<div style="font-family: Book Antiqua; font-size: 13px;">Phone Number: ' + user.phone_number + '</div>' +
                                    '<div style="display:flex; flex-direction:row; gap:20px; margin-top:5px;">' +
                                        '<a href="https://' + user.whatsapp_me + '">' +
                                        '<i class="fa-brands fa-square-whatsapp fa-2xl" style="color: #16da9f;"></i>' +
                                        '</a>' +

                                        '<a href="https://t.me/' + user.telegram_username + '">' +
                                        '<i class="fa-brands fa-telegram fa-2xl" style="color: #74C0FC;"></i>' +
                                        '</a>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>';

            })
            .catch(error => console.error('Error:', error));
    }

    function updateEmailSignature(event) {
        const fieldName = event.target.name;
        const fieldValue = event.target.value;

        const formData = new FormData();
        formData.append('user_id', document.querySelector('input[name="user_id"]').value);
        formData.append(fieldName, fieldValue);

        // Get the CSRF token from the meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('/update-email-signature', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to update ' + fieldName);
            }
            getEmailSignatureData();
        })
        .catch(error => console.error('Error:', error));
    }


</script>

@endsection
