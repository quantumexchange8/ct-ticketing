@extends('layouts.masterAdmin')
@section('content')



<!-- Page Content-->
<div class="page-content">
    <div class="container-fluid">
        <form id="invoiceForm" action="{{ route('addInvoice') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
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
                                        @foreach($orderItems as $index => $orderItem)
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
                                        @endforeach
                                    </tbody>
                                </table><!--end /table-->
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div><!--end /tableresponsive-->
                        </div><!--end card-body-->
                    </div><!--end card-->
                </div><!--end col-->
            </div><!--end row-->
        </form>
    </div>
</div>
<!-- end page content -->

{{-- <script>
    // Wait for the DOM content to be fully loaded
    document.addEventListener('DOMContentLoaded', function () {
        // Function to add event listener to plus icon button
        function addPlusButtonListener(button) {
            button.addEventListener('click', function () {
                var tbody = button.closest('tbody');
                var newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td><button type="button" class="btn btn-sm btn-soft-success btn-circle add-row"><i class="dripicons-plus"></i></button></td>
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


                // Recursively add event listener to plus icon button in new row
                newRow.querySelectorAll('.add-row').forEach(addPlusButtonListener);

                tbody.appendChild(newRow);
            });
        }

        // Find all the plus buttons
        var plusButtons = document.querySelectorAll('.btn-soft-success.btn-circle');

        // Add event listener to each plus button
        plusButtons.forEach(addPlusButtonListener);



        // Find the form
        var form = document.getElementById('invoiceForm');

        // Add event listener to the form's submit event
        form.addEventListener('submit', function (event) {
            // Find all the rows in the table body
            var rows = document.querySelectorAll('#invoiceItems tr');

            // Loop through each row
            rows.forEach(function (row) {
                // Find the contenteditable elements in the row
                var contentEditableElements = row.querySelectorAll('[contenteditable]');

                // Loop through each contenteditable element
                contentEditableElements.forEach(function (element) {
                    // Get the corresponding hidden input field
                    var hiddenInput = element.nextElementSibling;

                    // Update the value of the hidden input field with the current contenteditable element's value
                    hiddenInput.value = element.textContent.trim();
                });
            });
        });
    });

</script> --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Function to add event listener to plus icon button
    function addPlusButtonListener(button) {
        button.addEventListener('click', function () {
            var tbody = button.closest('tbody');
            var newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td><button type="button" class="btn btn-sm btn-soft-success btn-circle add-row"><i class="dripicons-plus"></i></button></td>
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
            // Recursively add event listener to plus icon button in new row
            newRow.querySelectorAll('.add-row').forEach(addPlusButtonListener);
            tbody.appendChild(newRow);
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
});

</script>



@endsection
