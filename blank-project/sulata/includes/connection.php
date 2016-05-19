<?php

/*
 * SULATA FRAMEWORK
 * This file contains the database connection.
 */
try {
    $cn = new MongoClient(DB_CONNECTION_STRING);
} catch (MongoConnectionException $e) {
    if (DEBUG == TRUE) {
        exit($e);
    } else {
        suRedirect302();
    }
}
$db = $cn->selectDB(DB_NAME);