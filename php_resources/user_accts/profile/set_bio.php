<?php
//Christian Lucht
session_start();
include("profile_functions.php");

$dbh = connect_DB();

if(isset($_SESSION["uid"])){
	set_bio($dbh, $_POST['bio'], $_SESSION['uid']);
}else{
    echo "Not Logged In";
}
