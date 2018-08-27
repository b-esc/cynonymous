<!DOCTYPE html>
<html>
<head>
    <title>Create Thread Test</title>
</head>
<body>
    <FORM NAME = "mlt" METHOD="POST" ACTION="../php_resources/create_content/create_thread.php">
    	Name<br>
        <INPUT TYPE = "Text" Name = "name"><br>
        Description<br>
        <textarea NAME = "description" rows = "4" cols = "50"></textarea><br>
        Thread URL<br>
        <INPUT TYPE = "Text" Name = "thread_url"><br>
        Board URL<br>
        <INPUT TYPE = "Text" Name = "board_url"><br>
        <INPUT TYPE = "Submit" Name = "Submit1" VALUE = "Submit Thread">
    </FORM>
</body>
</html>