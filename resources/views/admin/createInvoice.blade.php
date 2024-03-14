@extends('layouts.masterAdmin')
@section('content')

<style>
    .body { box-sizing: border-box; height: 11in; margin: 0 auto; overflow: hidden; padding: 0.5in; width: 8.5in; }
    .body { background: #FFF; border-radius: 1px; box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5); }
</style>

<!-- Page Content-->
<div class="page-content">
    <div class="container-fluid">
        <div class="body" id="invoice-page">

            <div class="row">
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
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6">
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
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card-body" style="display: grid; justify-content: flex-end;">
                                    <table>
                                        <tr>
                                            <th><span contenteditable>Invoice No: </span></th>
                                            <td><span>{{ $invoiceNumber }}</span></td>
                                        </tr>
                                        <tr>
                                            <th><span contenteditable>Date: </span></th>
                                            <td><span>{{ $current->format('d M Y') }}</span></td>
                                        </tr>
                                        <tr>
                                            <th><span contenteditable>Total: </span></th>
                                            <td>
                                                {{-- <span id="totalBill">0.00</span> --}}
                                                <input type="text" id="totalBill" name="total_bill" style="border: white; color: #303e67" value="">
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
                            {{-- <span class="float-right mb-3">
                                <button type="button" class="btn btn-secondary">Insert New Order</button>
                            </span> --}}
                            <div class="table-responsive">
                                <table class="table table-bordered" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th style="display: none;">Order ID</th>
                                            <th>Item</th>
                                            <th>Description</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Price</th>

                                        </tr>
                                    </thead>
                                    <tbody id="invoiceItems">
                                        {{-- @foreach($orderItems as $index => $orderItem)
                                            <tr>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-soft-success btn-circle">
                                                        <i class="dripicons-plus"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-soft-danger btn-circle">
                                                        <i class="dripicons-minus"></i>
                                                    </button>
                                                </td>
                                                <td style="display: none;">
                                                    <span contenteditable>{{ $orderItem->id }}</span>
                                                    <input type="hidden" name="orderItems[{{ $index }}][orderitemid]" value="{{ $orderItem->id }}">
                                                </td>
                                                <td>
                                                    <span contenteditable>{{ $orderItem->order_item }}</span>
                                                    <input type="hidden" name="orderItems[{{ $index }}][item]" value="{{ $orderItem->order_item }}">
                                                </td>
                                                <td>
                                                    <span contenteditable>{{ $orderItem->order_description }}</span>
                                                    <input type="hidden" name="orderItems[{{ $index }}][description]" value="{{ $orderItem->order_description }}">
                                                </td>
                                                <td>
                                                    <span contenteditable>{{ $orderItem->unit_price }}</span>
                                                    <input type="hidden" name="orderItems[{{ $index }}][rate]" value="{{ $orderItem->unit_price }}">
                                                </td>
                                                <td>
                                                    <span contenteditable>{{ $orderItem->order_quantity }}</span>
                                                    <input type="hidden" name="orderItems[{{ $index }}][quantity]" value="{{ $orderItem->order_quantity }}">
                                                </td>
                                                <td>
                                                    <span contenteditable>{{ $orderItem->total_price }}</span>
                                                    <input type="hidden" name="orderItems[{{ $index }}][price]" value="{{ $orderItem->total_price }}">
                                                </td>
                                            </tr>
                                        @endforeach --}}
                                    </tbody>
                                </table><!--end /table-->
                                <span class="float-right">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </span>
                            </div><!--end /tableresponsive-->
                        </div><!--end card-body-->
                    </div><!--end col-->
                </div><!--end row-->
            </form>
        </div>

    </div>
</div>
<!-- end page content -->

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

{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        var tbody = document.getElementById('invoiceItems');

        // Function to load order items using AJAX
        function loadOrderItems() {
            fetch('{{ route("loadOrderItems") }}?orderItemIds={{ $orderItemIds }}&projectId={{ $projectId }}&invoiceNumber={{ $invoiceNumber }}')
                .then(response => response.json())
                .then(data => {
                    // Clear existing rows
                    tbody.innerHTML = '';

                    // Iterate over received data and populate the table
                    data.forEach(orderItem => {
                        var newRow = document.createElement('tr');
                        newRow.innerHTML = `
                            <td>
                                <button type="button" class="btn btn-sm btn-soft-success btn-circle">
                                    <i class="dripicons-plus"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-soft-danger btn-circle">
                                    <i class="dripicons-minus"></i>
                                </button>
                            </td>
                            <td style="display: none;">
                                <span contenteditable>${orderItem.id}</span>
                                <input type="hidden" name="orderItems[${orderItem.id}][orderitemid]" value="${orderItem.id}">
                            </td>
                            <td>
                                <span contenteditable>${orderItem.order_item}</span>
                                <input type="hidden" name="orderItems[${orderItem.id}][item]" value="${orderItem.order_item}">
                            </td>
                            <td>
                                <span contenteditable>${orderItem.order_description}</span>
                                <input type="hidden" name="orderItems[${orderItem.id}][description]" value="${orderItem.order_description}">
                            </td>
                            <td>
                                <span contenteditable>${orderItem.unit_price}</span>
                                <input type="hidden" name="orderItems[${orderItem.id}][rate]" value="${orderItem.unit_price}">
                            </td>
                            <td>
                                <span contenteditable>${orderItem.order_quantity}</span>
                                <input type="hidden" name="orderItems[${orderItem.id}][quantity]" value="${orderItem.order_quantity}">
                            </td>
                            <td>
                                <span contenteditable>${orderItem.total_price}</span>
                                <input type="hidden" name="orderItems[${orderItem.id}][price]" value="${orderItem.total_price}">
                            </td>
                        `;
                        tbody.appendChild(newRow);
                    });
                })
                .catch(error => {
                    console.error('Error loading order items:', error);
                });
        }

        // Call the loadOrderItems function to load data on page load
        loadOrderItems();
    });
