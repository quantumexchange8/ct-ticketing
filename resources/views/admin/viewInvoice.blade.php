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
                        <div class="col">
                            <h4 class="page-title mt-2">
                                <a href="{{ route('invoiceSumm', ['project' => $projectId]) }}">
                                    {{ $order->order_no }}
                                </a>
                            </h4>
                        </div><!--end col-->
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
                                        <th>Order Item</th>
                                        <th>Description</th>
                                        <th>Unit Price</th>
                                        <th>Quantity</th>
                                        <th>Total Price</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($orderItems as $orderItem)
                                    <tr>
                                        <td>{{ $orderItem->created_at->format('d M Y') }}</td>
                                        <td>{{ $orderItem->order_item }}</td>
                                        <td>{{ $orderItem->order_description }}</td>
                                        <td>{{ $orderItem->unit_price }}</td>
                                        <td>{{ $orderItem->order_quantity }}</td>
                                        <td>{{ $orderItem->total_price }}</td>
                                        <td class="text-center" style="display: flex; justify-content: center; gap: 10px;">
                                            <div style="display: flex; justify-content: center; gap: 10px;">
                                                <button class="btn btn-sm btn-soft-success btn-circle edit-order-item" data-order-id="{{ $orderItem->id }}">
                                                    <i class="dripicons-pencil"></i>
                                                </button>

                                                <form action="{{ route('deleteOrderItem', ['id' => $orderItem->id]) }}" method="POST" id="deleteForm{{ $orderItem->id }}" data-order-id="{{ $orderItem->id }}">
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
                            </table><!--end /table-->
                        </div><!--end /tableresponsive-->
                        <span class="float-right">
                            <button class="btn btn-danger mt-2" id="billButton" onclick="createInvoice({{ $projectId }}, '{{ $invoiceNumber }}', {{ json_encode($orderItemIds) }})">Create Invoice</button>
                        </span>
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
          <h5 class="modal-title" id="imageModalLabel">Edit Order Item</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{ isset($orderItem) ? route('updateOrderItem', $orderItem->id) : '#' }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="order_item">Order Item</label>
                            <input type="text" class="form-control" name="order_item" id="order_item" autocomplete="off">
                            @error('order_item')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="order_description">Order Description</label>
                            <input type="text" class="form-control" name="order_description" id="order_description" autocomplete="off">
                            @error('order_description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="order_quantity">Order Quantity</label>
                            <input type="text" class="form-control" name="order_quantity" id="order_quantity" autocomplete="off" onchange="calculateTotal(this)">
                            @error('order_quantity')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="unit_price">Unit Price</label>
                            <input type="text" class="form-control" name="unit_price" id="unit_price" autocomplete="off" onchange="calculateTotal(this)">
                            @error('unit_price')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-4">
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
        $('.edit-order-item').click(function() {
            var orderItemId = $(this).data('order-id');

            // Fetch the title data via AJAX
            $.ajax({
                url: '/edit-order-item/' + orderItemId,
                type: 'GET',
                data: { id: orderItemId },
                success: function(response) {

                    // Update the modal content with the fetched title data
                    $('#order_item').val(response.orderItem.order_item);
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

        Swal.fire({
            title: 'Are you sure?',
            text: 'This action will delete the order item.',
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

{{-- Invoice --}}
<script>
    // Function to handle bill button click
    function createInvoice(projectId, invoiceNumber, orderItemIds) {
        // Construct the URL with the project ID, invoice number, and order item IDs
        var url = "{{ route('createInvoice') }}?projectId=" + projectId + "&invoiceNumber=" + invoiceNumber + "&orderItemIds=" + orderItemIds.join(',');
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
    }

    // Add event listener to the select all checkbox in the table head
    var selectAllCheckbox = document.getElementById('selectAllCheckbox');
    selectAllCheckbox.addEventListener('change', handleCheckboxChange);

    // Add event listener to each checkbox in the table body
    document.querySelectorAll('.orderItemCheckbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function(event) {
            var isChecked = event.target.checked;
            var orderId = event.target.dataset.orderitemId;
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

        // Calculate total
        var total = quantity * unitPrice;
        totalInput.value = total;
    }
</script>

@endsection

