<?PHP
    //Ryan Buls
    include("retrieve_functions.php");
    
    if(isset($_POST["thread_url"])){
        $DBH=connect_DB();
        $id = (int)get_thread_id($DBH, $_POST["thread_url"]);
        echo json_encode(get_posts($DBH,$id));
    }

    