<?php
//Christian Lucht
session_start();
include("retrieve_functions.php");

$dbh = connect_DB();

echo json_encode(get_friends_list($dbh, $_SESSION['uid']));
