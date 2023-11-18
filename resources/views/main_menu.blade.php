<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Main Menu</title>

    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    
    <div class="row justify-content-center mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Actions</div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-md-12 text-center">
                            <a href="/create-acc" class="btn btn-primary">Create Account</a>
                            <a href="/add-credit" class="btn btn-primary ml-2">Add Credit</a>
                            <a href="/display-acc" class="btn btn-primary ml-4">Display Accounts</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</body>

</html>



{{-- how to accses session information --}}

{{-- @if (session('user'))
        <p>Welcome, {{ session('user')['name'] }}!</p>
        <p>ID: {{ session('user')['id'] }}</p>
        <p>Email: {{ session('user')['email'] }}</p>
    @endif --}}
