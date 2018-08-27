<?php
//Christian Lucht
session_start();
include("retrieve_functions.php");

$dbh = connect_DB();

if(isset($_SESSION["uid"])){
    echo json_encode(get_user_bio($dbh, $_SESSION["uid"]));
}else{
    echo "Not Logged In";
}