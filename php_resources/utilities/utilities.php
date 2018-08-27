<?php
//Ryan Buls
include("db_info.php");
include("reuse_ids.php");
include("sanitization.php");
//include("../pdo_wrapper/pdo_wrapper_functions.php");

/**
 * Creates a connection to our database.
 *
 * @return PDO_DB_CONNECTION The connection to the database.
 */
function connect_DB(){
    try {
        global $host, $database, $user, $pass;
        $DBH = new PDO("mysql:host=$host;dbname=$database; charset=utf8mb4", $user, $pass);
        $DBH->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    
    }
    catch(PDOException $e) {
        echo $e->getMessage();
    }

    return $DBH;
}
/**
 * A hashing function used to generate a verification code from a username and email.
 *
 * @param string $username The username of the user to generate a verification code for.
 * @param string $email The email address of the user to generate a verification code for.
 * @return string The verification code unique to that user.
 */
function verification_hash($username, $email){
    return hash("ripemd160",$username.$email);
}



?>