<?PHP
    //Ryan Buls


    include("retrieve_functions.php"); 
    $DBH=connect_DB();
    //echo "hello";
    //echo $_POST["board_url"];
    if(isset($_POST["board_url"])){
        $id = (int)get_board_id($DBH, $_POST["board_url"]);
       // echo $id;
        echo json_encode(get_threads($DBH,$id));
    }