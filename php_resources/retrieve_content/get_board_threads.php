<?PHP
    //Ryan Buls


    include("retrieve_functions.php");
    $DBH=connect_DB();
    if(isset($_POST["board_id"])){
        echo json_encode(get_threads($DBH,(int)$_POST["board_id"]));
    }