<?php
//Christian Lucht
session_start();
include("create_functions.php");

$dbh = connect_DB();

if(isset($_SESSION["uid"])){
	$board_id = board_url_to_id($dbh, $_POST["board_url"]);
	create_thread($dbh, $board_id, $_SESSION["uid"], sanitize($_POST["name"]), sanitize($_POST["description"]), $_POST["thread_url"]);
	echo "Thread created successfully!";
} else{
	echo "Not logged in.";
}