<?php
//Christian Lucht
session_start();
include("friends_functions.php");

$dbh = connect_DB();

if(isset($_SESSION["uid"])){
	remove_friend($dbh, $_POST['friend_id'], $_SESSION['uid']);
}else{
    echo "Not Logged In";
}


