<?php
//Christian Lucht
session_start();
include("preferences_functions.php");

$dbh = connect_DB();

if(isset($_SESSION["uid"])){
	set_background_color($dbh, $_POST['color'], $_SESSION['uid']);
}else{
    echo "Not Logged In";
}