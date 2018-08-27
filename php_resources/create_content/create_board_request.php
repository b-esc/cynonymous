<?php
//Christian Lucht
session_start();
include("create_functions.php");

$dbh = connect_DB();

if(isset($_SESSION["uid"])){
	create_board_request($dbh, $_SESSION["uid"], sanitize($_POST["name"]), sanitize($_POST["description"]), (int)$_POST["family_friendly"]);
	echo "Board request created successfully!";
}else{
	echo "Not Logged In";
}