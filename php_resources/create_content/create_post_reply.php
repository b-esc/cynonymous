<?php
//Christian Lucht
session_start();
include("create_functions.php");

$dbh = connect_DB();

if(isset($_SESSION["uid"])){
    create_post_reply($dbh, sanitize($_POST['thread_url']), $_SESSION['uid'], $_POST['parent_id'], 
        sanitize($_POST['title']), sanitize($_POST['content']), (int)$_POST['anonymous']);
    echo 'Post replied to successfully!';
}else{
    echo "Not Logged In";
}
