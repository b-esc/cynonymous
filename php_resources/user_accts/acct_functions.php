<?php
include "../utilities/utilities.php";
include("../pdo_wrapper/pdo_wrapper_functions.php");

/**
 * Attempts to verify a user's account by their username and the verification code given to them in a confirmation email.
 *
 * @param string $username The username of the account being verified.
 * @param string $code The verification code passed to the user in an email.
 * @return bool Indication success or failure of the verification.
 */
function attempt_verification($username, $code)
{
    $DBH = PDO_connect_DB();
    global $usertable;
    $STH;
    $query = "SELECT username, email FROM $usertable WHERE username = :username";
    $exec_arr = ["username" => $username];

    $STH = PDO_prepare($DBH, $query);
    PDO_execute($STH, $exec_arr);
    $val_arr = PDO_fetch_all_assoc($STH);
    if (verification_hash($val_arr[0]["username"], $val_arr[0]["email"]) == $code) {

        //echo $val_arr[0]["board_id"];
        $query = "UPDATE $usertable SET verified = 1 WHERE username= :username";
        $exec_arr = ["username" => $username];
        $STH = PDO_prepare($DBH, $query);
        PDO_execute($STH, $exec_arr);

    } else {
        echo "not the same<br>";
        echo verification_hash($val_arr[0]["username"], $val_arr[0]["email"]) . "<br>";
        echo $code . "<br>";
        echo $username . "<br>";
        echo $val_arr[0]["username"] . "<br>";
        echo $val_arr[0]["email"] . "<br>";
        echo $id . "<br>";
        //printr($val_arr);
        return false;
    }
}

/**
 * Sends a confirmation email to a user.
 *
 * @param string $email The email address to send the email to.
 * @param string $verification_code The verification code to send with the email.
 * @param string $username The username of the user in question.
 * @return void
 */
function send_email($email, $verification_code, $username)
{
    $subject = "Confirm your account!";
    $message = "follow this link: http://proj-309-vc-7.cs.iastate.edu/php_resources/user_accts/confirm_account.php?uname=$username&ver_code=$verification_code";
    $header = "From: CyNonymous <chis@proj-309-vc-7.cs.iastate.edu>";
    mail($email, $subject, $message, $header);
}

/**
 * Verifies the provided credentials
 * @param PDO_DB_CONNECTION $DBH The database connection to use
 * @param string $uname The username to verify
 * @param string $upass The password to verify
 * @return array Info about the user being verified
 */
function verify_credientials($DBH, $uname, $upass)
{
    //echo $_SESSION["just_created"];
    $login_info;
    global $usertable;
    $exec_params;
    $query;

    if(isset($_SESSION["just_created"])){

        $exec_params = ["uname" => $uname, "upass" => $upass, "banned" => 0];
        $query = "Select privilege, id, username, last_name, first_name from " . $usertable . " WHERE username=:uname AND password=:upass AND banned = :banned ORDER BY id DESC";

    } else {
        $exec_params = ["uname" => $uname, "upass" => $upass, "verified" => 1, "banned" => 0];
        $query = "Select privilege, id, username, last_name, first_name from " . $usertable . " WHERE username=:uname AND password=:upass and verified = :verified AND banned = :banned ORDER BY id DESC";
    }

    $STH = PDO_prepare($DBH, $query);
    PDO_execute($STH, $exec_params);
    if (PDO_row_count($STH) == 1) {
        // echo "You Have Been Logged In";
        //$STH->setFetchMode(PDO::FETCH_ASSOC);
        $row = PDO_fetch_next_assoc($STH);
        $login_info["fname"] = $row["first_name"];
        $login_info["lname"] = $row["last_name"];
        $login_info["uid"] = $row["id"];
        $login_info["uname"] = $row["username"];
        $login_info["privilege"] = $row["privilege"];

    } else {
        //echo "LOGIN ERROR!!!!!!!!!!";
        return null;
    }
    return $login_info;
}
/**
 * Sets the password of the specified user
 *
 * @param PDO_DB_CONNECTION $DBH The database connection to use
 * @param int $uid The id of the user to change
 * @param string $pass The password to use
 * @return void
 */
function set_passwd($DBH, $uid, $pass)
{
    global $usertable;
    $query = "UPDATE $usertable SET password = :pass WHERE id = :uid";
    $exec_arr = ["pass" => $pass, "uid" => $uid];
    $STH = PDO_prepare($STH, $query);
    PDO_execute($STH, $exec_arr);
}
