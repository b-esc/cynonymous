<?php
//Christian Lucht
session_start();
include("delete_functions.php");

$dbh = connect_DB();

if(isset($_SESSION["uid"])){
    delete_thread($dbh, $_POST["thread_id"]);
    echo "Thread and related posts deleted.";
}else{
    echo "Not Logged In";
}	
