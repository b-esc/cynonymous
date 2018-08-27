<?php
include "../../utilities/utilities.php";
include("../../pdo_wrapper/pdo_wrapper_functions.php");

/**
 * Sets the specified user's profile to be invisible to other viewers.
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @param int $uid The id of the user who is making his profile private.
 * @return void
 */
function make_profile_private($dbh, $uid)
{
    global $preftable;
    $sql = "UPDATE $preftable SET private = 1 WHERE id = :uid";
    $sth = PDO_prepare($dbh, $sql);
    PDO_bind_value($sth, ':uid', $uid, PDO::PARAM_INT);

    PDO_execute_bound($sth);


}

/**
 * Sets the specified user's profile to be visible to other viewers.
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @param int $uid The id of the user who is making his profile public.
 * @return void
 */
function make_profile_public($dbh, $uid)
{
    global $preftable;

    $sql = "UPDATE $preftable SET private = 0 WHERE id = :uid";
    $sth = PDO_prepare($dbh, $sql);
    PDO_bind_value($sth, ':uid', $uid, PDO::PARAM_INT);
    PDO_execute_bound($sth);
}

/**
 * Sets the desired background color for the user.
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @param string $color The six digit html color code of the desired color preceeded by a pound.
 * @param int $uid The id of the user who is making his profile public.
 * @return void
 */
function set_background_color($dbh, $color, $uid)
{
    global $preftable;
    $sql = "UPDATE $preftable SET background_color = :color WHERE id = :uid";
    $sth = PDO_prepare($dbh, $sql);
    PDO_bind_value($sth, ':color', $color, PDO::PARAM_STR);
    PDO_bind_value($sth, ':uid', $uid, PDO::PARAM_INT);
    PDO_execute_bound($sth);
}
