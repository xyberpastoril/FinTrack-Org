@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ __('Payment') }}</div>

                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group mb-3">
                                    <label for="enrolled_student_id">Enrolled Student</label>
                                    <select id="enrolled_student_id" class="form-control" placeholder="Select a student"
                                        name="enrolled_student_id">
                                        {{-- <option value="1">Graeme Xyber Pastoril</option> --}}
                                    </select>
                                </div>

                                <label>Required Fees</label>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <th>Fee</th>
                                            <th class="text-end">Amount</th>
                                            <th width="50px">Action</th>
                                        </thead>
                                        <tbody id="fees_list">
                                            <tr>
                                                <td colspan="3" class="text-center">No student selected ...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <label>Attendance Fines</label>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <th>Attendance Event</th>
                                            <th class="text-end">Amount</th>
                                            <th width="50px">Action</th>
                                        </thead>
                                        <tbody id="fines_list">
                                            {{-- <tr>
                                                <td>First General Assembly</td>
                                                <td>Time-In</td>
                                                <td class="text-end">20.00</td>
                                                <td>
                                                    <button class="btn btn-sm btn-success" disabled>Paid</button>

                                                </td>
                                            </tr>
                                            <tr>
                                                <td>First General Assembly</td>
                                                <td>Time-Out</td>
                                                <td class="text-end">20.00</td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary">Pay</button>
                                                </td>
                                            </tr> --}}
                                            <tr>
                                                <td colspan="4" class="text-center">No student selected ...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group mb-3">
                                    <label for="date">Date</label>
                                    <input type="date" id="date" class="form-control" name="date"
                                        value="{{ now()->format('Y-m-d') }}">
                                </div>

                                <label>Pending Transactions</label>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <th>Description</th>
                                            <th>Category</th>
                                            <th class="text-end">Amount</th>
                                            <th width="50px">Action</th>
                                        </thead>
                                        <tbody id="pending_transactions_list">
                                            {{-- <tr>
                                                <td>First General Assembly - Time-Out</td>
                                                <td>
                                                    <span class="badge bg-danger">Fine</span>
                                                </td>
                                                <td class="text-end">20.00</td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger">Remove</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Membership Fee</td>
                                                <td>
                                                    <span class="badge bg-success">Fee</span>
                                                </td>
                                                <td class="text-end">20.00</td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger">Remove</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>CET Shirt</td>
                                                <td>
                                                    <span class="badge bg-secondary">Other</span>
                                                </td>
                                                <td class="text-end">550.00</td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger">Remove</button>
                                                </td>
                                            </tr> --}}
                                            <tr>
                                                <td colspan="4" class="text-center">No pending transactions ...</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2" class="text-end">
                                                    <h3>Total</h3>
                                                </td>
                                                <td colspan="2" class="text-end">
                                                    <h3 class="total_amount">0.00</h3>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- buttons right -->
                                <div class="d-flex justify-content-end">
                                    <!-- proceed transaction button that props up a modal -->
                                    <button id="proceed_transaction_btn" type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#proceedTransactionModal" disabled="true">
                                        Proceed Transaction
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="proceedTransactionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="proceedTransactionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="proceedTransactionModalLabel">Tender Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="tender" action="" method="post">
                    @csrf
                    <div class="row mb-3">
                        <label for="total_amount" class="col-sm-4 col-form-label">Total Amount</label>
                        <div class="col-sm-8">
                            <p id="total_amount" class="text-end total_amount">0.00</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="amount_received" class="col-sm-4 col-form-label">Amount Received</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control text-end" id="amount_received">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <h3 class="col-sm-4">Change</h3>
                        <div class="col-sm-8">
                            <h3 id="amount_change" class="text-end">0.00</h3>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="tender" class="btn btn-primary">Issue Receipt</button>
            </div>
        </div>
    </div>
</div>

