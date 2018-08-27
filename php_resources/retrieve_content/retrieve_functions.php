<?PHP
include "../utilities/utilities.php";
include("../pdo_wrapper/pdo_wrapper_functions.php");

/**
 * Echos all boards to the client as a json encoded associative array.
 *
 * @param PDO_DB_CONNECTION $DBH The Database connection to use.
 * @return array An array containing all boards
 */
function get_all_boards($DBH)
{
    global $boardtable;

    $STH;
    $query = "SELECT board_id, name, description, num_threads num_posts, date_created, board_url FROM $boardtable ORDER BY date_created DESC";
    $exec_arr = [];
    $STH = PDO_prepare($DBH, $query);
    PDO_execute($STH, $exec_arr);
    
    //tells the query how to return the data --> As an associative array.
    $array_to_return = PDO_fetch_all_assoc($STH);
    //$DBH = null;
    //print_r($array_to_return);
    //echo json_encode($array_to_return);
    return $array_to_return;
    //Associative array with keys "name", "description", "num_posts", "date_created"
    //each thread has it's own numeric entry 0,1,2,3... that contains the above keys
}

/**
 * Returns a board id from a given board url using the provided database connection.
 *
 * @param PDO_DB_CONNECTION $DBH The Database connection to use.
 * @param string $url The board url to locate.
 * @return int ID of the board with the provided url.
 */
function get_board_id($DBH, $url)
{
    global $boardtable;

    //$DBH = connect_DB();
    $query = "SELECT board_id from $boardtable WHERE board_url = :url";
    $exec_arr = ["url" => $url];
    $STH = PDO_prepare($DBH, $query);
    PDO_execute($STH, $exec_arr);

    if (PDO_row_count($STH) == 1) {
        $val_arr = PDO_fetch_all_assoc($STH);
        return $val_arr[0]["board_id"];
    } else {
        return 0;
    }
    $STH = null;
    //$DBH = null;
}

/**
 * Echos the integer value of the thread id that corresponds to the given url.
 *
 * @param PDO_DB_CONNECTION $DBH The database connection to use.
 * @param string $url The url to return the thread id of.
 * @return int The id of the thread given by the url.
 */
function get_thread_id($DBH, $url)
{
    global $threadtable;
    $query = "SELECT thread_id from $threadtable WHERE thread_url = :url";
    $exec_arr = ["url" => $url];
    $STH = PDO_prepare($DBH, $query);
    PDO_execute($STH, $exec_arr);
    if (PDO_row_count($STH) == 1) {
        $val_arr = PDO_fetch_all_assoc($STH);
        $STH = null;
        return $val_arr[0]["thread_id"];
    } else {
        echo "ERROR";
    }
    $STH = null;
}

/**
 * Echos threads for the board specified by the board id.
 *
 * @param PDO_DB_CONNECTION $DBH The databse conncection to use.
 * @param int $board_id The board id to return the threads of.
 * @return array The threads on the provided board.
 */
function get_threads($DBH, $board_id)
{
    global $threadtable;
    $STH;
    $query = "SELECT thread_id, name, description, num_posts, date_created, thread_url, thread_image_url FROM $threadtable WHERE board_id = :board ORDER BY date_created DESC";
    $exec_arr = ["board" => $board_id];
    $STH = PDO_prepare($DBH, $query);
    PDO_execute($STH, $exec_arr);

    //tells the query how to return the data --> As an associative array.
    $array_to_return = PDO_fetch_all_assoc($STH);
    //$DBH = null;
    //print_r($array_to_return);
    //echo json_encode($array_to_return);
    return $array_to_return;
    //Associative array with keys "name", "description", "num_posts", "date_created"
    //each thread has it's own numeric entry 0,1,2,3... that contains the above keys
}

/**
 * Returns an array containing the relevent information of 3 random boards.
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @return array $ret_arr An array with the relevent information to display a board.
 */
