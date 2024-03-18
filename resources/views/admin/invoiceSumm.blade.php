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
                    <div class="row" style="display: flex; align-items: center;">
                        <div class="col-8">
                            <h4 class="page-title">{{ $project->project_name }} - Invoice</h4>
                        </div><!--end col-->
                        {{-- <div class="col-4" style="display: flex; justify-content: flex-end;">
                            <a href="{{route('createSub', ['project' => $project])}}">
                                <button type="button" class="btn btn-soft-primary waves-effect waves-light">Add FAQ</button>
                            </a>
                        </div> --}}
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
                            <table id="datatable2" class="table table-bordered" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>

                                        <th>Date</th>
                                        <th>Invoice No</th>
                                        <th>Terms</th>
                                        <th>Total Bill</th>
                                        <th>Discount</th>
                                        <th>Grand Total</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr>
                                        <td>{{ $order->created_at->format('d M Y') }}</td>
                                        <td>{{ $order->order_no }}</td>
                                        <td>{{ $order->terms }}</td>
                                        <td>{{ $order->total_bill }}</td>
                                        <td>{{ $order->discount }}</td>
                                        <td>{{ $order->grand_total }}</td>

                                        <td class="text-center" style="display: flex; justify-content: center; gap: 10px;">
                                            <a href="{{ route('viewInvoice', ['id' => $order->id]) }}" class="btn btn-sm btn-soft-purple btn-circle">
                                                <i class="dripicons-preview"></i>
                                            </a>

                                            <button class="btn btn-sm btn-soft-success btn-circle edit-order" data-order-id="{{ $order->id }}">
                                                <i class="dripicons-pencil"></i>
                                            </button>

                                            <form action="{{ route('deleteInvoice', ['id' => $order->id]) }}" method="POST" id="deleteForm{{ $order->id }}" data-order-id="{{ $order->id }}">
                                                @method('DELETE')
                                                @csrf
                                                <button type="button" class="btn btn-sm btn-soft-danger btn-circle" onclick="confirmDelete('deleteForm{{ $order->id }}')">
                                                    <i class="dripicons-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table><!--end /table-->
                        </div><!--end /tableresponsive-->
                        {{-- <span class="float-right">
                            <a href="{{ route('createTicket') }}">
                                <button class="btn btn-danger mt-2">Add New Ticket</button>
                            </a>
                        </span> --}}
                    </div><!--end card-body-->
                </div><!--end card-->
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div><!-- container -->
</div>
<!-- end page content -->

{{-- Edit Order --}}
<div id="editOrderModal" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModalLabel">Edit Order</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{ isset($order) ? route('updateInvoice', $order->id) : '#' }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="order_no">Order No</label>
                            <input type="text" class="form-control" name="order_no" id="order_no" autocomplete="off" readonly>
                            @error('order_no')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="terms">Terms</label>
                            <input type="text" class="form-control" name="terms" id="terms" autocomplete="off">
                            @error('terms')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="total_bill">Total Bill</label>
                            <input type="text" class="form-control" name="total_bill" id="total_bill" autocomplete="off" readonly onchange="calculateTotal(this)">
                            @error('total_bill')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="discount">Discount</label>
                            <input type="text" class="form-control" name="discount" id="discount" autocomplete="off" onchange="calculateTotal(this)">
                            @error('discount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="grand_total">Grand Total</label>
                            <input type="text" class="form-control grand-total" name="grand_total" id="grand_total" autocomplete="off" readonly>
                            @error('grand_total')
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
                url: '/edit-invoice/' + orderId,
                type: 'GET',
                data: { id: orderId },
                success: function(response) {

                    // Update the modal content with the fetched title data
                    $('#order_no').val(response.order.order_no);
                    $('#terms').val(response.order.terms);
                    $('#total_bill').val(response.order.total_bill);
                    $('#discount').val(response.order.discount);
                    $('#grand_total').val(response.order.grand_total);
                    $('#id').val(response.order.id);

                    // Show the modal
                    $('#editOrderModal').modal('show');
                }
            });
        });
    });
</script>

{{-- Calculate grand total --}}
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
        var totalBillInput;
        var discountInput;

        parentDiv.querySelectorAll('input').forEach(function(child) {

            if (child.classList.contains('form-control') && child.name.includes('total_bill')) {
                totalBillInput = child;
            }

            if (child.classList.contains('form-control') && child.name.includes('discount')) {
                discountInput = child;
            }
        });

        // Check if the quantity input is found
        if (!totalBillInput) {
            console.error('Total bill input not found within parent element.');
            return;
        }

        // Check if the unit price input is found
        if (!discountInput) {
            console.error('Discount input not found within parent element.');
            return;
        }

        // Get the value of the quantity input
        var totalBill = totalBillInput.value;

        // Get the value of the unit price input
        var discount = discountInput.value;

        // Find the total unit price input
        var grandTotalInput = parentDiv.querySelector(".grand-total");
        if (!grandTotalInput) {
            console.error('Grand total input not found within parent element.');
            return;
        }

        // Log quantity, unit price, and total
        console.log('Total bill:', totalBill);
        console.log('Discount:', discount);

        // Check if the discount is a valid number
        if (isNaN(discount)) {
                discount = 0; // Set discount to 0 if it's not a valid number
            }

        // Calculate the grandTotal
        var grandTotal = totalBill - (totalBill * (discount / 100));

        grandTotalInput.value = grandTotal;
    }
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
                timer: 1000,
                showConfirmButton: false,
            });
        @endif
    });
</script>

<script>
    function confirmDelete(formId) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This action will delete the order and associated order items.',
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
