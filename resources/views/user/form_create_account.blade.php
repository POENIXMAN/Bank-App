<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Create Account</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    @include('auth.layouts')
    
    <div class="container">
        <h1 class="text-center mt-4">Create Account</h1>

        <form action="/submit-form-create" method="post">
            @csrf
            <input type="hidden" name="clientId" value="{{ @session('user')['id'] }}">

            <div class="form-group">
                <label for="accountNum">Account Number:</label>
                <input type="text" class="form-control" name="accountNum" id="accountNum">
                @error('accountNum')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="name">Client Name:</label>
                <input type="text" class="form-control" name="name" id="name">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="amount">Starting Amount:</label>
                <input type="number" class="form-control" name="amount" id="amount">
                @error('amount')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="currency">Currency:</label>
                <select class="form-control" name="currency" id="currency">
                    <option value="LBP">LBP</option>
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                </select>
                @error('currency')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg">Create</button>
            </div>
        </form>

        <p class="text-center mt-4"><a href="/main-menu">Return to Main Menu</a></p>
    </div>
</body>

</html>
