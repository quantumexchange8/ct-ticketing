@extends('layouts.masterAdmin')
@section('content')

<!-- Support start -->
<section class="section-sm" id="Support">
    <div class="page-content">
        {{-- <div id="html-container">

        </div> --}}
            <div class="row">
                <div class="col-lg-12">
                    <div class="card my-4">
                        <div class="card-header">
                            <h4 class="card-title">Create Order</h4>
                        </div><!--end card-header-->
                        <div class="card-body">
                            <form action="{{route('addOrder', ['project' => $project])}}" method="POST" enctype="multipart/form-data">
                                @csrf

                                {{-- @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif --}}

                                <fieldset>
                                    <div class="repeater-default">
                                        <div data-repeater-list="car">
                                            <div data-repeater-item="">
                                                <div class="form-group row d-flex align-items-end">

                                                    <div class="col-sm-1">
                                                        <label class="control-label">Quantity</label>
                                                        <input type="number" class="form-control" name="car[][order_quantity]" onchange="calculateTotal(this)">
                                                        @error('car[][order_quantity]')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div><!--end col-->

                                                    <div class="col-sm-4">
                                                    <label class="control-label">Description</label>
                                                        <input type="text" class="form-control" name="car[][order_description]">
                                                        @error('car[][order_description]')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div><!--end col-->

                                                    <div class="col-sm-3">
                                                        <label class="control-label">Unit Price</label>
                                                        <input type="text" class="form-control" name="car[][unit_price]" onchange="calculateTotal(this)">
                                                        @error('car[][unit_price]')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div><!--end col-->

                                                    <div class="col-sm-3">
                                                        <label class="control-label">Total Price</label>
                                                        <input type="number" class="form-control total-price" name="car[][total_price]" readonly>
                                                        @error('car[][total_price]')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
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

<script>
    // document.addEventListener("DOMContentLoaded", function() {
    //     var htmlCode = `
    //         <div class="row">
    //             <div class="col-lg-12">
    //                 <div class="card my-4">
    //                     <div class="card-header">
    //                         <h4 class="card-title">Create Order</h4>
    //                     </div><!--end card-header-->
    //                     <div class="card-body">
    //                         <form action="{{route('submitTicket')}}" method="POST" enctype="multipart/form-data">
    //                             @csrf
    //                             <fieldset>
    //                                 <div class="repeater-default">
    //                                     <div data-repeater-list="car">
    //                                         <div data-repeater-item="">
    //                                             <div class="form-group row d-flex align-items-end">

    //                                                 <div class="col-sm-1">
    //                                                     <label class="control-label">Quantity</label>
    //                                                     <input type="number" class="form-control" name="car[][order_quantity]" onchange="calculateTotal(this)">
    //                                                 </div><!--end col-->

    //                                                 <div class="col-sm-4">
    //                                                     <label class="control-label">Description</label>
    //                                                     <input type="text" class="form-control" name="car[][order_description]">
    //                                                 </div><!--end col-->

    //                                                 <div class="col-sm-3">
    //                                                     <label class="control-label">Unit Price</label>
    //                                                     <input type="number" class="form-control" name="car[][unit_price]" onchange="calculateTotal(this)">
    //                                                 </div><!--end col-->

    //                                                 <div class="col-sm-3">
    //                                                     <label class="control-label">Total Unit Price</label>
    //                                                     <input type="number" class="form-control total-price" name="car[][total_price]" readonly>
    //                                                 </div><!--end col-->

    //                                                 <div class="col-sm-1">
    //                                                     <span data-repeater-delete="" class="btn btn-danger">
    //                                                         <span class="far fa-trash-alt mr-1"></span> Delete
    //                                                     </span>
    //                                                 </div><!--end col-->
    //                                             </div><!--end row-->
    //                                         </div><!--end /div-->
    //                                     </div><!--end repet-list-->
    //                                     <div class="form-group mb-0 row float-right">
    //                                         <div class="col-sm-12">
    //                                             <span data-repeater-create="" class="btn btn-secondary">
    //                                                 <span class="fas fa-plus"></span> Add
    //                                             </span>
    //                                             <button type="submit" class="btn btn-primary px-4">Send Message</button>
    //                                         </div><!--end col-->
    //                                     </div><!--end row-->
    //                                 </div> <!--end repeter-->
    //                             </fieldset><!--end fieldset-->
    //                         </form>
    //                     </div><!--end card-body-->
    //                 </div><!--end card-->
    //             </div><!--end col-->
    //         </div><!--end row-->
    //     `;

    //     document.getElementById("html-container").innerHTML = htmlCode;
    // });

    function calculateTotal(input) {
        // Find the parent container
        var parentDiv = input.closest(".form-group.row");

        // Check if the parent container is found
        if (!parentDiv) {
            console.error('Parent element not found.');
            return;
        }

        // Loop through all child elements of the parent container
        var quantityInput;
        var unitPriceInput;

        parentDiv.querySelectorAll('input').forEach(function(child) {

            if (child.classList.contains('form-control') && child.name.includes('order_quantity')) {
                quantityInput = child;
            }

            if (child.classList.contains('form-control') && child.name.includes('unit_price')) {
                unitPriceInput = child;
            }
        });

        // Check if the quantity input is found
        if (!quantityInput) {
            console.error('Quantity input not found within parent element.');
            return;
        }

        // Check if the unit price input is found
        if (!unitPriceInput) {
            console.error('Unit price input not found within parent element.');
            return;
        }

        // Get the value of the quantity input
        var quantity = quantityInput.value;

        // Get the value of the unit price input
        var unitPrice = unitPriceInput.value;

        // Find the total unit price input
        var totalInput = parentDiv.querySelector(".total-price");
        if (!totalInput) {
            console.error('Total unit price input not found within parent element.');
            return;
        }

        // Log quantity, unit price, and total
        console.log('Quantity:', quantity);
        console.log('Unit Price:', unitPrice);

        // Calculate total
        var total = quantity * unitPrice;
        totalInput.value = total;
    }
</script>

@endsection
