<?php
include "../../utilities/utilities.php";
include "../../pdo_wrapper/pdo_wrapper_functions.php";

/**
 * Adds a friend to the specified user's friend list.
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @param int $friend_id The id of the friend who is being added.
 * @param int $uid The id of the user who is adding this friend.
 * @return void
 */
function add_friend($dbh, $friend_id, $uid)
{
    global $usertable;
    $friends = '';

    //retrieve a list of friends
    $sql = "SELECT friends FROM $usertable where id = :uid ";
    $sth = PDO_prepare($dbh, $sql);
    PDO_bind_value($sth, ':uid', $uid, PDO::PARAM_INT);
    PDO_execute_bound($sth);
    $friends = PDO_fetch_all_assoc($sth);

    //change list from array to string
    $friends = $friends[0]['friends'];
    //separate string into an array with a key for each friend
    $friend_arr = explode(',', $friends);
    //check if the friend already exisits
    $has_friend = false;
    foreach ($friend_arr as $key => $friend) {
        if ($friend == $friend_id) {
            $has_friend = true;
        }
    }

    //if the friend does not already exist, add it to the list of friends
    if ($has_friend == false) {
        echo 'false';
        $friends .= "$friend_id,";
        $sql = "UPDATE $usertable SET friends = :friends WHERE id = :uid";
        $sth = PDO_prepare($dbh, $sql);
        PDO_bind_value($sth, ':friends', $friends, PDO::PARAM_STR);
        PDO_bind_value($sth, ':uid', $uid, PDO::PARAM_INT);
        PDO_execute_bound($sth);
    }
}

/**
 * Removes a friend from the specified user's friends list
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @param int $friend_id The id of the friend who is being removed.
 * @param int $uid The id of the user who is removing this friend.
 * @return void
 */
function remove_friend($dbh, $friend_id, $uid)
{
    global $usertable;
    $friends = '';

    //retrieve a list of friends
    $sql = "SELECT friends FROM $usertable where id = :uid ";
    $sth = PDO_prepare($dbh, $sql);
    PDO_bind_value($sth, ':uid', $uid, PDO::PARAM_INT);
    PDO_execute_bound($sth);
    $friends = PDO_fetch_all_assoc($sth);

    //change list from array to string
    $friends = $friends[0]['friends'];
    //separate string into an array with a key for each friend
    $friend_arr = explode(',', $friends);
    //check if the friend exisits
    $has_friend = false;
    foreach ($friend_arr as $key => $friend) {
        if ($friend == $friend_id) {
            $has_friend = true;
            //remove the friend from the array
            unset($friend_arr[$key]);
            break;
        }
    }

    //if the friend exists, remove it from the list of friends
    if ($has_friend == true) {
        //turn the array back into a string with commas as delimeters
        $friends = implode(',', $friend_arr);
        $sql = "UPDATE $usertable SET friends = :friends WHERE id = :uid";
        $sth = PDO_prepare($dbh, $sql);
        PDO_bind_value($sth, ':friends', $friends, PDO::PARAM_STR);
        PDO_bind_value($sth, ':uid', $uid, PDO::PARAM_INT);
        PDO_execute_bound($sth);
    }
}
