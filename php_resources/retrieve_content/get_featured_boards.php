<?php
//Christian Lucht
include("retrieve_functions.php");

$dbh = connect_DB();

echo json_encode(get_featured_boards($dbh));