</script> --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tbody = document.getElementById('invoiceItems');

        // Function to load order items using AJAX
        function loadOrderItems() {
            fetch('{{ route("loadOrderItems") }}?orderItemIds={{ $orderItemIds }}&projectId={{ $projectId }}&invoiceNumber={{ $invoiceNumber }}')
                .then(response => response.json())
                .then(data => {
                    // Clear existing rows
                    tbody.innerHTML = '';

                    // Iterate over received data and populate the table
                    data.forEach(orderItem => {
                        var newRow = createRow(orderItem);
                        tbody.appendChild(newRow);
                    });

                    // Update the total bill after loading order items
                    updateTotalBill();
                })
                .catch(error => {
                    console.error('Error loading order items:', error);
                });
        }


        // // Function to create a new row with order item data
        // function createRow(orderItem) {
        //     var newRow = document.createElement('tr');
        //     newRow.innerHTML = `
        //         <td>
        //             <button type="button" class="btn btn-sm btn-soft-success btn-circle add-row">
        //                 <i class="dripicons-plus"></i>
        //             </button>
        //             <button type="button" class="btn btn-sm btn-soft-danger btn-circle remove-row">
        //                 <i class="dripicons-minus"></i>
        //             </button>
        //         </td>
        //         <td style="display: none;">
        //             <span contenteditable>${orderItem.id}</span>
        //             <input type="hidden" name="${orderItem.id ? 'orderItems[' + orderItem.id + '][orderitemid]' : 'newOrderItems[][orderitemid]'}" value="${orderItem.id}">
        //         </td>
        //         <td>
        //             <input type="text" class="w-100" style="border: white; color: #303e67" name="${orderItem.id ? 'orderItems[' + orderItem.id + '][item]' : 'newOrderItems[][item]'}" value="${orderItem.order_item}">
        //         </td>
        //         <td>
        //             <input type="text" class="w-100" style="border: white; color: #303e67" name="${orderItem.id ? 'orderItems[' + orderItem.id + '][description]' : 'newOrderItems[][description]'}" value="${orderItem.order_description}">
        //         </td>
        //         <td>
        //             <input type="number" class="w-100" style="border: white; color: #303e67" name="${orderItem.id ? 'orderItems[' + orderItem.id + '][rate]' : 'newOrderItems[][rate]'}" value="${orderItem.unit_price}">
        //         </td>
        //         <td>
        //             <input type="number" class="w-100" style="border: white; color: #303e67" name="${orderItem.id ? 'orderItems[' + orderItem.id + '][quantity]' : 'newOrderItems[][quantity]'}" value="${orderItem.order_quantity}">
        //         </td>
        //         <td>
        //             <input type="number" class="w-100" style="border: white; color: #303e67" id="totalPrice" name="${orderItem.id ? 'orderItems[' + orderItem.id + '][price]' : 'newOrderItems[][price]'}" value="${orderItem.total_price}" readonly>
        //         </td>
        //     `;

        //     // Add event listeners to the buttons in the new row
        //     newRow.querySelector('.add-row').addEventListener('click', addRow);
        //     newRow.querySelector('.remove-row').addEventListener('click', removeRow);

        //     return newRow;
        // }

        // // Function to add a new row
        // function addRow() {
        //     var newRow = createRow({
        //         id: '', // You can assign a new ID for the new row if needed
        //         order_item: 'New Item',
        //         order_description: 'New Description',
        //         order_quantity: '0.00',
        //         unit_price: '1',
        //         total_price: '0.00'
        //     });
        //     document.getElementById('invoiceItems').appendChild(newRow);
        // }

        // Function to add a new row
        function addRow() {
            var newRow = createRow({
                orderitemid: null,
                order_item: 'New Item',
                order_description: 'New Description',
                unit_price: '1',
                order_quantity: '0.00',
                total_price: '0.00'
            });
            tbody.appendChild(newRow);
        }

        // Function to create a new row with order item data
        function createRow(orderItem) {
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
                <td style="display: none;">
                    <span contenteditable>${orderItem.id}</span>
                    <input type="hidden" name="orderItems[${orderItem.id}][orderitemid]" value="${orderItem.id}">
                </td>
                <td>
                    <span contenteditable>${orderItem.order_item}</span>
                    <input type="hidden" name="orderItems[${orderItem.id}][item]" value="${orderItem.order_item}">
                </td>
                <td>
                    <span contenteditable>${orderItem.order_description}</span>
                    <input type="hidden" name="orderItems[${orderItem.id}][description]" value="${orderItem.order_description}">
                </td>
                <td>
                    <input type="number" class="w-100" style="border: white; color: #303e67" name="orderItems[${orderItem.id}][rate]" value="${orderItem.unit_price}">
                </td>
                <td>
                    <input type="number" class="w-100" style="border: white; color: #303e67" name="orderItems[${orderItem.id}][quantity]" value="${orderItem.order_quantity}">
                </td>
                <td>
                    <input type="number" class="w-100" style="border: white; color: #303e67" id="totalPrice" name="orderItems[${orderItem.id}][price]" value="${orderItem.total_price}" readonly>
                </td>
            `;

            // Add event listeners to the buttons in the new row
            newRow.querySelector('.add-row').addEventListener('click', addRow);
            newRow.querySelector('.remove-row').addEventListener('click', removeRow);

            // Add event listeners to quantity and rate fields for auto calculation
            newRow.querySelector('[name^="orderItems["][name$="[quantity]"]').addEventListener('input', calculateTotalPrice);
            newRow.querySelector('[name^="orderItems["][name$="[rate]"]').addEventListener('input', calculateTotalPrice);

            return newRow;
        }


        // Function to remove the current row
        function removeRow() {
            this.closest('tr').remove();
        }



        // Function to calculate total price
        function calculateTotalPrice() {
            console.log('calculateTotalPrice function called');
            var row = this.closest('tr');
            var quantity = parseFloat(row.querySelector('[name^="orderItems["][name$="[quantity]"]').value);
            var unitPrice = parseFloat(row.querySelector('[name^="orderItems["][name$="[rate]"]').value);
            var totalPrice = quantity * unitPrice;

            // console.log('Quantity:', quantity);
            // console.log('Unit Price:', unitPrice);
            // console.log('Total Price:', totalPrice);


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

            // document.getElementById('totalBill').textContent = 'RM' + total.toFixed(2);
            document.getElementById('totalBill').value = 'RM' + total.toFixed(2);
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

        // Load order items
        loadOrderItems();
    });
</script>









@endsection
