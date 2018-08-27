<?php
//Christian Lucht
session_start();
include("create_functions.php");

$dbh = connect_DB();

if(isset($_SESSION["uid"])){
	create_board($dbh, $_SESSION["uid"], sanitize($_POST["name"]), sanitize($_POST["description"]), (int)$_POST["family_friendly"], $_POST["board_url"]);
	echo "Board created successfully!";
}else{
    echo "Not Logged In";
}