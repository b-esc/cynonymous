<?PHP session_start();
//Ryan Buls
    include("acct_functions.php");
    //echo $_SESSION["just_created"];
    $uname = $_POST["user"];
    $upass = $_POST["pass"];

    $DBH = connect_DB();
    $verified_info = verify_credientials($DBH, $uname, $upass);
    if($verified_info != null){
        $_SESSION = $verified_info;
        echo json_encode($verified_info["uname"]);
    } else {
        echo null;
    }
    $DBH = null;


?>