<script>
    var controller,
        transaction_items = [],
        amount_received = 0,
        amount_change = 0,
        total_amount = 0;

    const element = document.getElementById('enrolled_student_id'),
        singleXhrRemove = new Choices(element, {
            allowHTML: true,
            removeItemButton: true,
            searchPlaceholderValue: "Search for an enrolled student ...",
            noResultsText: 'No results found',
        });

    singleXhrRemove.setChoices(function (callback) {
        return fetch(
                '/ajax/students/enrolled/search/'
        )
        .then(function (res) {
            return res.json();
        })
        .then(function (data) {
            console.log(data);
            return data.map(function (release) {
                return {
                    value: release.value,
                    id_number: release.id_number,
                    first_name: release.first_name,
                    last_name: release.last_name,
                    degree_program: release.degree_program,
                    year_level: release.year_level,
                    label: release.label
                };
            });
        });
    });

    singleXhrRemove.passedElement.element.addEventListener(
        'change',
        function (event) {
            console.log("change")

            // get the parent of the element, then get the child of the parent which is .choices__list.choices_list--single
            // document.parentNode, then get the length of the childNodes
            console.log(element.parentElement.childNodes[1].childNodes.length);
            let hasValue = element.parentElement.childNodes[1].childNodes.length > 0;

            if(hasValue) {
                console.log("choice")
                console.log(event.detail.value);

                // fetch student fees' data
                fetch(`/ajax/students/enrolled/${event.detail.value}/fees`)
                .then(function (res) {
                    return res.json();
                })
                .then(function(data){
                    console.log(data);

                    // populate fees_list
                    var fees_list = document.getElementById("fees_list");
                    fees_list.innerHTML = "";

                    data.forEach(function(fee){
                        var tr = document.createElement("tr");
                        tr.innerHTML = `
                            <td>${fee.name}</td>
                            <td class="text-end">${parseFloat(fee.amount).toFixed(2)}</td>
                            <td>
                                ${fee.is_paid ? `<button type="button" class="btn btn-sm btn-success" disabled>Paid</button>` : `<button type="button" class="btn btn-sm btn-primary" data-id="${fee.id}" data-name="${fee.name}" data-amount="${fee.amount}" data-action="pay-fee">Pay</button>`}
                            </td>
                        `;
                        fees_list.appendChild(tr);
                    })
                })

                // fetch student fines' data
                fetch(`/ajax/students/enrolled/${event.detail.value}/fines`)
                .then(function (res) {
                    return res.json();
                })
                .then(function(data){
                    console.log(data);

                    // populate fines_list
                    var fines_list = document.getElementById("fines_list");
                    fines_list.innerHTML = "";

                    data.forEach(function(fine){
                        var tr = document.createElement("tr");
                        tr.innerHTML = `
                            <td>${fine.name}</td>
                            <td class="text-end">${parseFloat(fine.amount).toFixed(2)}</td>
                            <td>
                                ${fine.is_paid ? `<button type="button" class="btn btn-sm btn-success" disabled>Paid</button>` : `<button type="button" class="btn btn-sm btn-primary" data-id="${fine.id}" data-name="${fine.name}" data-amount="${fine.amount}" data-action="pay-fine">Pay</button>`}
                            </td>
                        `;
                        fines_list.appendChild(tr);
                    })
                })
            }
            else {
                // clear transaction_items
                transaction_items = [];
                populatePendingTransactions();

                console.log("no choice")
                // populate fees_list
                var fees_list = document.getElementById("fees_list");
                fees_list.innerHTML = `
                    <tr>
                        <td colspan="3" class="text-center">No student selected ...</td>
                    </tr>
                `;

                // populate fines_list
                var fines_list = document.getElementById("fines_list");
                fines_list.innerHTML = `
                    <tr>
                        <td colspan="3" class="text-center">No student selected ...</td>
                    </tr>
                `;
            }
        },
        false,
    );

    document.addEventListener('click', function(event){
        console.log("Event: Click");
        console.log(event);
        if(event.target.dataset.action == 'pay-fee' || event.target.dataset.action == 'pay-fine') {
            console.log("pay-fee")
            console.log(event.target.dataset)

            // add to transaction items
            transaction_items.push({
                foreign_key_id: event.target.dataset.id,
                description: event.target.dataset.name,
                category: event.target.dataset.action.substring(4),
                amount: event.target.dataset.amount,
            });

            // set the button to disabled, change the text to selected, remove bg-primary, and add bg-success
            event.target.disabled = true;
            event.target.innerHTML = "Selected";
            event.target.classList.remove("btn-primary");
            event.target.classList.add("btn-success");

            // populate pending transactions
            populatePendingTransactions();
        }
        else if(event.target.dataset.action == 'remove-transaction-item') {
            console.log("remove-transaction-item")
            console.log(event.target.dataset)

            // remove from transaction items by filtering out the foreign_key_id where category is equal to the category of the button
            transaction_items = transaction_items.filter(function(transaction_item){
                return !(transaction_item.foreign_key_id == event.target.dataset.id && transaction_item.category == event.target.dataset.category);
            });

            // remove tr
            event.target.parentElement.parentElement.remove();

            // reset the button to enabled, change the text to pay, remove bg-success, and add bg-primary
            var pay_buttons = document.querySelectorAll(`[data-action="pay-${event.target.dataset.category}"]`);
            pay_buttons.forEach(function(pay_button){
                if(pay_button.dataset.id == event.target.dataset.id) {
                    pay_button.disabled = false;
                    pay_button.innerHTML = "Pay";
                    pay_button.classList.remove("btn-success");
                    pay_button.classList.add("btn-primary");
                }
            });

            // populate pending transactions
            populatePendingTransactions();
        }
    });

    function populatePendingTransactions() {
        var pending_transactions_list = document.getElementById("pending_transactions_list");
        pending_transactions_list.innerHTML = "";

        if(transaction_items.length == 0) {
            pending_transactions_list.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center">No pending transactions ...</td>
                </tr>
            `;
            // disable btn
            document.getElementById("proceed_transaction_btn").disabled = true;
        }
        else {
            // enable btn
            document.getElementById("proceed_transaction_btn").disabled = false;
        }

        total_amount = 0;

        transaction_items.forEach(function(transaction_item){
            var tr = document.createElement("tr");
            tr.innerHTML = `
                <td>${transaction_item.description}</td>
                <td class="text-end">${capitalizeFirstLetter(transaction_item.category)}</td>
                <td class="text-end">${parseFloat(transaction_item.amount).toFixed(2)}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger" data-id="${transaction_item.foreign_key_id}" data-category="${transaction_item.category}" data-action="remove-transaction-item">Remove</button>
                </td>
            `;
            pending_transactions_list.appendChild(tr);

            total_amount += parseFloat(transaction_item.amount);
        })

        var total_amount_elements = document.querySelectorAll(".total_amount");
        console.log(total_amount_elements);
        total_amount_elements.forEach(function(total_amount_element){
            total_amount_element.innerHTML = parseFloat(total_amount).toFixed(2);
        });

        // populate change
        amount_received = parseFloat(document.getElementById("amount_received").value);
        amount_change = amount_received - total_amount;
        document.getElementById("amount_change").innerHTML = amount_change.toFixed(2);
    }

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    document.addEventListener('input', function(event){
        console.log("Event: Input");
        console.log(event)

        if(event.target.id == "amount_received") {
            amount_received = parseFloat(event.target.value);

            amount_change = amount_received - total_amount;
            document.getElementById("amount_change").innerHTML = amount_change.toFixed(2);
        }
    })

</script>
@endsection
