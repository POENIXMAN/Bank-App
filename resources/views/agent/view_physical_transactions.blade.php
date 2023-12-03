<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Physical Transactions</title>
    <!-- Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    @include('agent.navbar')

    <div class="container mt-5">
        <h2>Physical Transactions</h2>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="/physical-transactions" method="post">@csrf
            <div class="form-group">
                <label for="transactionType">Transaction Type:</label>
                <select class="form-control" id="transactionType" name="transactionType" required>
                    <option value="withdrawal">Withdrawal</option>
                    <option value="deposit">Deposit</option>
                </select>
            </div>

            <div class="form-group">
                <label for="accountNumber">Account Number:</label>
                <input type="text" class="form-control" id="accountNumber" name="accountNumber" required>
                @if ($errors->has('accountNumber'))
                    <span class="text-danger">{{ $errors->first('accountNumber') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="currency">Currency:</label>
                <select class="form-control" id="currency" name="currency" required>
                    <option value="LBP">LBP</option>
                    <option value="EUR">EUR</option>
                    <option value="USD">USD</option>
                </select>
                @if ($errors->has('currency'))
                    <span class="text-danger">{{ $errors->first('currency') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="number" class="form-control" id="amount" name="amount" required>
                @if ($errors->has('amount'))
                    <span class="text-danger">{{ $errors->first('amount') }}</span>
                @endif
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- Bootstrap JS and Popper.js scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
