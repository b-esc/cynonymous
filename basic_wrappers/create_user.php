<!DOCTYPE html>
<html>
<head>
    
    <title>Login</title>

</head>
<body>
    <Fieldset><Legend> Login </Legend>
        <Form name="login" Method="post" Action="../php_resources/user_accts/create_acct.php">
            <table>
                <tr><td>First Name: </td><td><input type="text" name="fname"> </td></tr>
                <tr><td>Last Name: </td><td><input type="text" name="lname"> </td></tr>
                <tr><td>Email:: </td><td><input type="text" name="email"> </td></tr>
                <tr><td>Username: </td><td><input type="text" name="user"> </td></tr>
                <tr><td>Password: </td><td><input type="password" name="pass">
                <input type="submit" value= "GO"></td></tr>
            </table>
        </Form>
</body>
</html>