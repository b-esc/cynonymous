<?php 
//Ryan Buls
    include("retrueve_functions.php");

    if(isset($_POST["thread_url"])){
        $DBH = connect_DB();
        echo json_encode(get_thread_id($DBH, $_POST["thread_url"]));
    }
