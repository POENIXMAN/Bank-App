<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Main Menu</title>
    <link rel="stylesheet" href="{{ asset('styles.css') }}">
</head>

<body>
    <h1 style="margin-left:auto;margin-right:auto;width:fit-content">Accounts List</h1>

    <table border ="1">
        <thead>
            <tr>
                <td>Account N°</td>
                <td>Client</td>
                <td>Ammount</td>
            </tr>
        </thead>
        <?php
        foreach ($accounts as $account) {
            echo "<tr><td>$account->accountNum</td><td>$account->clientName</td><td>$account->ammount$</td></tr>";
        }
        ?>

    </table>

    <p style="margin-left:auto;margin-right:auto;width:fit-content"><a href="/main-menu">Return to
            Main Menu</a></p>

</body>

</html>
