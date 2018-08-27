<?php
//Christian Lucht
session_start();
include("banning_functions.php");

$dbh = connect_DB();

ban_user($dbh, $_POST["uid"]);

function ban_user($dbh, $uid){
	$sql = "UPDATE user_info SET banned = 1 WHERE id = :uid";
	try{
		$sth = $dbh->prepare($sql);
		$sth->bindValue(':uid', $uid, PDO::PARAM_INT);
		$sth->execute();
	} catch(PDOException $e) {
        echo $e->getMessage();
    }
}
