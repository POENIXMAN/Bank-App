<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Main Menu</title>
</head>

<body>
    <h1 style="margin-left:auto;margin-right:auto;width:fit-content">Create Account</h1>

    <table border="0">
        <tr>

            <td>Account N° :</td>
            <td>
                <form action="/submit-form-create" method="post"> @csrf
                    <input name="accountNum" type="text" />
            </td>
        </tr>

        <tr>
            <td>Client Name :</td>
            <td><input name="name" type="text" /></td>
        </tr>
        <tr>
            <td>Starting Ammount :</td>
            <td><input name="ammount" type="text" /></td>
        </tr>
        <tr>
            <td colspan="2">
                <div align="center">
                    <input type="submit" name="create" value="Create" />
                </div>
                </form>
            </td>

        </tr>

    </table>
    <p style="margin-left:auto;margin-right:auto;width:fit-content"><a href="/main-menu">Return to Main Menu</a></p>

</body>

</html>
