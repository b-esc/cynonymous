<?php
//include("../utilities/utilities.php");

/**
 * Gets an unused ID to make a new post with. Returns 0 if none avaliable.
 *
 * @param PDO_DB_CONNECTION $DBH The database connection to use.
 * @return int The avaliable post_id, or zero.
 */
function get_unused_post_id($DBH)
{
    global $unused_id_table;
    $query = "SELECT post_id FROM $unused_id_table WHERE post_id IS NOT NULL LIMIT 1";
    //echo $query;
    $STH = PDO_prepare($DBH, $query);
    PDO_execute($STH, []);
    if (PDO_row_count($STH) == 1) {
        $data = PDO_fetch_all_assoc($STH);
        remove_from_unused_list($DBH, "post_id", $data[0]["post_id"]);
        return $data[0]["post_id"];
    } else {
        return 0;
    }

}

/**
 * Gets an unused ID to make a new board with. Returns 0 if none avaliable.
 *
 * @param PDO_DB_CONNECTION $DBH The database connection to use.
 * @return int The avaliable board_id, or zero.
 */
function get_unused_board_id($DBH)
{
    global $unused_id_table;
    $query = "SELECT board_id FROM $unused_id_table WHERE board_id IS NOT NULL LIMIT 1";
    //echo $query;
    $STH = PDO_prepare($DBH, $query);
    PDO_execute($STH, []);
    if (PDO_row_count($STH) == 1) {
        $data = PDO_fetch_all_assoc($STH);
        remove_from_unused_list($DBH, "board_id", $data[0]["board_id"]);
        return $data[0]["board_id"];
    } else {
        return 0;
    }

}

/**
 * Gets an unused ID to make a new thread with. Returns 0 if none avaliable.
 *
 * @param PDO_DB_CONNECTION $DBH The database connection to use.
 * @return int The avaliable thread_id, or zero.
 */
function get_unused_thread_id($DBH)
{
    global $unused_id_table;
    $query = "SELECT thread_id FROM $unused_id_table WHERE thread_id IS NOT NULL LIMIT 1";
    //echo $query;
    $STH = PDO_prepare($DBH, $query);
    PDO_execute($STH, []);
    if (PDO_row_count($STH) == 1) {
        $data = PDO_fetch_all_assoc($STH);
        remove_from_unused_list($DBH, "thread_id", $data[0]["thread_id"]);
        return $data[0]["thread_id"];
    } else {
        return 0;
    }

}

/**
 * Gets an unused ID to make a new user with. Returns 0 if none avaliable.
 *
 * @param PDO_DB_CONNECTION $DBH The database connection to use.
 * @return int The avaliable user_id, or zero.
 */
function get_unused_user_id($DBH)
{
    global $unused_id_table;
    $query = "SELECT user_id FROM $unused_id_table WHERE user_id IS NOT NULL LIMIT 1";
    //echo $query;
    $STH = PDO_prepare($DBH, $query);
    PDO_execute($STH, []);
    if (PDO_row_count($STH) == 1) {
        $data = PDO_fetch_all_assoc($STH);
        remove_from_unused_list($DBH, "user_id", $data[0]["user_id"]);
        return $data[0]["user_id"];
    } else {
        return 0;
    }

}

/**
 * Removes a specified entry from the list of unused ids.
 *
 * @param PDO_DB_CONNECTION $DBH The database connection to use.
 * @param string $type Either post_id, thread_id, board_id, or user_id, denoting which type of id is to be removed.
 * @param int $value The value to remove from the list.
 * @return void
 */
function remove_from_unused_list($DBH, $type, $value)
{
    global $unused_id_table;
    $query = "DELETE FROM $unused_id_table WHERE $type = :value";
    //echo $query;
    $STH = PDO_prepare($DBH, $query);
    PDO_execute($STH, ["value" => $value]);
}

/**
 * Add a recently freed board id to the list of unused boards.
 *
 * @param PDO_DB_CONNECTION $DBH THe database connection to use.
 * @param int $value The board_id to add to the list.
 * @return void
 */
function add_board_to_unused_list($DBH, $value)
{
    global $unused_id_table;
    $query = "INSERT INTO $unused_id_table(board_id) VALUES (:board_id)";
    //echo $query;
    $STH = PDO_prepare($DBH, $query);
    PDO_execute($STH, ["board_id" => $value]);
}

/**
 * Add a recently freed board id to the list of unused threads.
 *
 * @param PDO_DB_CONNECTION $DBH THe database connection to use.
 * @param int $value The thread_id to add to the list.
 * @return void
 */
function add_thread_to_unused_list($DBH, $value)
{
    global $unused_id_table;
    $query = "INSERT INTO $unused_id_table(thread_id) VALUES (:thread_id)";
    //echo $query;
    $STH = PDO_prepare($DBH, $query);
    PDO_execute($STH, ["thread_id" => $value]);
}

/**
 * Add a recently freed post id to the list of unused posts.
 *
 * @param PDO_DB_CONNECTION $DBH THe database connection to use.
 * @param int $value The post_id to add to the list.
 * @return void
 */
function add_post_to_unused_list($DBH, $value)
{
    global $unused_id_table;
    $query = "INSERT INTO $unused_id_table(post_id) VALUES (:post_id)";
    //echo $query;
    $STH = PDO_prepare($DBH, $query);
    PDO_execute($STH, ["post_id" => $value]);
}

/**
 * Add a recently freed user id to the list of unused posts.
 *
 * @param PDO_DB_CONNECTION $DBH THe database connection to use.
 * @param int $value The user_id to add to the list.
 * @return void
 */
function add_user_to_unused_list($DBH, $value)
{
    global $unused_id_table;
    $query = "INSERT INTO $unused_id_table(user_id) VALUES (:user_id)";
    //echo $query;
    $STH = PDO_prepare($DBH, $query);
    PDO_execute($STH, ["user_id" => $value]);
}
