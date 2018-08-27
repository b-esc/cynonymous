<?php
//include("../utilities/utilities.php");

/**
 * Creates a connection to our database.
 *
 * @return PDO_DB_CONNECTION The connection to the database.
 */
function PDO_connect_DB(){
    try {
        global $host, $database, $user, $pass;
        $DBH = new PDO("mysql:host=$host;dbname=$database; charset=utf8mb4", $user, $pass);
        $DBH->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    
    }
    catch(PDOException $e) {
        PDO_handle_errors($e);
        return -1;
    }

    return $DBH;
}
/**
 * Prepares the provided query (with pdo parameters)
 *
 * @param PDO_DB_CONNECTION $DBH The connection to the database.
 * @param string $query the query to prepare.
 * @return PDO_PREPARED_QUERY An object containing the prepared query.
 */
function PDO_prepare($DBH, $query){
    try {
        $STH = $DBH->prepare($query);
        return $STH;
    }
    catch(PDOException $e) {
        PDO_handle_errors($e);
        return -1;
    }

    return NULL;
}
/**
 * Executes a prepared query with the parameters specified in the associative array.
 *
 * @param PDO_PREPARED_QUERY $STH The prepared query to execute with the provided parameters.
 * @param array $params The parameters to execute the query with,
 * @return void
 */
function PDO_execute($STH, $params){
    try {
        $STH -> execute($params);
    }
    catch(PDOException $e) {
        PDO_handle_errors($e);
        return -1;
    }
}

/**
 * Executes a prepared query with the parameters already bound.
 *
 * @param PDO_PREPARED_QUERY $STH The prepared query to execute with the provided parameters.
 * @return void
 */
function PDO_execute_bound($STH){
    try {
        $STH -> execute();
    }
    catch(PDOException $e) {
        PDO_handle_errors($e);
        return -1;
    }
}

/**
 * Fetches all results from the specified executed query as an associative arra.y
 *
 * @param PDO_PREPARED_QUERY $STH The executed query to retrieve the rows from.
 * @return void
 */
function PDO_fetch_all_assoc($STH){
    try {
        $STH->setFetchMode(PDO::FETCH_ASSOC);
        $result = $STH->fetchAll();
        return $result;
    }
    catch(PDOException $e){
        PDO_handle_errors($e);
        return -1;
    }
    return NULL;
}

/**
 * Returns the next row of data returned by the specified executed query.
 *
 * @param PDO_PREPARED_QUERY $STH The query to return the results of.
 * @return void
 */
function PDO_fetch_next_assoc($STH){
    try {
        $STH->setFetchMode(PDO::FETCH_ASSOC);
        $result = $STH->fetch();
        return $result;
    }
    catch(PDOException $e){
        PDO_handle_errors($e);
        return -1;
    }
    return NULL;
}

/**
 * Returns the number of rows in the provided executed query.
 *
 * @param PDO_PREPARED_QUERY $STH The query to return the rows of.
 * @return void
 */
function PDO_row_count($STH){
    try {
        return $STH->rowcount();
    } catch(PDOException $e) {
        PDO_handle_errors($e);
        return -1;
    }
}

/**
 * Binds a value in a PDO query
 *
 * @param PDO_PREPARED_QUERY $STH The query to attach the value to.
 * @param string $identifier The identifier to bind the value to
 * @param mixed $value The value to bind
 * @param PDO_DATA_TYPE $type The type of the $value param
 * @return void
 */
function PDO_bind_value($STH, $identifier, $value, $type){
    $STH->bindValue($identifier, $value, $type);
}


/**
 * Handles the PDO errors.
 *
 * @param PDOException $e The error object caught in a try-catch.
 * @return void
 */
function PDO_handle_errors($e){
    echo $e->getMessage();
}

