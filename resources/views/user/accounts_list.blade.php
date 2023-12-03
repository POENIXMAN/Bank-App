<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Main Menu</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    @include('auth.layouts')

    <div class="container">
        <h1 class="text-center">Accounts List</h1>

        <!-- Add a filter dropdown -->
        <div class="container">
            <h3><label for="statusFilter">Filter by Status:</label></h3>
            <select id="statusFilter" class="form-control">
                <option value="all">All</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="disapproved">Disapproved</option>
            </select>
        </div><br>

        <!-- Display accounts list -->
        <div class="container">
            @if (count($accounts) > 0)
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Account Number</th>
                        <th>Client</th>
                        <th>Amount</th>
                        <th>Currency</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($accounts as $account)
                    <tr data-status="{{ $account['status'] }}">
                        <td>{{ $account['accountNum'] }}</td>
                        <td>{{ $account['clientName'] }}</td>
                        <td>{{ $account['amount'] }}</td>
                        <td>{{ $account['currency'] }}</td>
                        <td> @if ($account['status'] === 'approved')
                            <span style="color: green;">{{ $account['status'] }}</span>
                            @else ($account['status'] === 'approved')
                            <span style="color: red;">{{ $account['status'] }}</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('transfer') }}" method="get">
                                @csrf
                                <input type="hidden" name="accountNum" value="{{ $account['accountNum'] }}">
                                @if($account['status'] == 'approved')
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

        <p class="text-center"><a href="/main-menu">Return to Main Menu</a></p>

        <!-- Add jQuery and custom script for filtering -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#statusFilter').change(function () {
                    var selectedStatus = $(this).val();

                    if (selectedStatus === 'all') {
                        $('tbody tr').show();
                    } else {
                        $('tbody tr').hide();
                        $('tbody tr[data-status="' + selectedStatus + '"]').show();
                    }
                });
            });
        </script>
    </div>
</body>

</html>