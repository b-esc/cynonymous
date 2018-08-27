<?php
include "../utilities/utilities.php";

/**
 * Deletes the board and all related threads and posts.
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @param int $board_id The id of the board to delete.
 * @return void
 */
function delete_board($dbh, $board_id)
{
    global $boardtable;
    delete_related_threads($dbh, $board_id);
    add_board_to_unused_list($dbh, $board_id);
    $sql = "DELETE FROM $boardtable WHERE board_id = :board_id";

    $sth = PDO_prepare($dbh, $sql);
    PDO_bind_value($sth, ":board_id", $board_id, PDO::PARAM_INT);
    PDO_execute_bound($sth);
}

/**
 * Deletes all threads that were contained on the specified board.
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @param int $board_id The id number of board that is being deleted.
 * @return void
 */
function delete_related_threads($dbh, $board_id)
{
    global $threadtable;

    $sql = "SELECT $thread_id FROM $threadtable WHERE board_id = :board_id";
    $sth = PDO_prepare($dbh, $sql);
    PDO_bind_value($sth, ":board_id", $board_id, PDO::PARAM_INT);
    PDO_execute_bound($sth);
    $related_threads = PDO_fetch_all_assoc($sth);
    foreach ($related_threads as $key => $thread) {
        $thread_id = $thread["thread_id"];
        delete_thread($dbh, $thread_id);
    }

}

/**
 * Deletes the content of the specified thread in the database.
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @param int $thread_id The id of the thread that you wish to delete.
 * @return void
 */
function delete_thread($dbh, $thread_id)
{
    global $threadtable;
    add_thread_to_unused_list($dbh, $thread_id);
    $board_id = find_board_id($dbh, $thread_id);

    if ($board_id == -1) {
        echo 'Error obtaining board number when deleting thread. Action cancelled.';
        return;
    }

    decrement_thread_count($dbh, $board_id);
    delete_related_posts($dbh, $thread_id);

    $sql = "DELETE FROM $threadtable WHERE thread_id = :thread_id";

    $sth = PDO_prepare($dbh, $sql);
    PDO_bind_value($sth, ":thread_id", $thread_id, PDO::PARAM_INT);
    PDO_execute_bound();

}

/**
 * Deletes all posts related to the specified thread.
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @param int $thread_id The id of thread to delete the related posts from.
 * @return void
 */
function delete_related_posts($dbh, $thread_id)
{
    global $posttable;

    $sql = "SELECT post_id FROM $posttable WHERE thread_id = :thread_id";
    $sth = PDO_prepare($dbh, $sql);
    PDO_bind_value($sth, ":thread_id", $thread_id, PDO::PARAM_INT);
    PDO_execute_bound($sth);
    $related_posts = PDO_fetch_all_assoc($sth);
    foreach ($related_posts as $key => $post) {
        $post_id = $post["post_id"];
        delete_post($dbh, $post_id);
    }
}

/**
 * Returns the id of the board which contains the specified thread, or -1 on failure.
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @param int $thread_id The id of thread which needs to be located on a board.
 * @return void board id or -1 on failure
 */
function find_board_id($dbh, $thread_id)
{
    global $threadtable;

    $param_arr = array(":thread_id" => $thread_id);
    $sql = "SELECT board_id FROM $threadtable WHERE thread_id = :thread_id;";
    $sth = PDO_prepare($dbh, $sql);
    PDO_execute($sth, $param_arr);
    if (PDO_row_count($sth) >= 0) {
        $result = PDO_fetch_next_assoc($sth);
        $board_id = $result["board_id"];
    } else {
        return -1;
    }
/*
try {
$sth = $dbh->prepare($sql);
$sth->execute($param_arr);

$result = $sth->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
echo $e->getMessage();
return -1;
}
 */
    return $board_id;
}

/**
 * Decreases the number of threads on the specified board by 1.
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @param int $board_id The id number of the board this thread was on.
 * @return void
 */
function decrement_thread_count($dbh, $board_id)
{
    global $boardtable;

    $param_arr = array(":board_id" => $board_id);
    $sql = "UPDATE $boardtable SET num_threads = num_threads - 1
	WHERE board_id = :board_id";

    $sth = PDO_prepare($dbh, $sql);
    PDO_execute($sth, $param_arr);

}

/**
 * Deletes the specified post.
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @param int $post_id The id of the post to delete.
 * @return void
 */
function delete_post($dbh, $post_id)
{
    global $posttable;
    add_post_to_unused_list($dbh, $post_id);
    $thread_id = find_thread_id($dbh, $post_id);
    $board_id = find_board_id($dbh, $thread_id);

    if ($board_id == -1) {
        echo 'Error obtaining board number when deleting post. Action cancelled.';
        return;
    }

    if ($thread_id == -1) {
        echo 'Error obtaining thread number when deleting post. Action cancelled.';
        return;
    }

    $sql = "DELETE FROM $posttable WHERE post_id = :post_id";
    $sth = PDO_prepare($sth, $sql);
    PDO_bind_value($sth, ":post_id", $post_id, PDO::PARAM_INT);
    PDO_execute_bound($sth);
    decrement_num_posts_board($dbh, $board_id);
    decrement_num_posts_thread($dbh, $thread_id);
}

/**
 * Finds the id of the parent thread for the specified post.
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @param int $post_id The id of the post to locate the parent thread for.
 * @return The id of the parent thread.
 */
function find_thread_id($dbh, $post_id)
{
    global $posttable;

    $param_arr = array(":post_id" => $post_id);
	$sql = "SELECT thread_id FROM $posttable WHERE post_id = :post_id;";
	$sth = PDO_prepare($dbh, $sql);
	PDO_execute($sql, $param_arr);
	if(PDO_row_count($sth) >= 0){
		$result = PDO_fetch_next_assoc($sth);
        $thread_id = $result["thread_id"];
	} else {
		return -1;
	}

    
    return $thread_id;
}

/**
 * Decreases the number of posts on the parent board by 1.
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @param int $board_id The id of the board that lost a post.
 * @return void
 */
function decrement_num_posts_board($dbh, $board_id)
{
    global $boardtable;

    $param_arr = array(":board_id" => $board_id);
    $sql = "UPDATE $boardtable SET num_posts = num_posts - 1
	WHERE board_id = :board_id";

	$sth = PDO_prepare($dbh, $sql);
	PDO_execute($sth, $param_arr);
}

/**
 * Decrements the number of posts on the parent thread by 1.
 *
 * @param PDO_DB_CONNECTION $dbh The connection handle to the database.
 * @param int $thread_id The id of the thread that lost a post.
 * @return void
 */
function decrement_num_posts_thread($dbh, $thread_id)
{
    global $threadtable;

    $param_arr = array(":thread_id" => $thread_id);
    $sql = "UPDATE $threadtable SET num_posts = num_posts - 1
	WHERE thread_id = :thread_id";

	$sth = PDO_prepare($dbh, $sql);
	PDO_execute($sth, $param_arr);

  
}
