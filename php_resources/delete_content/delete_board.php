<?php
//Christian Lucht

session_start();
include("delete_functions.php");

$dbh = connect_DB();

if(isset($_SESSION["uid"])){
	delete_board($dbh, $_POST["board_id"]);
	echo "Board, related threads, and related posts deleted.";
}else{
    echo "Not Logged In";
}	


