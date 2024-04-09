<?php
// Function to establish the connection with the database
function db_connection() {
    // Using the credentials in the db_information file
    // outside the public directory
    $path = './../../db_information.txt';
    $hostname = $username = $password = $database = '';
    $credentials = file($path, FILE_IGNORE_NEW_LINES);

    if (count($credentials) >= 4) {
        $hostname = $credentials[0];
        $username = $credentials[1];
        $password = $credentials[2];
        $database = $credentials[3];
    } else {
        $error = "Impossible to open the file: $path";
        error_log($error, 3, './../../error_log.txt');
    }

    // Establish a new connection with the DB
    $mysqli = new mysqli("$hostname", "$username", "$password", "$database");

    // Check connection
    if ($mysqli->connect_errno) {
        error_log('db_connection.php - connection failed: ' . $mysqli->connect_error, 3, './../../error_log.txt');
        return false;
    }

    // Character encoding used on the database
    $mysqli->set_charset('utf8mb4');

    return $mysqli;
}
?>