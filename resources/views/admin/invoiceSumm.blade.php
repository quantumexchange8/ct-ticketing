@extends('layouts.masterAdmin')
@section('content')

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Page Content-->
<div class="page-content">
    <div class="container-fluid">

        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="page-title">
                                <a href="{{ route('invoice') }}">{{ $project->project_name }}</a>
                                 - Order
                            </h4>
                        </div><!--end col-->
                            {{-- <div class="col-4" style="display: flex; justify-content: flex-end;">
                                <button type="button" id="billButton" class="btn btn-soft-primary waves-effect waves-light">Bill</button>
                            </div> --}}
                        {{-- @if ($orderItems->isNotEmpty()) --}}
                            <div class="col-4" style="display: flex; justify-content: flex-end;">
                                <div class="col-4" style="display: flex; justify-content: flex-end;">
                                    <button type="button" id="billButton" class="btn btn-soft-primary waves-effect waves-light" onclick="createInvoice({{ $projectId }}, '{{ $invoiceNumber }}')">Bill</button>
                                    {{-- <button type="button" id="billButton" class="btn btn-soft-primary waves-effect waves-light" onclick="createInvoice()">Bill</button> --}}
                                </div>
                            </div>
                        {{-- @endif --}}

                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <!-- end page title end breadcrumb -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            <!-- Custom Checkbox -->
                                            <label class="custom-checkbox">
                                                <input type="checkbox" id="selectAllCheckbox">
                                                <span class="checkmark"></span>
                                            </label>
                                        </th>
                                        <th>Order Description</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Total Price (RM)</th>
                                        <th>Order ID</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orderItems as $orderItem)
                                    <tr data-orderitem-id="{{ $orderItem->id }}">
                                        <td>
                                            <!-- Custom Checkbox -->
                                            <label class="custom-checkbox">
                                                <input type="checkbox" class="orderItemCheckbox" data-orderitem-id="{{ $orderItem->id }}">
                                                <span class="checkmark"></span>
                                            </label>
                                            <!-- End Custom Checkbox -->
                                        </td>
                                        <td>{{ $orderItem->order_description }}</td>
                                        <td>{{ $orderItem->order_quantity }}</td>
                                        <td>{{ $orderItem->unit_price }}</td>
                                        <td>{{ $orderItem->total_price }}</td>
                                        <td>{{ $orderItem->order_id }}</td>
                                        <td class="text-center">
                                            <div style="display: flex; justify-content: center; gap: 10px;">
                                                <button class="btn btn-sm btn-soft-success btn-circle edit-order" data-order-id="{{ $orderItem->id }}">
                                                    <i class="dripicons-pencil"></i>
                                                </button>

                                                <form action="{{ route('deleteOrder', ['id' => $orderItem->id]) }}" method="POST" id="deleteForm{{ $orderItem->id }}" data-order-id="{{ $orderItem->id }}">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="button" class="btn btn-sm btn-soft-danger btn-circle" onclick="confirmDelete('deleteForm{{ $orderItem->id }}')">
                                                        <i class="dripicons-trash"></i>
                                                    </button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <span class="float-right">
                            <a href="{{ route('createOrder', ['project' => $project ]) }}">
                                <button class="btn btn-danger mt-2">Add New Order Item</button>
                            </a>
                        </span><!--end table-->
                    </div><!--end card-body-->
                </div><!--end card-->
            </div> <!-- end col -->
        </div> <!-- end row -->

        <div id="invoice">

        </div>

        {{-- <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body invoice-head">
                        <div class="row">
                            <div class="col-md-4 align-self-center">
                                <img src="{{ asset('assets/images/current-tech-logo-black.png') }}" alt="logo-large" class="logo-lg logo-dark" height="100" weight="100">
                            </div><!--end col-->
                            <div class="col-md-8">

                                <ul class="list-inline mb-0 contact-detail float-right">
                                    <li class="list-inline-item">
                                        <div class="pl-3">
                                            <i class="mdi mdi-web"></i>
                                            <p class="text-muted mb-0">{{ $user->email }}</p>
                                        </div>
                                    </li>
                                    <li class="list-inline-item">
                                        <div class="pl-3">
                                            <i class="mdi mdi-phone"></i>
                                            <p class="text-muted mb-0">{{ $user->phone_number }}</p>
                                        </div>
                                    </li>
                                </ul>
                            </div><!--end col-->
                        </div><!--end row-->
                    </div><!--end card-body-->
                    <div class="card-body">
                        <div class="row mb-5">
                            <div class="col-md-3">
                                <div class="">
                                    <h6 class="mb-0"><b>Order Date :</b> 11/05/2020</h6>
                                    <h6><b>Order No :</b> # 23654789</h6>
                                </div>
                            </div><!--end col-->
                            <div class="col-md-3">
                                <div class="float-left">
                                    <address class="font-13">
                                        <strong class="font-14">Billed To :</strong><br>
                                        Joe Smith<br>
                                    </address>
                                </div>
                            </div><!--end col-->
                        </div><!--end row-->

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-responsive project-invoice">
                                    <table class="table table-bordered mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Quantity</th>
                                                <th>Description</th>
                                                <th>Unit Price</th>
                                                <th>Subtotal</th>
                                            </tr><!--end tr-->
                                        </thead>
                                        <tbody>

                                            <tr>
                                                <td>
                                                    <h5 class="mt-0 mb-1 font-14">Project Design</h5>
                                                </td>
                                                <td>60</td>
                                                <td>$50</td>
                                                <td>$3000.00</td>
                                            </tr><!--end tr-->

                                            <tr class="bg-black text-white">
                                                <th colspan="2" class="border-0"></th>
                                                <td class="border-0 font-14"><b>Total</b></td>
                                                <td class="border-0 font-14"><b>$82,000.00</b></td>
                                            </tr><!--end tr-->
                                        </tbody>
                                    </table><!--end table-->
                                </div>  <!--end /div-->
                            </div>  <!--end col-->
                        </div><!--end row-->

                        <div class="row justify-content-center">
                            <div class="col-lg-6">
                                <h5 class="mt-4">Terms And Condition :</h5>
                                <ul class="pl-3">
                                    <li><small class="font-12">All accounts are to be paid within 7 days from receipt of invoice. </small></li>
                                    <li><small class="font-12">To be paid by cheque or credit card or direct payment online.</small ></li>
                                    <li><small class="font-12"> If account is not paid within 7 days the credits details supplied as confirmation of work undertaken will be charged the agreed quoted fee noted above.</small></li>
                                </ul>
                            </div> <!--end col-->
                            <div class="col-lg-6 align-self-end">
                                <div class="float-right" style="width: 30%;">
                                    <small>Account Manager</small>
                                    <img src="assets/images/signature.png" alt="" class="mt-2 mb-1" height="26">
                                    <p class="border-top">Signature</p>
                                </div>
                            </div><!--end col-->
                        </div><!--end row-->
                        <hr>
                        <div class="row d-flex justify-content-center">
                            <div class="col-lg-12 col-xl-4 ml-auto align-self-center">
                                <div class="text-center"><small class="font-12">Thank you very much for doing business with us.</small></div>
                            </div><!--end col-->
                            <div class="col-lg-12 col-xl-4">
                                <div class="float-right d-print-none">
                                    <a href="javascript:window.print()" class="btn btn-info"><i class="fa fa-print"></i></a>
                                    <a href="#" class="btn btn-primary">Submit</a>
                                    <a href="#" class="btn btn-danger">Cancel</a>
                                </div>
                            </div><!--end col-->
                        </div><!--end row-->
                    </div><!--end card-body-->
                </div><!--end card-->
            </div><!--end col-->
        </div><!--end row--> --}}


    </div><!-- container -->
