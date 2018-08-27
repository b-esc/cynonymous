<?php
//Christian Lucht
session_start();
include("preferences_functions.php");

$dbh = connect_DB();

if(isset($_SESSION["uid"])){
	make_profile_private($dbh, $_SESSION['uid']);
}else{
    echo "Not Logged In";
}