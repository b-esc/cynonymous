<?PHP session_start();
//Ryan Buls
include "acct_functions.php"; // includes DB_INFO already, if needed
//include("send_confirm_email.php");
$uname = $_POST["user"];
$upass = $_POST["pass"];
$fname = $_POST["fname"];
$email = $_POST["email"];
$lname = $_POST["lname"];

$DBH = connect_DB();

$free_id = get_unused_user_id($DBH);
$exec_arr;
$query;
if ($free_id) {
    $query = "INSERT INTO " . $usertable . " (id, username,password,privilege,first_name,last_name,email) VALUES (:id, :uname, :upass, 1, :fname, :lname, :email)";
    $exec_arr = ["uname" => $uname, "upass" => $upass, "fname" => $fname, "email" => $email, "lname" => $lname, "id" => $free_id];
} else {
    $query = "INSERT INTO " . $usertable . " (username,password,privilege,first_name,last_name,email) VALUES (:uname, :upass, 1, :fname, :lname, :email)";
    $exec_arr = ["uname" => $uname, "upass" => $upass, "fname" => $fname, "email" => $email, "lname" => $lname];
}

try {
    //set_time_limit(0);

    //ob_start();
    $STH = $DBH->prepare($query);
    //echo $STH;
    $STH->execute($exec_arr);
    $_SESSION["just_created"] = true;
    echo "YAY! You Have Been Added!!";
    //header('Connection: close');
    //header('Content-Length: ' . ob_get_length());
    //ob_end_flush();
    //ob_flush();
    //flush();

    $verification_code = verification_hash($uname, $email);
    send_email($email, $verification_code, $uname);

} catch (PDOException $e) {
    echo $e->getMessage();
}

$DBH = null;
