<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Approve Accounts</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    @include('agent.navbar')

    <div class="container mt-5">
        <h2 class="text-center">Account Approval</h2><br>

        @if (count($accounts) > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Account Number</th>
                        <th>Client Name</th>
                        <th>Amount</th>
                        <th>Currency</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($accounts as $account)
                        <tr>
                            <td>{{ $account['accountNum'] }}</td>
                            <td>{{ $account['clientName'] }}</td>
                            <td>{{ $account['amount'] }}</td>
                            <td>{{ $account['currency'] }}</td>
                            <td>{{ $account['created_at'] }}</td>
                            <td>
                                <form action="{{ route('approve-account') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $account['id'] }}">
                                    <button type="submit" class="btn btn-success">Approve</button>
                                </form>

                                <form action="{{ route('reject-account') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $account['id'] }}">
                                    <button type="submit" class="btn btn-danger">Reject</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No accounts pending approval.</p>
        @endif

        <a href="{{ route('agent-dashboard') }}" class="btn btn-primary">Return to Dashboard</a>
    </div>

</body>



</html>