</div>
<!-- end page content -->

{{-- Edit Order --}}
<div id="editOrderModal" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModalLabel">Edit Project</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{ isset($orderItem) ? route('updateOrder', $orderItem->id) : '#' }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="order_description">Order Description</label>
                            <input type="text" class="form-control" name="order_description" id="order_description" autocomplete="off">
                            @error('order_description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="order_quantity">Order Quantity</label>
                            <input type="text" class="form-control" name="order_quantity" id="order_quantity" autocomplete="off" onchange="calculateTotal(this)">
                            @error('order_quantity')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="unit_price">Unit Price</label>
                            <input type="text" class="form-control" name="unit_price" id="unit_price" autocomplete="off" onchange="calculateTotal(this)">
                            @error('unit_price')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="total_price">Total Price</label>
                            <input type="number" class="form-control  total-price" name="total_price" id="total_price" autocomplete="off" readonly>
                            @error('total_price')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row" style="display: none;">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="id">id</label>
                            <input type="text" class="form-control" name="id" id="id" autocomplete="off">
                        </div>
                    </div>
                </div>

                <div class="col-12 text-right">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
      </div>
    </div>
</div>

{{-- Edit ajax --}}
<script>
    $(document).ready(function() {

        // When the user clicks the edit button, fetch the title data and display it in the modal
        $('.edit-order').click(function() {
            var orderId = $(this).data('order-id');

            // Fetch the title data via AJAX
            $.ajax({
                url: '/edit-order/' + orderId,
                type: 'GET',
                data: { id: orderId },
                success: function(response) {

                    // Update the modal content with the fetched title data
                    $('#order_description').val(response.orderItem.order_description);
                    $('#order_quantity').val(response.orderItem.order_quantity);
                    $('#unit_price').val(response.orderItem.unit_price);
                    $('#total_price').val(response.orderItem.total_price);
                    $('#id').val(response.orderItem.id);

                    // Show the modal
                    $('#editOrderModal').modal('show');
                }
            });
        });
    });
</script>

{{-- Calculate total price --}}
<script>
    function calculateTotal(input) {
        // Find the parent container
        var parentDiv = input.closest(".modal-body");

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

{{-- Invoice --}}
<script>
    // Function to handle bill button click
    function createInvoice(projectId, invoiceNumber) {
        // Get all checked order item IDs
        var checkedIds = [];
        document.querySelectorAll('.orderItemCheckbox:checked').forEach(function(checkbox) {
            checkedIds.push(checkbox.dataset.orderitemId);
        });

        // var url = "{{ route('createInvoice') }}?orderItemIds=" + checkedIds.join(',');
        // Construct the URL with the checked IDs, project ID, and invoice number, and navigate to createInvoice route
        var url = "{{ route('createInvoice') }}?projectId=" + projectId + "&orderItemIds=" + checkedIds.join(',') + "&invoiceNumber=" + invoiceNumber;
        window.location.href = url;
    }

    // Function to handle checkbox change
    function handleCheckboxChange(event) {
        var isChecked = event.target.checked;
        var checkedIds = [];
        if (isChecked) {
            // Check all checkboxes in the table body
            document.querySelectorAll('.orderItemCheckbox').forEach(function(checkbox) {
                checkbox.checked = true;
                checkedIds.push(checkbox.dataset.orderitemId);
            });
        } else {
            // Uncheck all checkboxes in the table body
            document.querySelectorAll('.orderItemCheckbox').forEach(function(checkbox) {
                checkbox.checked = false;
            });
        }
        console.log('Checked IDs:', checkedIds);
    }

    // Add event listener to the select all checkbox in the table head
    var selectAllCheckbox = document.getElementById('selectAllCheckbox');
    selectAllCheckbox.addEventListener('change', handleCheckboxChange);

    // Add event listener to each checkbox in the table body
    document.querySelectorAll('.orderItemCheckbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function(event) {
            var isChecked = event.target.checked;
            var orderId = event.target.dataset.orderitemId;
            console.log('Checkbox ID:', orderId, 'Checked:', isChecked);
        });
    });
</script>



<!-- Sweet-Alert  -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            Swal.fire({
                title: 'Done',
                text: '{{ session('success') }}',
                icon: 'success',
                timer: 1000, // 3000 milliseconds (3 seconds)
                showConfirmButton: false, // Hide the "OK" button
            });
        @endif
    });
</script>

{{-- Delete Confirmation --}}
<script>
    function confirmDelete(formId) {
        var orderId = document.getElementById(formId).getAttribute('data-order-id');
        // console.log('Title ID:', titleId);

        Swal.fire({
            title: 'Are you sure?',
            text: 'This action will delete the project and associated documentations.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
</script>

@endsection

