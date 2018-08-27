<?php
include("../../utilities/utilities.php");
include("../../pdo_wrapper/pdo_wrapper_functions.php");

/**
 * Used to set the description of the user.
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @param string $description The string of the desired description.
 * @param int $uid The id of the user whose description is to be set.
 * @return void
 */
function set_description($dbh, $description, $uid){
	global $usertable;

	$sql = "UPDATE $usertable SET description = :description WHERE id = :uid";
		$sth = PDO_prepare($dbh, $sql);
		PDO_bind_value($sth, ':description', $description, PDO::PARAM_STR);
		PDO_bind_value($sth, ':uid', $uid, PDO::PARAM_INT);
		PDO_execute_bound($sth);
}

/**
 * Used to set the bio of the user.
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @param string $bio The string of the desired bio.
 * @param int $uid The id of the user whose bio is to be set.
 * @return void
 */
function set_bio($dbh, $bio, $uid){
	global $usertable;
	$sql = "UPDATE $usertable SET bio = :bio WHERE id = :uid";
		$sth = PDO_prepare($dbh, $sql);
		PDO_bind_value($sth, ':bio', $bio, PDO::PARAM_STR);
		PDO_bind_value($sth, ':uid', $uid, PDO::PARAM_INT);
		PDO_execute_bound($sth);
}