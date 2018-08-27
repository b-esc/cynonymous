<?PHP session_start();

include("acct_delete_functions.php");

//Must post "iamsure" with any value to confirm delete. prevents accidents.

if(isset($_SESSION["uid"]) && isset($_POST["iamsure"])){
    $DBH = connect_DB();
    delete_acct($DBH, $_SESSION["uid"]);
    include("../logout.php");
}