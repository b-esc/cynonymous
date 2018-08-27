<?PHP include("../../utilities/utilities.php");
include("../../pdo_wrapper/pdo_wrapper_functions.php");

function delete_acct($DBH, $uid){
    global $usertable;
        $query = "DELETE FROM $usertable WHERE id = :id";
        $STH = PDO_prepare($DBH, $query);
        PDO_execute($STH, ["id" => $uid]);
        add_user_to_unused_list($DBH, $uid);
}