function get_featured_boards($dbh)
{
    global $boardtable;

    $board_arr = array();
    //get all of the current boards and relevent infromation
    $sql = "SELECT board_id, name, description, num_threads, num_posts, date_created, board_url FROM $boardtable";
    $sth = PDO_prepare($dbh, $sql);
    PDO_execute($sth, []);
    $board_arr = PDO_fetch_all_assoc($sth);

    //count the number of boards created
    $size = count($board_arr);

    //select a board from the first third, second third, and last third of created boards
    $first = rand(0, $size / 3);
    $second = rand($size / 3 + 1, (($size / 3) * 2));
    $third = rand((($size / 3) * 2) + 1, $size - 1);

    //get the relevent information of each board and put it into an array
    $ret_arr = array();
    $ret_arr[] = $board_arr[$first];
    $ret_arr[] = $board_arr[$second];
    $ret_arr[] = $board_arr[$third];

    return $ret_arr;
}

/**
 * Returns an array containing the relevent information of a random thread.
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @return array $ret_arr An array with the relevent information to display a thread.
 */
function get_safari_thread($dbh)
{
    global $threadtable;

    $thread_arr = array();
    //get all of the current threads and relevent information
    $sql = "SELECT thread_id, name, description, num_posts, date_created, thread_url FROM $threadtable";
    $exec_arr = [];
    $sth = PDO_prepare($dbh, $sql);
    PDO_execute($sth, $exec_arr);
    $thread_arr = PDO_fetch_all_assoc($sth);
  
    //count the number of threads created
    $size = count($thread_arr);

    //select a random thread
    $random_pos = rand(0, $size - 1);

    //get the thread information and put it into an array.
    $ret_arr = array();
    $ret_arr[] = $thread_arr[$random_pos];

    return $ret_arr;
}

/**
 * Echos featured posts to the client as a json encoded associative array.
 *
 * @param PDO_DB_CONNECTION The database connection to use.
 * @return array The information about the featured posts.
 */
function get_featured_posts($DBH)
{

    global $posttable;
    //$DBH = connect_DB();

    $query = "SELECT title, content FROM $posttable WHERE anonymous = :anon ORDER BY date_created DESC LIMIT 3";
    $exec_arr = ["anon" => "0"];
    $STH = PDO_prepare($DBH, $query);
    PDO_execute($STH, $exec_arr);


    $data = PDO_fetch_all_assoc($STH);
    
    //$DBH = null;
    $STH = null;

    //echo json_encode($data);
    return $data;
    //print_r($data);
}

/**
 * Retrieves a list of friends as an array.
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @param int $uid The id of the user whose friends list is to be retrieved.
 * @return array $friend_arr an array that contains the uid of a friend at each index.
 */
function get_friends_list($dbh, $uid)
{
    global $usertable;

    $friends = '';

    //retrieve a list of friends
    $sql = "SELECT friends FROM $usertable where id = :uid ";
    $sth = PDO_prepare($dbh, $sql);
    PDO_bind_value($sth,':uid',$uid, PDO::PARAM_INT);
    PDO_execute_bound($sth);
    $friends = PDO_fetch_all_assoc($sth);


    //change list from array to string
    $friends = $friends[0]['friends'];
    //separate string into an array with a key for each friend
    $friend_arr = explode(',', $friends);

    unset($friend_arr[count($friend_arr) - 1]);

    return $friend_arr;
}

/**
 * Echos all posts for the corresponding thread id to the client in a json encoded associative array.
 *
 * @param PDO_DB_CONNECTION $DBH The database connection to use.
 * @param string $thread_id THe thread id to echo the posts of.
 * @return array The posts on the specified thread.
 */
