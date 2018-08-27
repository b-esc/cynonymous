<?php
include "../utilities/utilities.php";
include("../pdo_wrapper/pdo_wrapper_functions.php");

/**
 * Creates a new board for users to access
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @param int $creator_uid The id of the user creating the board
 * @param string $name The name of the board being created
 * @param string $description The description of the content that can be found on this board
 * @param int $family_friendly Value of 1 or 0 which signals if a board is family friendly or not
 * @param string $board_url The all lowercase no spaces name of the board
 * @return void
 */
function create_board($dbh, $creator_uid, $name, $description, $family_friendly, $board_url)
{
    global $boardtable;
    $free_id = get_unused_board_id($dbh); // if zero, use auto-increment value
    if ($free_id) {
        $sql = "INSERT INTO $boardtable(board_id, creator_uid, name, description, family_friendly, board_url)
        VALUES(:board_id, :creator_uid, :name, :description, :family_friendly, :board_url)";
    } else {
        $sql = "INSERT INTO $boardtable(creator_uid, name, description, family_friendly, board_url)
        VALUES(:creator_uid, :name, :description, :family_friendly, :board_url)";
    }
    $sth = PDO_prepare($dbh, $sql);

    if ($free_id) {
        PDO_bind_value($sth, ":creator_uid", $creator_uid, PDO::PARAM_INT);
        PDO_bind_value($sth, ":name", $name, PDO::PARAM_STR);
        PDO_bind_value($sth, ":description", $description, PDO::PARAM_STR);
        PDO_bind_value($sth, ":family_friendly", $family_friendly, PDO::PARAM_INT);
        PDO_bind_value($sth, ":board_url", $board_url, PDO::PARAM_STR);
        PDO_bind_value($sth, ":board_id", $free_id, PDO::PARAM_INT);
    } else {
        PDO_bind_value($sth, ":creator_uid", $creator_uid, PDO::PARAM_INT);
        PDO_bind_value($sth, ":name", $name, PDO::PARAM_STR);
        PDO_bind_value($sth, ":description", $description, PDO::PARAM_STR);
        PDO_bind_value($sth, ":family_friendly", $family_friendly, PDO::PARAM_INT);
        PDO_bind_value($sth, ":board_url", $board_url, PDO::PARAM_STR);
    }
    PDO_execute_bound($sth);

}

/**
 * Creates a new board request for admins to review for approval.
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @param int $requestor_uid The id of the user requesting the board
 * @param string $name The name of the board being requested
 * @param string $description The description of the content that can be found on this board
 * @param int $family_friendly Value of 1 or 0 which signals if a board is family friendly or not
 * @return void
 */
function create_board_request($dbh, $requestor_uid, $name, $description, $family_friendly)
{
    global $requesttable;

    $sql = "INSERT INTO $requesttable(requestor_uid, name, description, family_friendly)
        VALUES(:requestor_uid, :name, :description, :family_friendly)";

    $sth = PDU_prepare($dbh, $sql);

    PDO_bind_value($sth, ":requestor_uid", $requestor_uid, PDO::PARAM_INT);
    PDO_bind_value($sth, ":name", $name, PDO::PARAM_STR);
    PDO_bind_value($sth, ":description", $description, PDO::PARAM_STR);
    PDO_bind_value($sth, ":family_friendly", $family_friendly, PDO::PARAM_INT);

    PDO_execute_bound($sth);
}

/**
 * Creates a reply to a post that already exists.
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @param int $thread_id The id number of the thread this post will be on.
 * @param int $board_id The id number of the board this post will be on.
 * @param int $creator_uid The id of the user creating this post reply.
 * @param int $parent_id The id number of the post being responded to.
 * @param string $title The title of the post reply.
 * @param string $content The content of the post reply.
 * @param int $anonymous A bit of 0 for a public post or 1 to keep the user anonymous.
 * @return void
 */
