<?php
// Fetched by cart.js
require_once('./../include/db_connection.php');
require_once('./../include/session_ctr_include.php');
require_once('./../include/tools.php');

if (!isset($_SESSION['email'])|| !isset($_GET['id'])) exit();
else {
    $email = $_SESSION['email'];
    $id = $_GET['id'];
}

$mysql = db_connection();
$code = 0;
if (!$mysql) {
    error_log('remove_from_cart.php - unable to access to the database: ' . mysqli_connect_error() .'\n',3, './../../error_log.txt');
    $code = -1;
    echo json_encode($code);
    exit();
}
// DELETE query to remove the course with the appropriate ID
// and current email from the carts table
$query = "DELETE FROM carts WHERE email=? AND course_id=?";
$stmt = $mysql->prepare($query);
$stmt->bind_param('si',$email,$id);
$stmt->execute();
if ($stmt->affected_rows != 1){
    error_log("remove_from_cart.php - the delete went wrong, query: $query\n", 3, './../../error_log.txt');
    $code = -1;
}
$stmt->close();
$mysql->close();
echo json_encode($code);

