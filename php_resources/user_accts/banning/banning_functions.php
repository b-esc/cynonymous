<?php
include "../../utilities/utilities.php";
include("../../pdo_wrapper/pdo_wrapper_functions.php");

/**
 * Bans a user from accessing the site.
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @param int $ban_username The id of the user that is to be banned.
 * @return void
 */
function ban_user($dbh, $ban_username)
{
    global $usertable;

    $sql = "UPDATE $usertable SET banned = 1 WHERE username = :ban_username";
    $sth = PDO_prepare($dbh, $sql);
    PDO_bind_value($sth, ':ban_username', $ban_username, PDO::PARAM_STR);
    PDO_execute_bound($sth);
}

/**
 * Allows a banned user to access the site once again.
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @param int $ban_username The id of the user who is to be reallowed access.
 * @return void
 */
function unban_user($dbh, $ban_username)
{
    global $usertable;

    $sql = "UPDATE $usertable SET banned = 0 WHERE username = :ban_username";
    $sth = PDO_prepare($dbh, $sql);
    PDO_bind_value($sth, ':ban_username', $ban_username, PDO::PARAM_STR);
    PDO_execute_bound($sth);
}
