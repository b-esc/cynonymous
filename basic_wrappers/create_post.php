<!DOCTYPE html>
<html>
<head>
    <title>Multiline txbox</title>
</head>
<body>
    <?php
        $boardnum = $_GET["boardnum"];
    ?>
    <FORM NAME = "mlt" METHOD="POST" ACTION="../php_resources/create_content/create_post.php">
        <INPUT TYPE = "Text" Name = "title"><br>
        <INPUT TYPE = "Hidden" Name = "board_id" value = '1'>
        <INPUT TYPE = "Hidden" Name = "thread_id" value = '29'>
        <INPUT TYPE = "Hidden" Name = "anonymous" value = '0'>
        <textarea NAME = "content" rows = "4" cols = "50"></textarea>
        <INPUT TYPE = "Submit" Name = "Submit1" VALUE = "Login">
    </FORM>
</body>
</html>
