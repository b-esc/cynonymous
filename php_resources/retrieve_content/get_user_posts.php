<?php session_start();
include("retrieve_functions.php");

if(isset($_SESSION["uid"])){
    $DBH = connect_DB();
    echo json_encode(user_posts($DBH, (int)$_SESSION["uid"]));
}
?>