<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Transactions</title>

    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    @include('agent.navbar')

    <div class="container mt-4">
        <h1 class="mb-4">All Previous Transactions</h1>

        <!-- Filter Form -->
        <div class="row mb-4">
            <div class="col-md-3">
                <label for="fromAccount">From Account:</label>
                <input type="text" class="form-control" id="fromAccount">
            </div>
            <div class="col-md-3">
                <label for="toAccount">To Account:</label>
                <input type="text" class="form-control" id="toAccount">
            </div>
            <div class="col-md-3">
                <label for="currency">Currency:</label>
                <select class="form-control" id="currency">
                    <option value="">All</option>
                    <option value="LBP">LBP</option>
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-primary mt-4" onclick="applyFilters()">Apply Filters</button>
            </div>
        </div>

        @if (count($transactions) > 0)
            <table class="table table-striped" id="transactionsTable">
                <thead>
                    <tr>
                        <th>From Account</th>
                        <th>To Account</th>
                        <th>Amount</th>
                        <th>Currency</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction['from_account_id'] }}</td>
                            <td>{{ $transaction['to_account_id'] }}</td>
                            <td>{{ $transaction['amount'] }}</td>
                            <td>{{ $transaction['currency'] }}</td>
                            <td>{{ $transaction['formatted_created_at'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No transactions available.</p>
        @endif
        <a href="{{ route('agent-dashboard') }}" class="btn btn-primary">Return to Dashboard</a>
    </div>

    <script>
        function applyFilters() {
            var fromAccount = $('#fromAccount').val();
            var toAccount = $('#toAccount').val();
            var currency = $('#currency').val();

            // Loop through each row in the table
            $('#transactionsTable tbody tr').each(function() {
                var showRow = true;

                // Check the From Account filter
                if (fromAccount && $(this).find('td:nth-child(1)').text().indexOf(fromAccount) === -1) {
                    showRow = false;
                }

                // Check the To Account filter
                if (toAccount && $(this).find('td:nth-child(2)').text().indexOf(toAccount) === -1) {
                    showRow = false;
                }

                // Check the Currency filter
                if (currency && $(this).find('td:nth-child(4)').text() !== currency) {
                    showRow = false;
                }

                // Show or hide the row based on filter conditions
                if (showRow) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
    </script>

</body>

</html>
