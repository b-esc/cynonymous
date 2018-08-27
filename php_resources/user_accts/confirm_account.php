<?php 
//Ryan Buls
    include("acct_functions.php");


    if(isset($_GET["uname"]) && isset($_GET["ver_code"])){
        if(attempt_verification($_GET["uname"],$_GET["ver_code"])){
            echo "SUCCESS";
        } else {
            echo "FAILED";
        }
    }
    