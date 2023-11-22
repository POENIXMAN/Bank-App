<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Transfer Form</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
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

        <form action="/submit-transfer" method="post">
            @csrf

            <div class="form-group row">
                <label for="fromAccount" class="col-md-3 col-form-label text-md-right">From Account:</label>
                <div class="col-md-6">
                    <input type="hidden" name="fromAccount" value="{{ $accountNumFrom }}">
                    <input type="text" class="form-control" id="fromAccountDisplay" value="{{ $accountNumFrom }}" disabled>
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
                    <input type="text" class="form-control" id="amount" name="amount" required>
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
                <div class="col-md-6 offset-md-3">
                    <button type="submit" class="btn btn-primary">Transfer</button>
                </div>
            </div>
        </form>
        
    </div>

    <p class="text-center"><a href="/main-menu">Return to Main Menu</a></p>

</body>

</html>
