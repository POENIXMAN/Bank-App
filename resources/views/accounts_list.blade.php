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
    <div class="container">
        <h1 class="text-center">Accounts List</h1>

        @if(count($accounts) > 0)
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Account N°</th>
                        <th>Client</th>
                        <th>Amount</th>
                        <th>Currency</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accounts as $account)
                        <tr>
                            <td>{{ $account['accountNum'] }}</td>
                            <td>{{ $account['clientName'] }}</td>
                            <td>{{ $account['amount'] }}</td>
                            <td>{{ $account['currency'] }}</td>
                            <td>{{ $account['status'] }}</td>
                            <td>
                                <form action="{{ route('transfer') }}" method="get">
                                    @csrf
                                    <input type="hidden" name="accountNum" value="{{ $account['accountNum'] }}">
                                    <button type="submit" class="btn btn-link">Transfer</button>
                                </form>
                            </td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-center">You have no accounts.</p>
        @endif

        <p class="text-center"><a href="/main-menu">Return to Main Menu</a></p>
    </div>
</body>

</html>
