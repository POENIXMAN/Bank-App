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

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Account N°</th>
                    <th>Client</th>
                    <th>Amount</th>
                    <th>Currency</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($accounts as $account) {
                    echo "<tr><td>{$account['accountNum']}</td><td>{$account['clientName']}</td><td>{$account['ammount']}$</td><td>{$account['currency']}$</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <p class="text-center"><a href="/main-menu">Return to Main Menu</a></p>
    </div>

</body>

</html>
