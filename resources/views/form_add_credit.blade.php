<!DOCTYPE html>
<html>
   <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Main Menu</title>
        <link rel="stylesheet" href="{{asset('style.css')}}">
    </head>
    <body>
      <h1 style="margin-left:auto;margin-right:auto;width:fit-content">Add Credit</h1>

        <table border="0">
  <form action="controller.php" method="post">
                <input type="hidden" name="action" value="SUBMT_FORM_ADD"/>
                    <td>Account N° :</td>
                    <td>
                        <input name="account_num" type="text"  />
                    </td>
                </tr>
                <tr>
                    <td>Ammount to Add</td>
                    <td><input name="ammount" type="text" /></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div align="center">
                            <input type="submit" name="add" value="Add" />
                        </div>
                    </td>
                </tr></form>
        </table>
        <p style="margin-left:auto;margin-right:auto;width:fit-content"><a href="/">Return to Main Menu</a></p>
    </body></html>