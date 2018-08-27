<?php 
//Ryan Buls
    include("retrieve_functions.php");

    if(isset($_POST["board_url"])){
        $DBH = connect_DB();
        echo ret_board_id($DBH, $_POST["board_url"]);
    }
    