function get_posts($DBH, $thread_id)
{

    global $posttable, $usertable;
    $STH;

        $query = "SELECT $posttable.post_id, $posttable.title, $posttable.content, $posttable.anonymous, $usertable.username
                from $posttable, $usertable WHERE $posttable.thread_id=:thread AND $usertable.id = $posttable.creator_uid
                ORDER BY $posttable.post_id DESC";
        $exec_arr = ["thread" => $thread_id];
        $STH = PDO_prepare($DBH, $query);
        PDO_execute($STH, $exec_arr);
    //tells the query how to return the data --> As an associative array.
    $array_to_return = PDO_fetch_all_assoc($STH);
    //$DBH = null;
    //print_r($array_to_return);

    foreach ($array_to_return as $key => $row) {
        if ($row["anonymous"]) {
            $array_to_return[$key]["username"] = "ANONYMOUS";
        }
    }

    //echo json_encode($array_to_return);
    return $array_to_return;
}

/**
 * Echos all posts made by the user with the given id to the client as a json encoded associative array.
 * @param PDO_DB_CONNECTION $DBH The database connection to use.
 * @param int $id The id of the user to find the posts of.
 * @return array All posts made by the specified user
 */
function user_posts($DBH, $id)
{
    //$DBH = connect_DB();
    global $posttable;
    $query = "SELECT title, content, date_created FROM $posttable WHERE creator_uid=:uid ORDER BY post_id DESC";
    $exec_arr = ["uid" => $id];
    $STH = PDO_prepare($DBH, $query);
    PDO_execute($STH, $exec_arr);

    $array_to_return = PDO_fetch_all_assoc($STH);
    //$DBH = null;
    $STH = null;
    //echo json_encode($array_to_return);
    return $array_to_return;
    //echo "Hello";

}


function user_image($DBH, $id)
{
    //$DBH = connect_DB();
    global $usertable;
    $query = "SELECT profile_image_url FROM $usertable WHERE id=:uid";
    $exec_arr = ["uid" => $id];
    $STH = PDO_prepare($DBH, $query);
    PDO_execute($STH,$exec_arr);

    $STH->setFetchMode(PDO::FETCH_ASSOC);
    $array_to_return = PDO_fetch_all_assoc($STH);
    //$DBH = null;
    $STH = null;
    //echo json_encode($array_to_return);
    return $array_to_return[0]["profile_image_url"];
    //echo "Hello";

}


/**
 * Returns an array containing the logged-in user's bio.
 * @param PDO_DB_CONNECTION $DBH The database connection to use.
 * @param int $uid The id of the user that is logged in.
 * @return array $bio_array An array containing the bio of the logged in user.
 */
function get_user_bio($dbh, $uid)
{
    global $usertable;

    $sql = "SELECT bio FROM $usertable WHERE id = :uid";
    $sth = PDO_prepare($dbh,$sql);
    PDO_bind_value($sth,":uid",$uid, PDO::PARAM_INT);
    PDO_execute_bound($sth);

    $bio_array = PDO_fetch_all_assoc($sth);
    return $bio_array;
}


/**
 * Returns an array containing the logged-in user's preferences.
 * @param PDO_DB_CONNECTION $DBH The database connection to use.
 * @param int $uid The id of the user that is logged in.
 * @return array $pref_array An array containing the preferences of the logged in user.
 */
function get_user_preferences($dbh, $uid)
{
    global $preftable;

    $sql = "SELECT background_color, private FROM $preftable WHERE id = :uid";
    $sth = PDO_prepare($dbh, $sql);
    PDO_bind_value($sth, ":uid", $uid, PDO::PARAM_INT);
    PDO_execute_bound($sth);
    
    $pref_array = PDO_fetch_all_assoc($sth);
    return $pref_array;
}

/**
 * Returns an array containing the username for the provided uid.
 * @param PDO_DB_CONNECTION $DBH The database connection to use.
 * @param int $uid The id of the user that you want the username for.
 * @return array $bio_array An array containing the username for the corresponding uid.
 */
function get_username_from_id($dbh, $uid)
{
    global $usertable;

    $sql = "SELECT username FROM $usertable WHERE id = :uid";
    $sth = PDO_prepare($dbh, $sql);
    PDO_bind_value($sth, ":uid", $uid, PDO::PARAM_INT);
    PDO_execute_bound($sth);
 

    $username_array = PDO_fetch_all_assoc($sth);
    return $username_array;
}
