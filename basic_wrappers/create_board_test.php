<!DOCTYPE html>
<html>
<head>
    <title>Create Board Test</title>
</head>
<body>
    <FORM NAME = "mlt" METHOD="POST" ACTION="../php_resources/create_content/create_board.php">
    	Name<br>
        <INPUT TYPE = "Text" Name = "name"><br>
        Description<br>
        <textarea NAME = "description" rows = "4" cols = "50"></textarea><br>
        URL<br>
        <INPUT TYPE = "Text" Name = "board_url"><br>
        <INPUT TYPE = "Hidden" Name = "family_friendly" value = '1'>
        <INPUT TYPE = "Submit" Name = "Submit1" VALUE = "Submit Board">
    </FORM>
</body>
</html>