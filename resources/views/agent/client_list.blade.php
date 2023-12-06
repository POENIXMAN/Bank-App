<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client List</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    @include('agent.navbar')

    <div class="container mt-4">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="currencyFilter">Filter by Currency:</label>
                <select id="currencyFilter" class="form-control">
                    <option value="all">All</option>
                    <option value="LBP">LBP</option>
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="statusFilter">Filter by Status:</label>
                <select id="statusFilter" class="form-control">
                    <option value="all">All</option>
                    <option value="approved">Approved</option>
                    <option value="pending">Pending</option>
                    <option value="disapproved">Disapproved</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="availabilityFilter">Filter by Availability:</label>
                <select id="availabilityFilter" class="form-control">
                    <option value="all">All</option>
                    <option value="enabled">Enabled</option>
                    <option value="disabled">Disabled</option>
                </select>
            </div>
        </div>

        @foreach ($usersWithAccounts as $item)
            <div class="card mb-3">
                <div class="card-header">
                    <h2>User: {{ $item['user']['name'] }}</h2>
                    <p>Email: {{ $item['user']['email'] }}</p>
                </div>
                <!-- Inside the card-body div -->
                <div class="card-body">
                    @if (!empty($item['accounts']))
                        <h3>Accounts:</h3>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Account Number</th>
                                    <th>Client Name</th>
                                    <th>Amount</th>
                                    <th>Currency</th>
                                    <th>Status</th>
                                    <th>Enabled</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item['accounts'] as $account)
                                    <tr data-status="{{ $account['status'] }}" data-currency="{{ $account['currency'] }}" data-availability="{{ $account['is_enabled'] }}">
                                        <td>{{ $account['accountNum'] }}</td>
                                        <td>{{ $account['clientName'] }}</td>
                                        <td>{{ $account['amount'] }}</td>
                                        <td>{{ $account['currency'] }}</td>
                                        <td>{{ $account['status'] }}</td>
                                        <td>{{ $account['is_enabled'] ? "Enabled" : "Disabled" }}</td>
                                        <td>
                                            <form action="{{ $account['is_enabled'] ? route('disable-account') : route('enable-account') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $account['id'] }}">
                                                <button type="submit" class="btn {{ $account['is_enabled'] ? 'btn-danger' : 'btn-success' }}">
                                                    {{ $account['is_enabled'] ? 'Disable' : 'Enable' }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No accounts found for this user.</p>
                    @endif
                </div>
            </div>
        @endforeach

        <a href="{{ route('agent-dashboard') }}" class="btn btn-primary">Return to Dashboard</a>
    </div>

    <!-- Add jQuery and custom script for filtering -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to apply filters
            function applyFilters() {
                var selectedStatus = $('#statusFilter').val();
                var selectedCurrency = $('#currencyFilter').val();
                var selectedAvailability = $('#availabilityFilter').val();

                // Hide all rows initially
                $('tbody tr').hide();

                // Filter and show rows based on selected filters
                $('tbody tr').filter(function() {
                    var statusMatch = selectedStatus === 'all' || $(this).data('status') === selectedStatus;
                    var currencyMatch = selectedCurrency === 'all' || $(this).data('currency') === selectedCurrency;
                    var availabilityMatch = selectedAvailability === 'all' || $(this).data('availability') == (selectedAvailability === 'enabled');

                    return statusMatch && currencyMatch && availabilityMatch;
                }).show();
            }

            // Bind applyFilters to the change event of filter dropdowns
            $('#statusFilter, #currencyFilter, #availabilityFilter').change(function() {
                applyFilters();
            });
        });
    </script>

</body>

</html>
