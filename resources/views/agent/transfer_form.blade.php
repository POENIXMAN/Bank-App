<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Transfer</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    @include('agent.navbar')

    <div class="container mt-5">
        <h1 class="text-center">Transfer Funds</h1>

        @if ($errors->has('general'))
            <div class="alert alert-danger mt-3">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session()->has('success'))
            <div class="alert alert-success mt-3">
                {{ session()->get('success') }}
            </div>
        @endif

        <form action="/agent-submit-transfer" method="post">
            @csrf

            <div class="form-group row">
                <label for="fromAccount" class="col-md-3 col-form-label text-md-right">From Account:</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" id="fromAccount" name="fromAccount" required>
                    @if ($errors->has('fromAccount'))
                        <span class="text-danger">{{ $errors->first('fromAccount') }}</span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="toAccount" class="col-md-3 col-form-label text-md-right">To Account:</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" id="toAccount" name="toAccount" required>
                    @if ($errors->has('toAccount'))
                        <span class="text-danger">{{ $errors->first('toAccount') }}</span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="amount" class="col-md-3 col-form-label text-md-right">Amount:</label>
                <div class="col-md-6">
                    <input type="number" class="form-control" id="amount" name="amount" required>
                    @if ($errors->has('amount'))
                        <span class="text-danger">{{ $errors->first('amount') }}</span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="currency" class="col-md-3 col-form-label text-md-right">Currency:</label>
                <div class="col-md-6">
                    <select class="form-control" id="currency" name="currency" required>
                        <option value="USD">USD</option>
                        <option value="LBP">LBP</option>
                        <option value="EUR">EUR</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6 offset-md-3 text-center">
                    <button type="submit" class="btn btn-primary mx-auto">Transfer</button>
                </div>
            </div>

        </form>
        <a href="{{ route('agent-dashboard') }}" class="btn btn-primary">Return to Dashboard</a>
    </div>

</body>

</html>
