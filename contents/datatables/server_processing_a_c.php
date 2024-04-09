<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
    if (!isset($_SESSION['login'])) header('Location: ./../../php/home.php');
    if (!isset($_SESSION['admin']) || $_SESSION['admin'] == 0) header('Location: ./../../php/home.php');
}

// DB table to use
$table = 'courses';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array('db' => 'id', 'dt' => 0),
    array('db' => 'id', 'dt' => 1),
    array('db' => 'name', 'dt' => 2),
    array('db' => 'category', 'dt' => 3),
    array('db' => 'difficulty', 'dt' => 4),
    array('db' => 'price', 'dt' => 5),
    array('db' => 'rating', 'dt' => 6),
);

// Database information from the log_file
$path = './../../../db_information.txt';

$hostname = $username = $password = $database = '';

$credentials = file($path, FILE_IGNORE_NEW_LINES);

if (count($credentials) >= 4) {
    $hostname = $credentials[0];
    $username = $credentials[1];
    $password = $credentials[2];
    $database = $credentials[3];

} else {
    $error = "Impossible to open the file: $path";
    error_log($error, 3, './../../../error_log.txt');
}

// SQL server connection information
$sql_details = array(
    'user' => $username,
    'pass' => $password,
    'db' => $database,
    'host' => $hostname
,'charset' => 'utf8' // Depending on your PHP and MySQL config, you may need this
);


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require('./ssp.class.php');

echo json_encode(
    SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns)
);
?>


