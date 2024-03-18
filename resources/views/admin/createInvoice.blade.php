@extends('layouts.masterAdmin')
@section('content')

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>



<style id="bodyStyle">
    .body { box-sizing: border-box; height: 11in; margin: 0 auto; overflow: hidden; padding: 0.5in; width: 8.5in; }
    .body { background: #FFF; border-radius: 1px; box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5); }

    @media print {
        .no-print {
            display: none;
        }
        /* Example: Adjust font size for printing */
        h1 {
            font-size: 24px;
        }
    }
</style>

<!-- Page Content-->
<div class="page-content">
    <div class="container-fluid">
        {{-- <div class="body" id="contentToPrint">
            <div class="box">
                <div class="row box-1">
                    <div class="col-lg-12">
                        <div class="card-body">
                            <p class="page-title">
                                {{ $user->name }}
                            </p>
                            <p class="page-title">
                                Current Tech Industries Sdn. Bhd.
                            </p>
                            <p class="page-title">
                                {{ $user->phone_number }}
                            </p>
                        </div>
                    </div>
                </div>

                <form id="invoiceForm" action="{{ route('addInvoice') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="box-5">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row box-2">
                                    <div class="col-lg-6 box-3">
                                        <div class="card-body">
                                            <p class="page-title">
                                                {{ $project->project_owner }}
                                            </p>
                                            <p class="page-title">
                                                {{ $project->project_name }}
                                            </p>
                                            <p class="page-title">
                                                {{ $project->project_telno }}
                                            </p>
                                            <p class="page-title" style="display: none;">
                                                <input type="text" name="project_id" value="{{ $project->id }}">
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 box-4">
                                        <div class="card-body" style="display: grid; justify-content: flex-end;">
                                            <table>
                                                <tr>
                                                    <th><span contenteditable>Invoice No: </span></th>
                                                    <td>
                                                        <input type="text" name="invoice_number" style="border: white; color: #303e67" value="{{ $invoiceNumber }}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><span contenteditable>Date: </span></th>
                                                    <td><span style="color: #303e67;">{{ $current->format('d M Y') }}</span></td>
                                                </tr>
                                                <tr>
                                                    <th><span contenteditable>Total (RM): </span></th>
                                                    <td>
                                                        <input type="text" id="totalBill" name="total_bill" style="border: white; color: #303e67" value="{{$totalPriceSum}}">
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-body">
                                    <span class="float-right mb-3 no-print">
                                        <button type="button" class="btn btn-secondary" id="addOrderItem">Insert New Order</button>
                                    </span>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th class="no-print"></th>
                                                    <th style="display: none;">Order ID</th>
                                                    <th>Item</th>
                                                    <th>Description</th>
                                                    <th>Quantity</th>
                                                    <th>Unit Price</th>
                                                    <th>Price</th>
                                                    <th style="display: none;">Project ID</th>
                                                </tr>
                                            </thead>
                                            <tbody id="invoiceItems">

                                            </tbody>
                                        </table><!--end /table-->
                                        <span class="float-right no-print">
                                            <button type="button" class="btn btn-warning" id="exportButton">Download</button>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </span>
                                    </div><!--end /tableresponsive-->
                                </div><!--end card-body-->
                            </div><!--end col-->
                        </div><!--end row-->
                    </div>
                </form>
            </div>
        </div> --}}

        <div class="body" id="contentToPrint">
            <div class="box">
                <div class="row box-1">
                    <div class="col-lg-12">
                        <div class="card-body">
                            <p class="page-title">
                                {{ $user->name }}
                                <br>
                                Current Tech Industries Sdn. Bhd.
                                <br>
                                VO60308, Signature 2, Lingkaran SV, Sunway Velocity,
                                <br>
                                55100 Cheras, Federal Territory of Kuala Lumpur
                                <br>
                                {{ $user->phone_number }}
                            </p>
                        </div>
                    </div>
                </div>

                <form id="invoiceForm">
                {{-- <form id="invoiceForm" action="{{ route('addInvoice') }}" method="POST" enctype="multipart/form-data"> --}}
                    @csrf
                    <div class="box-5">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row box-2">
                                    <div class="col-lg-6 box-3">
                                        <div class="card-body">
                                            <p class="page-title">
                                                {{ $project->project_owner }}
                                                <br>
                                                {{ $project->project_name }}
                                                <br>
                                                {{ $project->project_address }}
                                                <br>
                                                {{ $project->project_telno }}
                                            </p>

                                            <p class="page-title" style="display: none;">
                                                <input type="text" name="project_id" value="{{ $project->id }}">
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 box-4">
                                        <div class="card-body" style="display: grid; justify-content: flex-end;">
                                            <table>
                                                <tr>
                                                    <th><span contenteditable>Invoice No: </span></th>
                                                    <td>
                                                        <input type="text" name="invoice_number" style="border: white; color: #303e67" value="{{ $invoiceNumber }}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><span contenteditable>Date: </span></th>
                                                    <td><span style="color: #303e67;">{{ $current->format('d M Y') }}</span></td>
                                                </tr>
                                                <tr>
                                                    <th><span contenteditable>Terms: </span></th>
                                                    <td>
                                                        <input type="text" name="terms" style="border: white; color: #303e67" value="COD">
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-body">
                                    <span class="float-right mb-3 no-print">
                                        <button type="button" class="btn btn-secondary" id="addOrderItem">Insert New Order</button>
                                    </span>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th class="no-print"></th>
                                                    <th style="display: none;">Order ID</th>
                                                    <th>Item</th>
                                                    <th>Description</th>
                                                    <th>Quantity</th>
                                                    <th>Unit Price</th>
                                                    <th>Price</th>
                                                    <th style="display: none;">Project ID</th>
                                                </tr>
                                            </thead>
                                            <tbody id="invoiceItems">

                                            </tbody>
                                        </table><!--end /table-->
                                        <span class="float-right no-print">
                                            {{-- @if ($existingInvoice == null) --}}
                                                <button type="button" class="btn btn-primary" id="exportButton">Generate and Save</button>
                                            {{-- @endif --}}

                                            {{-- <button type="button" class="btn btn-primary" id="exportButton">Generate and Save</button> --}}

                                            {{-- @if ($existingInvoice)
                                                <button type="button" class="btn btn-warning" id="exportButton">Download</button>
                                            @endif --}}

                                            {{-- <button type="button" class="btn btn-warning" id="exportButton">Download</button>

                                            <button type="submit" class="btn btn-primary">Submit</button> --}}
                                        </span>
                                    </div><!--end /tableresponsive-->
                                </div><!--end card-body-->
                            </div><!--end col-->
                        </div><!--end row-->
                    </div>
                    <div class="box-5">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row" style="display:flex; justify-content:flex-end;">
                                    <div class="col-lg-6">
                                        <div class="card-body" style="display: grid; justify-content: flex-end;">
                                            <table>
                                                <tr>
                                                    <th><span contenteditable>Total (RM): </span></th>
                                                    <td>
                                                        <input type="text" id="totalBill" name="total_bill" style="border: white; color: #303e67" value="{{ $totalPriceSum }}" readonly>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><span contenteditable>Discount (%): </span></th>
                                                    <td>
                                                        <input type="text" name="discount" id="discount" style="border: white; color: #303e67" autocomplete="off" value="{{ $discount }}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><span contenteditable>Grand Total: </span></th>
                                                    <td>
                                                        <input type="text" id="grandTotal" name="grand_total" style="border: white; color: #303e67" value="{{ $grandTotal }}" readonly>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<!-- end page content -->

{{-- Add Order Item --}}
<div id="addOrderItemModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Add Order Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="title-name">Order</label>
                            <select class="form-control" name="role_id" id="orderItemIdSelect">
                                @foreach($unselectedOrderItems as $item)
                                    <option value="{{ $item->id }}"
                                            data-item="{{ $item->order_item }}"
                                            data-description="{{ $item->order_description }}"
                                            data-rate="{{ $item->unit_price }}"
                                            data-quantity="{{ $item->order_quantity }}"
                                            data-price="{{ $item->total_price }}">
                                        {{ $item->order_item }} - {{ $item->order_description }}
                                    </option>
                                @endforeach
                            </select>
                            @error('title_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-12 text-right">
                    <button type="button" class="btn btn-primary" id="submitOrderItem">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tbody = document.getElementById('invoiceItems');
        var addOrderItemModal = document.getElementById("addOrderItemModal");
        var addOrderItemButton = document.getElementById("addOrderItem");
        var orderItemIdSelect = document.getElementById("orderItemIdSelect");
        var submitOrderItemButton = document.getElementById("submitOrderItem");

        addOrderItemButton.addEventListener("click", function() {
            $('#addOrderItemModal').modal('show');
        });

        submitOrderItemButton.addEventListener("click", function() {
            var selectedOption = orderItemIdSelect.options[orderItemIdSelect.selectedIndex];
            var orderId = selectedOption.value;
            var orderItem = selectedOption.dataset.item;
            var orderDescription = selectedOption.dataset.description;
            var unitPrice = selectedOption.dataset.rate;
            var quantity = selectedOption.dataset.quantity;
            var price = selectedOption.dataset.price;

            var newRow = createRow({
                id: orderId,
                order_item: orderItem,
                order_description: orderDescription,
                unit_price: unitPrice,
                order_quantity: quantity,
                total_price: price,
                project_id: '{{ $projectId }}' // Assign the projectId here
            });
            tbody.appendChild(newRow);

            // Update the total bill after adding the new row
            updateTotalBill();

            // Hide the modal after adding the order item
            $('#addOrderItemModal').modal('hide');
        });

        var tbody = document.getElementById('invoiceItems');

        // Function to load order items using AJAX
        function loadOrderItems() {
            fetch('{{ route("loadOrderItems") }}?orderItemIds={{ $orderItemIds }}&projectId={{ $projectId }}&invoiceNumber={{ $invoiceNumber }}')
                .then(response => response.json())
                .then(data => {
                    // Clear existing rows
                    tbody.innerHTML = '';

                    // Check if the received data contains the orderItems property
                    if (data.hasOwnProperty('orderItems')) {
                        // Iterate over orderItems and populate the table
                        data.orderItems.forEach(orderItem => {
                            var newRow = createRow(orderItem, {{ $projectId }});
                            tbody.appendChild(newRow);
                        });
                    } else {
                        console.error('Received data does not contain orderItems property:', data);
                    }

                    // Update the total bill after loading order items
                    updateTotalBill();
                })
                .catch(error => {
                    console.error('Error loading order items:', error);
                });
        }

        // Function to add a new row
        function addRow(projectId) {
            console.log('Project Id: ',projectId);
            var newRow = createRow({
                orderitemid: null,
                order_item: 'New Item',
                order_description: 'New Description',
                unit_price: '1',
                order_quantity: '0.00',
                total_price: '0.00',
                project_id: projectId, // Assign the projectId here
            });
            tbody.appendChild(newRow);
        }

        // Function to create a new row with order item data
        function createRow(orderItem, projectId) {
            var newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td class="no-print">
                    <div style="display: flex; justify-content: center; gap: 10px;">
                        <button type="button" class="btn btn-sm btn-soft-success btn-circle add-row">
                            <i class="dripicons-plus"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-soft-danger btn-circle remove-row">
                            <i class="dripicons-minus"></i>
                        </button>
                    </div>

                </td>
                <td style="display: none;">
                    <span contenteditable style="color: #303e67;">${orderItem.id}</span>
                    <input type="hidden" name="orderItems[${orderItem.id}][orderitemid]" value="${orderItem.id}">
                </td>
                <td>
                    <span contenteditable style="color: #303e67;">${orderItem.order_item}</span>
                    <input type="hidden" name="orderItems[${orderItem.id}][item]" value="${orderItem.order_item}">
                </td>
                <td>
                    <span contenteditable style="color: #303e67;">${orderItem.order_description}</span>
                    <input type="hidden" name="orderItems[${orderItem.id}][description]" value="${orderItem.order_description}">
                </td>
                <td>
                    <input type="number" class="w-100" style="border: white; color: #303e67;" name="orderItems[${orderItem.id}][rate]" value="${orderItem.unit_price}">
                </td>
                <td>
                    <input type="number" class="w-100" style="border: white; color: #303e67;" name="orderItems[${orderItem.id}][quantity]" value="${orderItem.order_quantity}">
                </td>
                <td>
                    <input type="number" class="w-100" style="border: white; color: #303e67;" id="totalPrice" name="orderItems[${orderItem.id}][price]" value="${orderItem.total_price}" readonly>
                </td>
                <td style="display: none;">
                    <span contenteditable>${projectId}</span>
                    <input type="hidden" name="orderItems[${orderItem.id}][projectid]" value="${projectId}">
                </td>
            `;


            // Add event listeners to the buttons in the new row
            newRow.querySelector('.add-row').addEventListener('click', function() {
                addRow(projectId); // Call addRow with projectId when clicked
            });
            newRow.querySelector('.remove-row').addEventListener('click', removeRow);

            // Add event listeners to quantity and rate fields for auto calculation
            newRow.querySelector('[name^="orderItems["][name$="[quantity]"]').addEventListener('input', calculateTotalPrice);
            newRow.querySelector('[name^="orderItems["][name$="[rate]"]').addEventListener('input', calculateTotalPrice);

            return newRow;
        }

        // Function to remove the current row
        function removeRow() {
            this.closest('tr').remove();
            updateTotalBill();
        }

        // Function to calculate total price
        function calculateTotalPrice() {
            console.log('calculateTotalPrice function called');
            var row = this.closest('tr');
            var quantity = parseFloat(row.querySelector('[name^="orderItems["][name$="[quantity]"]').value);
            var unitPrice = parseFloat(row.querySelector('[name^="orderItems["][name$="[rate]"]').value);
            var totalPrice = quantity * unitPrice;

            var totalPriceInput = row.querySelector('[name^="orderItems["][name$="[price]"]');
            totalPriceInput.value = totalPrice.toFixed(2);

            updateTotalBill();
        }

        // Function to update total bill
        function updateTotalBill() {
            var total = 0;
            var totalPriceInputs = document.querySelectorAll('input[name^="orderItems["][name$="[price]"]');

            totalPriceInputs.forEach(function(input) {
                var price = parseFloat(input.value);
                if (!isNaN(price)) { // Check if the parsed price is a valid number
                    total += price;
                }
            });

            // Update the totalBill input field with the latest total value
            document.getElementById('totalBill').value = total.toFixed(2);

            // Get the discount value
            var discount = parseFloat(document.querySelector('input[name="discount"]').value);

            // Check if the discount is a valid number
            if (isNaN(discount)) {
                discount = 0; // Set discount to 0 if it's not a valid number
            }

            // Calculate the grandTotal
            var grandTotal = total - (total * (discount / 100));

            // Check if the grandTotal is a valid number
            if (isNaN(grandTotal)) {
                grandTotal = 0; // Set grandTotal to 0 if it's not a valid number
            }

            // Update the grandTotal input field
            document.getElementById('grandTotal').value = grandTotal.toFixed(2);
        }

        var discountInput = document.querySelector('input[name="discount"]');

        // Add event listener to the discount input field
        discountInput.addEventListener('input', function() {
            // Update the totalBill input field with the latest total value
            updateTotalBill();
        });

        // Find the form
        var form = document.getElementById('invoiceForm');

        // Add event listener to the form's submit event
        form.addEventListener('submit', function (event) {
            // Find all the rows in the table body
            var rows = document.querySelectorAll('#invoiceItems tr');

            // Loop through each row
            rows.forEach(function (row, index) {
                // Find the contenteditable elements in the row
                var contentEditableElements = row.querySelectorAll('[contenteditable]');

                // Loop through each contenteditable element
                contentEditableElements.forEach(function (element) {
                    // Get the corresponding input field for the group
                    var hiddenInput = element.parentElement.querySelector('input[type="hidden"]');

                    // Update the value of the input field with the current contenteditable element's value
                    hiddenInput.value = element.textContent.trim();
                });
            });
        });

        // Load order items
        loadOrderItems();
    });
</script>

{{-- Print --}}

{{-- Title = CT Ticketing --}}
{{-- <script>
    // Print contentToPrint or allContentToPrint based on user selection
    document.getElementById("exportButton").addEventListener("click", function() {
        PrintElem("contentToPrint");
    });

    function PrintElem(elem) {
        var mywindow = window.open('', 'PRINT', 'height=400,width=600');
        // mywindow.document.write('<html><head><title>' + document.title + '</title>');
            mywindow.document.write('<html><head>');

        // Create a style element and set its content to the specified CSS styles
        var style = document.createElement('style');
        style.textContent = `
            .body { box-sizing: border-box; height: 11in; margin: 0 auto; overflow: hidden; padding: 0.5in; width: 8.5in; gap:5px;}
            .body { background: #FFF; border-radius: 1px; box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5); }

            .card-body {
                margin: 0;
                padding: 10px;
            }

            .page-title {
                margin: 0;
                font-size: 18px;
            }

            p {
                line-height: 1.6;
                font-size: .8125rem;
                font-weight: 400;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th, td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            .float-right {
                float: right;
            }

            .no-print {
                display: none;
            }

            input {
                border: white;
                width: 100%;
            }

            .box {
                display: flex;
                flex-direction: column;
                gap: 50px;
            }

            .box-1 {
                width: 100%;
            }

            .box-2 {
                width: 100%;
                display: flex;
            }

            .box-3 {
                width: 50%;
                display: flex;
            }

            .box-4 {
                width: 50%;
                display: flex;
                justify-content: flex-end;"
            }

            .box-5 {
                display: flex;
                flex-direction: column;
                gap: 50px;
            }

        `;
        mywindow.document.head.appendChild(style);

        mywindow.document.write('</head><body>');
        mywindow.document.write(document.getElementById(elem).innerHTML);
        mywindow.document.write('</body></html>');

        // Wait for images to load before printing
        var images = mywindow.document.getElementsByTagName('img');
        if (images.length === 0) { // If no images, proceed to print
            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10
            mywindow.print();
            mywindow.close();
            return;
        }

        var loaded = 0;
        for (var i = 0; i < images.length; i++) {
            images[i].onload = function() {
                loaded++;
                if (loaded === images.length) {
                    mywindow.document.close(); // necessary for IE >= 10
                    mywindow.focus(); // necessary for IE >= 10
                    mywindow.print();
                    mywindow.close();
                }
            };
        }
        return true;
    }

</script> --}}


{{-- Title = Invoice --}}
{{-- <script>
    // Print contentToPrint or allContentToPrint based on user selection
    document.getElementById("exportButton").addEventListener("click", function() {
        PrintElem("contentToPrint");
    });

    function PrintElem(elem) {
        var mywindow = window.open('', 'PRINT', 'height=400,width=600');
        mywindow.document.write('<html><head>');

        // Set the title for the new window
        mywindow.document.title = "Invoice";

        // Create a style element and set its content to the specified CSS styles
        var style = document.createElement('style');
        style.textContent = `
            .body { box-sizing: border-box; height: 11in; margin: 0 auto; overflow: hidden; padding: 0.5in; width: 8.5in; gap:5px;}
            .body { background: #FFF; border-radius: 1px; box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5); }

            .card-body {
                margin: 0;
                padding: 10px;
            }

            .page-title {
                margin: 0;
                font-size: 18px;
            }

            p {
                line-height: 1.6;
                font-size: .8125rem;
                font-weight: 400;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th, td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }


            .float-right {
                float: right;
            }

            .no-print {
                display: none;
            }

            input {
                border: white;
                width: 100%;
            }

            .box {
                display: flex;
                flex-direction: column;
                gap: 30px;
            }

            .box-1 {
                width: 100%;
            }

            .box-2 {
                width: 100%;
                display: flex;
            }

            .box-3 {
                width: 50%;
                display: flex;
            }

            .box-4 {
                width: 50%;
                display: flex;
                justify-content: flex-end;
            }

            .box-5 {
                display: flex;
                flex-direction: column;
                gap: 30px;
            }

        `;
        mywindow.document.head.appendChild(style);
        mywindow.document.write('</head><body>');
        mywindow.document.write(document.getElementById(elem).innerHTML);
        mywindow.document.write('</body></html>');

        // Wait for images to load before printing
        var images = mywindow.document.getElementsByTagName('img');
        if (images.length === 0) { // If no images, proceed to print
            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10
            mywindow.print();
            mywindow.close();
            return;
        }

        var loaded = 0;
        for (var i = 0; i < images.length; i++) {
            images[i].onload = function() {
                loaded++;
                if (loaded === images.length) {
                    mywindow.document.close(); // necessary for IE >= 10
                    mywindow.focus(); // necessary for IE >= 10
                    mywindow.print();
                    mywindow.close();
                }
            };
        }
        return true;
    }
</script> --}}


<script>
    document.getElementById("exportButton").addEventListener("click", function() {

         // Retrieve form data
        var formData = new FormData(document.getElementById("invoiceForm"));

        // Send form data to the server
        fetch('{{ route("addInvoice") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            // Handle success response
            var totalBill = parseFloat(document.getElementById('totalBill').value);
            var grandTotal = parseFloat(document.getElementById('grandTotal').value);
            var discountInput = document.getElementById('discount');
            var discount = parseFloat(discountInput.value);

            // Check if discount is NaN, if so, set it to 0
            if (isNaN(discount)) {
                discount = 0;
            }

            // Call the PrintElem function to generate and print the custom invoice
            PrintElem('contentToPrint', totalBill, grandTotal, discount);
        })
        .catch(error => {
            console.error('Error sending invoice data:', error);
        });
    });

    function PrintElem(elem, totalBill, grandTotal, discount) {
        var mywindow = window.open('', 'PRINT', 'height=400,width=600');
        mywindow.document.write('<html><head>');

        // Set the title for the new window
        mywindow.document.title = "Invoice";

        // Create a style element and set its content to the specified CSS styles
        var style = document.createElement('style');
        style.textContent = `
            .body { box-sizing: border-box; height: 12in; margin: 0 auto; overflow: hidden; padding: 0.5in; width: 9.5in; gap:5px;}
            .body { background: #FFF; border-radius: 1px; }

            .card-body {
                margin: 0;
                padding: 10px;
            }

            .page-title {
                margin: 0;
                font-size: 18px;
            }

            p {
                line-height: 1.6;
                font-size: .8125rem;
                font-weight: 400;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th, td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }


            .float-right {
                float: right;
            }

            .no-print {
                display: none;
            }

            input {
                border: white;
                width: 100%;
            }

            .box {
                display: flex;
                flex-direction: column;
                gap:20px;
            }

            .box-1 {
                width: 100%;
            }

            .box-2 {
                width: 100%;
                display: flex;
            }

            .box-3 {
                width: 50%;
                display: flex;
            }

            .box-4 {
                width: 50%;
                display: flex;
                justify-content: flex-end;
            }

            .box-5 {
                display: flex;
                flex-direction: column;
                gap:20px;
            }
        `;
        mywindow.document.head.appendChild(style);
        mywindow.document.write('</head><body>');

        // Write the HTML content of the invoice
        mywindow.document.write('<div class="body">');
        mywindow.document.write(document.getElementById(elem).innerHTML);

        // Update the values of total bill and grand total
        mywindow.document.getElementById('totalBill').value = totalBill.toFixed(2);
        mywindow.document.getElementById('grandTotal').value = grandTotal.toFixed(2);
        mywindow.document.getElementById('discount').value = discount.toFixed(2);

        // Find all rate, quantity, and total_price inputs and update their values in the printed invoice
        var rateInputs = document.querySelectorAll('input[name^="orderItems["][name$="[rate]"]');
        var quantityInputs = document.querySelectorAll('input[name^="orderItems["][name$="[quantity]"]');
        var totalPriceInputs = document.querySelectorAll('input[name^="orderItems["][name$="[price]"]');

        rateInputs.forEach(function(input) {
            var id = input.getAttribute('name').match(/\[(.*?)\]/)[1];
            mywindow.document.querySelector('input[name="orderItems[' + id + '][rate]"]').value = input.value;
        });

        quantityInputs.forEach(function(input) {
            var id = input.getAttribute('name').match(/\[(.*?)\]/)[1];
            mywindow.document.querySelector('input[name="orderItems[' + id + '][quantity]"]').value = input.value;
        });

        totalPriceInputs.forEach(function(input) {
            var id = input.getAttribute('name').match(/\[(.*?)\]/)[1];
            mywindow.document.querySelector('input[name="orderItems[' + id + '][price]"]').value = input.value;
        });

        mywindow.document.write('</div>');

        mywindow.document.write('</body></html>');

        // Print the window
        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10
        mywindow.print();
        mywindow.close();

        return true;
    }

</script>

{{-- If use controller to display data --}}
{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Function to add event listener to plus icon button
        function addPlusButtonListener(button) {
            button.addEventListener('click', function () {
                var tbody = button.closest('tbody');
                var newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td>
                        <button type="button" class="btn btn-sm btn-soft-success btn-circle add-row">
                            <i class="dripicons-plus"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-soft-danger btn-circle remove-row">
                            <i class="dripicons-minus"></i>
                        </button>
                    </td>
                    <td>
                        <span contenteditable>New Item</span>
                        <input type="hidden" name="items[]" value="New Item" />
                    </td>
                    <td>
                        <span contenteditable>New Description</span>
                        <input type="hidden" name="descriptions[]" value="New Description" />
                    </td>
                    <td>
                        <span contenteditable>1</span>
                        <input type="hidden" name="quantities[]" value="1" />
                    </td>
                    <td>
                        <span contenteditable>0.00</span>
                        <input type="hidden" name="unit_prices[]" value="0.00" />
                    </td>
                    <td>0.00</td>
                `;
                // Recursively add event listener to plus and minus icon buttons in new row
                newRow.querySelectorAll('.add-row').forEach(addPlusButtonListener);
                newRow.querySelectorAll('.remove-row').forEach(addMinusButtonListener);
                tbody.appendChild(newRow);
            });
        }

        // Function to add event listener to minus icon button
        function addMinusButtonListener(button) {
            button.addEventListener('click', function () {
                var row = button.closest('tr');
                row.remove();
                calculateTotalPrice(); // Recalculate total price when a row is removed
            });
        }

        // Function to calculate total price
        function calculateTotalPrice() {
            console.log("calculateTotalPrice function called");
            var rows = document.querySelectorAll('#invoiceItems tr');

            rows.forEach(function (row) {
                var quantity = parseFloat(row.querySelector('[name^="orderItems["][name$="[quantity]"]').value);
                var unitPrice = parseFloat(row.querySelector('[name^="orderItems["][name$="[rate]"]').value);
                var totalPrice = quantity * unitPrice;
                console.log(quantity);
                row.querySelector('[name^="orderItems["][name$="[price]"]').value = totalPrice.toFixed(2);
                row.querySelector('td:last-child').textContent = totalPrice.toFixed(2);
            });
        }

        // Find the form
        var form = document.getElementById('invoiceForm');

        // Add event listener to the form's submit event
        form.addEventListener('submit', function (event) {
            // Find all the rows in the table body
            var rows = document.querySelectorAll('#invoiceItems tr');

            // Loop through each row
            rows.forEach(function (row, index) {
                // Find the contenteditable elements in the row
                var contentEditableElements = row.querySelectorAll('[contenteditable]');

                // Loop through each contenteditable element
                contentEditableElements.forEach(function (element) {
                    // Get the corresponding input field for the group
                    var hiddenInput = element.parentElement.querySelector('input[type="hidden"]');

                    // Update the value of the input field with the current contenteditable element's value
                    hiddenInput.value = element.textContent.trim();
                });
            });
        });

        // Find all the plus buttons
        var plusButtons = document.querySelectorAll('.btn-soft-success.btn-circle');

        // Add event listener to each plus button
        plusButtons.forEach(addPlusButtonListener);

        // Find all the minus buttons
        var minusButtons = document.querySelectorAll('.btn-soft-danger.btn-circle');

        // Add event listener to each minus button
        minusButtons.forEach(addMinusButtonListener);

        // Find all quantity and unit price inputs
        var quantityInputs = document.querySelectorAll('[name^="orderItems["][name$="[quantity]"]');
        var unitPriceInputs = document.querySelectorAll('[name^="orderItems["][name$="[rate]"]');

        // Add event listener to each quantity and unit price input to recalculate total price
        quantityInputs.forEach(function (input) {
            input.addEventListener('input', calculateTotalPrice);
        });

        unitPriceInputs.forEach(function (input) {
            input.addEventListener('input', calculateTotalPrice);
        });

    });
</script> --}}

@endsection
