<?PHP include "utilities.php";
include "../pdo_wrapper/pdo_wrapper_functions.php";
include "db_info.php";
<<<<<<< HEAD
=======

>>>>>>> origin/dev_client

clean_up();

/**
 * Cleans up the database anywhere there were failed querys.
 *
 * @return void
 */
function clean_up()
{
    $DBH = connect_DB();
    clean_undeleted_threads($DBH);
    clean_undeleted_posts($DBH);
<<<<<<< HEAD
    update_unused_user_list($DBH);
    update_unused_post_list($DBH);
    update_unused_board_list($DBH);
    update_unused_thread_list($DBH);

}

/**
 * Removes threads that belong to a board that no longer exists
 *
 * @param PDO_DB_CONNECTION $DBH The database connection to use
 * @return void
 */
function clean_undeleted_threads($DBH)
{
    global $posttable;
    global $threadtable;
    global $boardtable;

    //gets table of board_id, thread_id where board_id is null if no such board exists.
    $query = "SELECT $threadtable.thread_id, $boardtable.board_id FROM $threadtable LEFT JOIN $boardtable ON $threadtable.board_id=$boardtable.board_id";
    $STH = PDO_prepare($DBH, $query);
    //echo $STH;
    PDO_execute($STH, []);
    $threads_boards = PDO_fetch_all_assoc($STH);

    //deletes all tables that should not exist
    foreach ($threads_boards as $row) {
        if ($row["board_id"] == null) {
            add_thread_to_unused_list($DBH, (int) $row["thread_id"]);
            $query = "DELETE FROM $threadtable WHERE thread_id=:id";
            $STH = PDO_prepare($DBH, $query);
            //echo $STH;
            PDO_execute($STH, ["id" => $row["thread_id"]]);
        }
    }

}


/**
 * Removes posts that belong to a thread that no longer exists
 *
 * @param PDO_DB_CONNECTION $DBH The database connection to use
 * @return void
 */
function clean_undeleted_posts($DBH)
=======

}

function clean_undeleted_threads($DBH)
>>>>>>> origin/dev_client
{
    global $posttable;
    global $threadtable;
    global $boardtable;
<<<<<<< HEAD
    //gets table of thread_id, post_id where thread_id is null if no such thread exists.
    $query = "SELECT $posttable.post_id, $threadtable.thread_id FROM $posttable LEFT JOIN $threadtable ON $posttable.thread_id=$threadtable.thread_id";
=======

    //gets table of board_id, thread_id where board_id is null if no such board exists.
    $query = "SELECT $threadtable.thread_id, $boardtable.board_id FROM $threadtable LEFT JOIN $boardtable ON $threadtable.board_id=$boardtable.board_id";
>>>>>>> origin/dev_client
    $STH = PDO_prepare($DBH, $query);
    //echo $STH;
    PDO_execute($STH, []);
    $threads_boards = PDO_fetch_all_assoc($STH);

    //deletes all tables that should not exist
    foreach ($threads_boards as $row) {
<<<<<<< HEAD
        if ($row["thread_id"] == null) {
            print_r($row);
            add_post_to_unused_list($DBH, (int) $row["post_id"]);
            $query = "DELETE FROM $posttable WHERE post_id=:id";
            $STH = PDO_prepare($DBH, $query);
            //echo $STH;
            PDO_execute($STH, ["id" => $row["post_id"]]);

        }
    }
}

/**
 * Adds all unused user ids to the unused_id table`
 *
 * @param PDO_DB_CONNECTION $DBH The database connection to use.
 * @return void
 */
