<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Main Menu</title>
</head>

<body>
    <h1 style="margin-left:auto;margin-right:auto;width:fit-content">Add Credit</h1>

    <table border="0">
        <form action="/submit-form-add-credit" method="post"> @csrf
            <td>Account N° :</td>
            <td>
                <input name="account_num" type="text" />
            </td>
            </tr>
            <tr>
                <td>Amount to Add</td>
                <td><input name="amount" type="number" /></td>
            </tr>
            <tr>
                <td colspan="2">
                    <div align="center">
                        <input type="submit" name="add" value="Add" />
                    </div>
                </td>
            </tr>
        </form>
    </table>
    <p style="margin-left:auto;margin-right:auto;width:fit-content"><a href="/main-menu">Return to
            Main Menu</a></p>
</body>

</html>
