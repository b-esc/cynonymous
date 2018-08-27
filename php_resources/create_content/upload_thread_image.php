<?php session_start();
include "../utilities/utilities.php";

include "../../php_resources/pdo_wrapper/pdo_wrapper_functions.php";
if (isset($_SESSION["uid"]) && isset($_FILES["fileToUpload"]["name"])) {
    $DBH = connect_DB();
}
    upload_pic($_POST["url"]);
}
/**
 * Uploads an image as the profile picture of the user trying to upload it.
 *
 * @param int $id The id of the current user.
 * @return void
 */
function upload_pic($DBH, $url)
{
    $target_dir = "/var/www/html/Images/Thread/";
    $target_url_dir = "proj-309-vc-7.cs.iastate.edu/Images/Thread/";
    $target_file = $target_dir . get_thread_id($url) . "." . strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
    $target_url_file = $target_url_dir . get_thread_id($url) . "." . strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));

    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (isset($_FILES["fileToUpload"]["tmp_name"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]); //should fail if non-image
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }
    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
            global $threadtable;
            $STH;

            $query = "UPDATE $threadtable SET thread_image_loc=:loc, thread_image_url=:url WHERE thread_url = :url_key";
            $STH = PDO_prepare($DBH, $query);

            PDO_execute($STH, ["loc" => $target_file, "url" => $target_url_file, "url_key" => $url]);
            $STH = null;
            $DBH = null;
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

/**
 * Echos the integer value of the thread id that corresponds to the given url.
 *
 * @param PDO_DB_CONNECTION $DBH The database connection to use.
 * @param string $url The url to return the thread id of.
 * @return int The id of the thread given by the url.
 */
function get_thread_id($DBH, $url)
{
    global $threadtable;
    $query = "SELECT thread_id from $threadtable WHERE thread_url = :url";
    $exec_arr = ["url" => $url];
    $STH = PDO_prepare($DBH, $query);
    PDO_execute($STH, $exec_arr);
    if (PDO_row_count($STH) == 1) {
        $val_arr = PDO_fetch_all_assoc($STH);
        $STH = null;
        return $val_arr[0]["thread_id"];
    } else {
        echo "ERROR";
    }
    $STH = null;
}