function update_unused_user_list($DBH)
{
    global $usertable, $unused_id_table;
    $query = "SELECT id from $usertable ORDER BY id ASC";
    $STH = PDO_prepare($DBH, $query);
    PDO_execute($STH, []);
    $output = PDO_fetch_all_assoc($STH);

    if(PDO_row_count($STH) == 0){
        return;
    }


    $already_listed_query = "SELECT user_id from $unused_id_table WHERE user_id IS NOT NULL";
    $already_listed_STH = PDO_prepare($DBH, $already_listed_query);
    PDO_execute($already_listed_STH, []);
    $already_listed_output = PDO_fetch_all_assoc($already_listed_STH);
    $already_listed_1D = array();
    $i = 0;
    foreach ($already_listed_output as $key => $value) {
        $already_listed_1D[$i] = $value["user_id"];
        $i++;
    }
    //adds items not in unused_ids to unused_ids when necessary.    
    $cur = 1;
    foreach ($output as $key => $value) {
        while ($value["id"] != $cur) {
            if (!in_array($cur, $already_listed_1D)) {
                add_user_to_unused_list($DBH, $cur);
            }
            $cur++;
=======
        if ($row["board_id"] == null) {
            add_thread_to_unused_list($DBH, (int)$row["thread_id"]);
            $query = "DELETE FROM $threadtable WHERE thread_id=:id";
            $STH = PDO_prepare($DBH, $query);
            //echo $STH;
            PDO_execute($STH, ["id" => $row["thread_id"]]);
>>>>>>> origin/dev_client
        }
        $cur++;
    }

}

<<<<<<< HEAD
/**
 * Adds all unused post ids to the unused_id table`
 *
 * @param PDO_DB_CONNECTION $DBH The database connection to use.
 * @return void
 */

function update_unused_post_list($DBH)
{
    global $posttable, $unused_id_table;

    //fetches all ids already used
    $query = "SELECT post_id from $posttable ORDER BY post_id ASC";
    $STH = PDO_prepare($DBH, $query);
    PDO_execute($STH, []);
    $output = PDO_fetch_all_assoc($STH);
    if(PDO_row_count($STH) == 0){
        return;
    }

    //fetches all ids already known to be free
    $already_listed_query = "SELECT post_id from $unused_id_table WHERE post_id IS NOT NULL";
    $already_listed_STH = PDO_prepare($DBH, $already_listed_query);
    PDO_execute($already_listed_STH, []);
    $already_listed_output = PDO_fetch_all_assoc($already_listed_STH);
    $already_listed_1D = array();
    $i = 0;
    foreach ($already_listed_output as $key => $value) {
        $already_listed_1D[$i] = $value["post_id"];
        $i++;
    }
    //adds items not in unused_ids to unused_ids when necessary.    
    $cur = 1;
    foreach ($output as $key => $value) {
        while ($value["post_id"] != $cur) {
            if (!in_array($cur, $already_listed_1D)) {
                add_post_to_unused_list($DBH, $cur);
            }
            $cur++;
        }
        $cur++;
    }

}

/**
 * Adds all unused board ids to the unused_id table`
 *
 * @param PDO_DB_CONNECTION $DBH The database connection to use.
 * @return void
 */

function update_unused_board_list($DBH)
{
    global $boardtable, $unused_id_table;
    $query = "SELECT board_id from $boardtable ORDER BY board_id ASC";
    $STH = PDO_prepare($DBH, $query);
    PDO_execute($STH, []);
    $output = PDO_fetch_all_assoc($STH);

    if(PDO_row_count($STH) == 0){
        return;
    }


    $already_listed_query = "SELECT board_id from $unused_id_table WHERE board_id IS NOT NULL";
    $already_listed_STH = PDO_prepare($DBH, $already_listed_query);
    PDO_execute($already_listed_STH, []);
    $already_listed_output = PDO_fetch_all_assoc($already_listed_STH);
    $already_listed_1D = array();
    $i = 0;
    foreach ($already_listed_output as $key => $value) {
        $already_listed_1D[$i] = $value["board_id"];
        $i++;
    }
    //adds items not in unused_ids to unused_ids when necessary.    
    $cur = 1;
    foreach ($output as $key => $value) {
        while ($value["board_id"] != $cur) {
            if (!in_array($cur, $already_listed_1D)) {
                add_board_to_unused_list($DBH, $cur);
            }
            $cur++;
        }
        $cur++;
    }

}

/**
 * Adds all unused thread ids to the unused_id table`
 *
 * @param PDO_DB_CONNECTION $DBH The database connection to use.
 * @return void
 */

function update_unused_thread_list($DBH)
{
    global $threadtable, $unused_id_table;
    $query = "SELECT thread_id from $threadtable ORDER BY thread_id ASC";
    $STH = PDO_prepare($DBH, $query);
    PDO_execute($STH, []);
    $output = PDO_fetch_all_assoc($STH);

    if(PDO_row_count($STH) == 0){
        return;
    }


    $already_listed_query = "SELECT thread_id from $unused_id_table WHERE thread_id IS NOT NULL";
    $already_listed_STH = PDO_prepare($DBH, $already_listed_query);
    PDO_execute($already_listed_STH, []);
    $already_listed_output = PDO_fetch_all_assoc($already_listed_STH);
    $already_listed_1D = array();
    $i = 0;
    foreach ($already_listed_output as $key => $value) {
        $already_listed_1D[$i] = $value["thread_id"];
        $i++;
    }
    //adds items not in unused_ids to unused_ids when necessary.    
    $cur = 1;
    foreach ($output as $key => $value) {
        while ($value["thread_id"] != $cur) {
            if (!in_array($cur, $already_listed_1D)) {
                add_thread_to_unused_list($DBH, $cur);
            }
            $cur++;
=======
function clean_undeleted_posts($DBH)
{
    global $posttable;
    global $threadtable;
    global $boardtable;
    //gets table of thread_id, post_id where thread_id is null if no such thread exists.
    $query = "SELECT $posttable.post_id, $threadtable.thread_id FROM $posttable LEFT JOIN $threadtable ON $posttable.thread_id=$threadtable.thread_id";
    $STH = PDO_prepare($DBH, $query);
    //echo $STH;
    PDO_execute($STH, []);
    $threads_boards = PDO_fetch_all_assoc($STH);

    //deletes all tables that should not exist
    foreach ($threads_boards as $row) {
        if ($row["thread_id"] == null) {
            print_r($row);
            add_post_to_unused_list($DBH, (int)$row["post_id"]);
            $query = "DELETE FROM $posttable WHERE post_id=:id";
            $STH = PDO_prepare($DBH, $query);
            //echo $STH;
            PDO_execute($STH, ["id" => $row["post_id"]]);

>>>>>>> origin/dev_client
        }
        $cur++;
    }
<<<<<<< HEAD

=======
>>>>>>> origin/dev_client
}
