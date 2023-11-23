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
    <div class="container mt-4">
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
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item['accounts'] as $account)
                                    <tr>
                                        <td>{{ $account['accountNum'] }}</td>
                                        <td>{{ $account['clientName'] }}</td>
                                        <td>{{ $account['amount'] }}</td>
                                        <td>{{ $account['currency'] }}</td>
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

    <!-- Bootstrap JS (optional, if you need it) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
