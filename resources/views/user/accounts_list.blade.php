<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Account List</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    @include('auth.layouts')

    <div class="container mt-4">
        <h1 class="text-center">Accounts List</h1>

        <!-- Add filter dropdowns -->
        <div class="row mt-4">
            <div class="col-md-4">
                <h6><label for="currencyFilter">Filter by Currency:</label></h6>
                <select id="currencyFilter" class="form-control">
                    <option value="all" selected>All</option>
                    <option value="LBP">LBP</option>
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                </select>
            </div>
            <div class="col-md-4">
                <h6><label for="statusFilter">Filter by Status:</label></h6>
                <select id="statusFilter" class="form-control">
                    <option value="all">All</option>
                    <option value="approved" selected>Approved</option>
                    <option value="pending">Pending</option>
                    <option value="disapproved">Disapproved</option>
                </select>
            </div>
            <div class="col-md-4">
                <h6><label for="availabilityFilter">Filter by Availability:</label></h6>
                <select id="availabilityFilter" class="form-control">
                    <option value="all">All</option>
                    <option value="enabled" selected>Enabled</option>
                    <option value="disabled">Disabled</option>
                </select>
            </div>
        </div>

        <!-- Display accounts list -->
        <div class="mt-4">
            @if (count($accounts) > 0)
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Account Number</th>
                            <th>Client</th>
                            <th>Amount</th>
                            <th>Currency</th>
                            <th>Status</th>
                            <th>Availability</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($accounts as $account)
                            <tr data-status="{{ $account['status'] }}" data-currency="{{ $account['currency'] }}"
                                data-availability="{{ $account['is_enabled'] }}">
                                <td>{{ $account['accountNum'] }}</td>
                                <td>{{ $account['clientName'] }}</td>
                                <td>{{ $account['amount'] }}</td>
                                <td>{{ $account['currency'] }}</td>
                                <td>
                                    @if ($account['status'] === 'approved')
                                        <span class="text-success">{{ $account['status'] }}</span>
                                    @else
                                        <span class="text-danger">{{ $account['status'] }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($account['is_enabled'] === 1)
                                        <span class="text-success">Enabled</span>
                                    @else
                                        <span class="text-danger">Disabled</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('tranfer-from-acc') }}" method="get">
                                        @csrf
                                        <input type="hidden" name="accountNum" value="{{ $account['accountNum'] }}">
                                        @if ($account['status'] == 'approved' && $account['is_enabled'] === 1)
                                            <button type="submit" class="btn btn-link">Transfer</button>
                                        @else
                                            <button type="button" class="btn btn-link" disabled>Transfer</button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-center">You have no accounts.</p>
            @endif
        </div>

        <p class="text-center mt-4"><a href="/main-menu" class="btn btn-primary">Return to Main Menu</a></p>

        <!-- Add jQuery and custom script for filtering -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script>
            $(document).ready(function() {
                // Call applyFilters on page load
                applyFilters();

                // Bind applyFilters to the change event of filter dropdowns
                $('#statusFilter, #currencyFilter, #availabilityFilter').change(function() {
                    applyFilters();
                });

                function applyFilters() {
                    var selectedStatus = $('#statusFilter').val();
                    var selectedCurrency = $('#currencyFilter').val();
                    var selectedAvailability = $('#availabilityFilter').val();

                    // Hide all rows initially
                    $('tbody tr').hide();

                    // Filter and show rows based on selected filters
                    $('tbody tr').filter(function() {
                        var statusMatch = selectedStatus === 'all' || $(this).data('status') === selectedStatus;
                        var currencyMatch = selectedCurrency === 'all' || $(this).data('currency') ===
                            selectedCurrency;
                        var availabilityMatch =
                            selectedAvailability === 'all' || $(this).data('availability') == (
                                selectedAvailability === 'enabled');

                        return statusMatch && currencyMatch && availabilityMatch;
                    }).show();
                }
            });
        </script>
    </div>
</body>

</html>
