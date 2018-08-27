<?PHP
    //Ryan Buls

    include("retrieve_functions.php");
    
    if(isset($_POST["thread_id"])){
        $DBH = connect_DB();
        echo json_encode(get_posts($DBH, (int)$_POST["thread_id"]));
    }

     