function create_post_reply($dbh, $thread_url, $creator_uid, $parent_id, $title, $content, $anonymous)
{
    global $posttable;
    $nest_level;
    $thread_id = get_thread_id($dbh, $thread_url);
    $board_id = get_board_id($dbh, $thread_id);

    $sql = "SELECT nest_level FROM $posttable WHERE post_id = :parent_id";
    $sth = PDO_prepare($dbh, $sql);
    PDO_bind_value($sth, ':parent_id', $parent_id, PDO::PARAM_INT);
    PDO_execute_bound($sth);
    $nest_level = PDO_fetch_all_assoc($sth);
    $nest_level = $nest_level[0]['nest_level'];
    echo $e->getMessage();

    $free_id = get_unused_post_id($dbh);
    if ($free_id) {
        $sql = "INSERT INTO $posttable(post_id, thread_id, board_id, creator_uid, parent_id, nest_level, title, content, anonymous)
            VALUES(:post_id, :thread_id, :board_id, :creator_uid, :parent_id, :nest_level, :title, :content, :anonymous)";
        $sth = PDO_prepare($dbh, $sql);
        PDO_bind_value($sth, ':post_id', $free_id, PDO::PARAM_INT);
    } else {
        $sql = "INSERT INTO $posttable(thread_id, board_id, creator_uid, parent_id, nest_level, title, content, anonymous)
            VALUES(:thread_id, :board_id, :creator_uid, :parent_id, :nest_level, :title, :content, :anonymous)";
        $sth = PDO_prepare($dbh, $sql);

    }
    PDO_bind_value($sth, ':thread_id', $thread_id, PDO::PARAM_INT);
    PDO_bind_value($sth, ':board_id', $board_id, PDO::PARAM_INT);
    PDO_bind_value($sth, ':creator_uid', $creator_uid, PDO::PARAM_INT);
    PDO_bind_value($sth, ':parent_id', $parent_id, PDO::PARAM_INT);
    PDO_bind_value($sth, ':nest_level', $nest_level + 1, PDO::PARAM_INT);
    PDO_bind_value($sth, ':title', $title, PDO::PARAM_STR);
    PDO_bind_value($sth, ':content', $content, PDO::PARAM_STR);
    PDO_bind_value($sth, ':anonymous', $anonymous, PDO::PARAM_INT);
    PDO_execute_bound($sth);

    increment_board_posts($dbh, $board_id);
    increment_thread_posts($dbh, $thread_id);

}

/**
 * Creates a post with the given parameters
 *
 * @param PDO_DB_CONNECTION $dbh The database connection to use.
 * @param int $thread_id The id of the thread the post belongs to.
 * @param int $board_id The id of the board the post belongs to.
 * @param int $creator_uid The user id of the creator of the post.
 * @param string $title The title of the post.
 * @param string $content The content of the post.
 * @param int $anonymous A 1 bit flag indicating whether the post should be stored as anonymous or not.
 * @return void
 */
function create_post($dbh, $thread_id, $board_id, $creator_uid, $title, $content, $anonymous)
{
    global $posttable;
    $free_id = get_unused_post_id($dbh);
    if ($free_id) {
        $sql = "INSERT INTO $posttable(post_id, thread_id, board_id, creator_uid, title, content, anonymous)
            VALUES(:post_id, :thread_id, :board_id, :creator_uid, :title, :content, :anonymous)";
        $sth = PDO_prepare($dbh, $sql);
        PDO_bind_value($sth, ':post_id', $free_id, PDO::PARAM_INT);

    } else {
        $sql = "INSERT INTO $posttable(thread_id, board_id, creator_uid, title, content, anonymous)
            VALUES(:thread_id, :board_id, :creator_uid, :title, :content, :anonymous)";
        $sth = PDO_prepare($dbh, $sql);
    }
    PDO_bind_value($sth, ':thread_id', $thread_id, PDO::PARAM_INT);
    PDO_bind_value($sth, ':board_id', $board_id, PDO::PARAM_INT);
    PDO_bind_value($sth, ':creator_uid', $creator_uid, PDO::PARAM_INT);
    PDO_bind_value($sth, ':title', $title, PDO::PARAM_STR);
    PDO_bind_value($sth, ':content', $content, PDO::PARAM_STR);
    PDO_bind_value($sth, ':anonymous', $anonymous, PDO::PARAM_INT);
    PDO_execute_bound($sth);

    increment_board_posts($dbh, $board_id);
    increment_thread_posts($dbh, $thread_id);

}

/**
 * Increments the number of posts that are on the board by 1.
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @param int $board_id The id number of the board this post will be on.
 * @return void
 */
function increment_board_posts($dbh, $board_id)
{
    global $boardtable;

    $sql = "UPDATE $boardtable SET num_posts = num_posts + 1 WHERE board_id = :board_id";
    $sth = PDO_prepare($dbh, $sql);
    PDO_bind_value($sth, ":board_id", $board_id, PDO::PARAM_INT);
    PDO_execute_bound($sth);
}

