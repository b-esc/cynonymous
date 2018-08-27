<?php session_start();
include("retrieve_functions.php");

if(isset($_SESSION["uid"])){
    $DBH = connect_DB();
    echo user_image($DBH, (int)$_SESSION["uid"]);
}
?>