<html>
    <head>
        <title> Here are all of the posts </title>
    </head>
    <body>
        <?PHP include("php_resources/board_resource.php");?>
        <FIELDSET><LEGEND>Make New Post</LEGEND>
            <FORM NAME = "mlt" METHOD="POST" ACTION="php_resources/create_post.php">
                Title: <INPUT TYPE = "Text" Name = "title"><br><br>
                
                Board ID: <br>
                <INPUT TYPE = "number" Name = "board_id" value = '1'><br>
                Thread ID: <br>
                <INPUT TYPE = "number" Name = "thread_id" value = '1'><br>
                Amonymous?: <br>
                <INPUT TYPE = "number" Name = "anonymous" value = '0'><br>

                Content: <br>
                <textarea NAME = "content" rows = "4" cols = "50"></textarea>
                <INPUT TYPE = "Submit" Name = "Submit1" VALUE = "POST">
            </FORM>
        </FIELDSET>
    </body>
</html>