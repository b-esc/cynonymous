<?php
//Christian Lucht
session_start();
include("delete_functions.php");

$dbh = connect_DB();

if(isset($_SESSION["uid"])){
    delete_post($dbh, $_POST["post_id"]);
    echo "Post deleted.";
}else{
    echo "Not Logged In";
}	