/**
 * Increments the number of posts on the specified thread by 1.
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @param int $thread_id The id number of the thread this post will be on.
 * @return void
 */
function increment_thread_posts($dbh, $thread_id)
{
    global $threadtable;

    $sql = "UPDATE $threadtable SET num_posts = num_posts + 1 WHERE thread_id = :thread_id";
    $sth = PDO_prepare($dbh, $sql);
    PDO_bind_value($sth, ":thread_id", $thread_id, PDO::PARAM_INT);
    PDO_execute_bound($sth);
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
    $STH = PDO_prepare($DBH, $query);

    PDO_execute($STH, ["url" => $url]);
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
 * Returns the id of the board that contains the given thread.
 *
 * @param PDO_DB_CONNECTION $DBH The database connection to use.
 * @param int $thread_id The id of the thread to search for.
 * @return int The board id of the given thread.
 */
function get_board_id($DBH, $thread_id)
{
    global $threadtable;

    $query = "SELECT board_id from $threadtable WHERE thread_id = :id";
    $STH = PDO_prepare($DBH, $query);

    PDO_execute($STH, ["id" => $thread_id]);
    if (PDO_row_count($STH) == 1) {
        $val_arr = PDO_fetch_all_assoc($STH);
        $STH = null;
        return $val_arr[0]["board_id"];
    } else {
        echo "ERROR";
    }
    $STH = null;
}

/**
 * Returns the thread id of the thread specified by a url.
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @param string $url The url of the thread that is concatenated onto the main site url.
 * @return The id of the thread specified by the url.
 */
function board_url_to_id($DBH, $url)
{
    global $boardtable;

    $query = "SELECT board_id from $boardtable WHERE board_url = :url";
    $STH = PDO_prepare($DBH, $query);

    PDO_execute($STH, ["url" => $url]);
    if (PDO_row_count($STH) == 1) {
        $val_arr = PDO_fetch_all_assoc($STH);
        $STH = null;
        return $val_arr[0]["board_id"];
    } else {
        echo "ERROR";
    }
}

/**
 * Creates a thread using the specified values.
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @param int $board_id The id number of the board this post will be on.
 * @param int $creator_uid The id of the user creating this post reply.
 * @param string $name The desired name for the thread.
 * @param string $description The desired description for the thread.
 * @param string $thread_url The url that is unique to this thread.
 * @return void
 */
function create_thread($dbh, $board_id, $creator_uid, $name, $description, $thread_url)
{
    global $threadtable;

    $free_id = get_unused_thread_id($dbh);
    if ($free_id) {
        $sql = "INSERT INTO $threadtable(thread_id, board_id, creator_uid, name, description, thread_url)
        VALUES(:thread_id, :board_id, :creator_uid, :name, :description, :thread_url)";
    } else {
        $sql = "INSERT INTO $threadtable (board_id, creator_uid, name, description, thread_url)
        VALUES(:board_id, :creator_uid, :name, :description, :thread_url)";
    }
    $sth = PDO_prepare($dbh, $sql);
    if ($free_id) {
        PDO_bind_value($sth, ":thread_id", $free_id, PDO::PARAM_INT);
    }
    PDO_bind_value($sth, ":board_id", $board_id, PDO::PARAM_INT);
    PDO_bind_value($sth, ":creator_uid", $creator_uid, PDO::PARAM_INT);
    PDO_bind_value($sth, ":name", $name, PDO::PARAM_STR);
    PDO_bind_value($sth, ":description", $description, PDO::PARAM_STR);
    PDO_bind_value($sth, ":thread_url", $thread_url, PDO::PARAM_STR);
    PDO_execute_bound($sth);

    increment_threads($dbh, $board_id);

}

/**
 * Increments the number of threads that are on the board by 1.
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @param int $board_id The id number of the thread this post will be on.
 * @return void
 */
function increment_threads($dbh, $board_id)
{
    global $boardtable;

    $sql = "UPDATE $boardtable SET num_threads = num_threads + 1 WHERE board_id = :board_id";
    $sth = $dbh->prepare($sql);
    PDO_bind_value($sth, ":board_id", $board_id, PDO::PARAM_INT);
    PDO_execute_bound($sth);
}
