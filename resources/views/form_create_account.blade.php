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
    <div class="container">
        <h1 class="text-center mt-4">Create Account</h1>

        <form action="/submit-form-create" method="post">
            @csrf
            <input type="hidden" name="clientId" value="{{ @session('user')['id'] }}">

            <div class="form-group">
                <label for="accountNum">Account Number:</label>
                <input type="text" class="form-control" name="accountNum" id="accountNum">
            </div>

            <div class="form-group">
                <label for="name">Client Name:</label>
                <input type="text" class="form-control" name="name" id="name">
            </div>

            <div class="form-group">
                <label for="ammount">Starting Amount:</label>
                <input type="text" class="form-control" name="ammount" id="ammount">
            </div>

            <div class="form-group">
                <label for="currency">Currency:</label>
                <select class="form-control" name="currency" id="currency">
                    <option value="LBP">LBP</option>
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                </select>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg">Create</button>
            </div>
        </form>

        <p class="text-center mt-4"><a href="/main-menu">Return to Main Menu</a></p>
    </div>
</body>

</html>
