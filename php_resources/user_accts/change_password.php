<?php session_start();
include "acct_functions.php";

if(isset($_POST["orig_pass"]) && isset($_POST["new_pass"])){
    echo "hi";
    $DBH = connect_DB();
    if(verify_credientials($DBH, $_SESSION["uname"],$_POST["orig_pass"])){
        echo "hi";

        set_passwd($DBH, $_SESSION["uid"],$_POST["new_pass"]);
    }
} else {
    echo -1;
}
