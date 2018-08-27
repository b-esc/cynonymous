<?php
//Ryan Buls

session_start();
include("create_functions.php");

$dbh = connect_DB();

/*testing values:
        thread_id = 1
        board_id = 1
        uid = 7
        anonymous = 0*/
if(isset($_SESSION["uid"])){
    $thread_id = get_thread_id($dbh,$_POST["thread_url"]);
    $board_id = get_board_id($dbh,$thread_id);
    create_post($dbh, $thread_id, $board_id, $_SESSION["uid"], sanitize($_POST["title"]), sanitize($_POST["content"]), (int)$_POST["anonymous"]);
}else{
    echo "Not Logged In";
}
