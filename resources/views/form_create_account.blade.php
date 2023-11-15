<!DOCTYPE html>
<html>
   <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Main Menu</title>
        <link rel="stylesheet" href="{{asset('style.css')}}">
    </head>
    <body>
      <h1 style="margin-left:auto;margin-right:auto;width:fit-content">Create Account</h1>

<table border="0">
    <tr>
  
  <td>Account N° :</td>
    <td>
  <form action="/submit_form_create" method="POST">@csrf
    
    <input name="accountNum" type="text" />
      </td>
      </tr>
    
    <tr>
      <td>Client Name  :</td>
      <td><input name="clientName" type="text" /></td>
    </tr>
    <tr>
      <td>Starting Ammount :</td>
      <td><input name="ammount" type="text" /></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center">
          <input type="submit" name="create" value="Create" />
        </div>
  </form>
    </td>
  
    </tr>
  
</table>
<p style="margin-left:auto;margin-right:auto;width:fit-content"><a href="/">Return to Main Menu</a></p>

    </body></html>
