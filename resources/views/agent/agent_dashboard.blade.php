<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Agent Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    @include('agent.navbar')
    <div class="container mt-5">
        @if (Session::has('success') || Session::has('error'))
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            @if ($message = Session::get('success'))
                                <div class="alert alert-success">
                                    {{ $message }}
                                </div>
                            @elseif ($message = Session::get('error'))
                                <div class="alert alert-danger">
                                    {{ $message }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Navigation Links -->
        <div class="list-group mt-3">
            <a href="/approve-accounts" class="list-group-item list-group-item-action">Review Account Creation Requests</a>
            <a href="/list-clients" class="list-group-item list-group-item-action">Check Listing of Clients</a>
            <a href="/view-physical-transactions" class="list-group-item list-group-item-action">Execute Deposit/Withdrawal Transactions</a>
            <a href="/client-transactions" class="list-group-item list-group-item-action">View all Client Transactions</a>
            <a href="/agent-tranfer" class="list-group-item list-group-item-action">Transfer Credit between Accounts</a>
        </div>

    </div>

</body>

</html>
