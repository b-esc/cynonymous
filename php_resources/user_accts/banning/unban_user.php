<?php
//Christian Lucht
session_start();
include("banning_functions.php");

$dbh = connect_DB();

if(isset($_SESSION["uid"])){
	unban_user($dbh, $_POST["banned_username"]);
}else{
    echo "Not Logged In";
}

