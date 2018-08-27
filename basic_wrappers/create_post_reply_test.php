<!DOCTYPE html>
<html>
<head>
    <title>Post Reply Test</title>
</head>
<body>
    <FORM NAME = "mlt" METHOD="POST" ACTION="../php_resources/create_content/create_post_reply.php">
        Thread URL<br>
        <INPUT TYPE = "Text" Name = "thread_url"><br>
        Parent ID<br>
        <INPUT TYPE = "Text" Name = "parent_id"><br>
        Anonymous<br>
        <INPUT TYPE = "Text" Name = "anonymous" value = '0'><br>
        Title<br>
        <INPUT TYPE = "Text" Name = "title"><br>
        Content<br>
        <textarea NAME = "content" rows = "4" cols = "50"></textarea><br>
        <INPUT TYPE = "Submit" Name = "Submit1" VALUE = "Create Post Reply">
    </FORM>
</body>
</html>
