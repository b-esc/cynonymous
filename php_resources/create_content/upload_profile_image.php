<?php session_start();
include "../utilities/utilities.php";
include "../pdo_wrapper/PDO_wrapper_functions.php";

if (isset($_SESSION["uid"]) && isset($_FILES["fileToUpload"]["name"])) {
    upload_pic($_SESSION["uid"]);
}
/**
 * Uploads an image as the profile picture of the user trying to upload it.
 *
 * @param int $id The id of the current user.
 * @return void
 */
function upload_pic($id)
{
    $target_dir = "/var/www/html/Images/Profile/";
    $target_url_dir = "proj-309-vc-7.cs.iastate.edu/Images/Profile/";
    $target_file = $target_dir . $id . "." . strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
    $target_url_file = $target_url_dir . $id . "." . strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
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
            $DBH = connect_DB();
            global $usertable;
            $STH;

            $query = "UPDATE $usertable SET profile_image_loc=:loc, profile_image_url=:url WHERE id = :id";
            $STH = PDO_prepare($DBH, $query);

            PDO_execute($STH, ["loc" => $target_file, "url" => $target_url_file, "id" => $id]);
            $STH = null;
            $DBH = null;
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
