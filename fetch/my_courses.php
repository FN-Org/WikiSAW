<?php
// Fetched by show_profile.js
require_once('./../include/db_connection.php');
require_once('./../include/session_ctr_include.php');

$email = $_SESSION['email'];
$response = array();

$mysql = db_connection();
if (!$mysql) {
    error_log('my_courses.php - Db connection failed',3, './../../error_log.txt');
    $response['error'] = true;
} try {
    // Try to do the SELECT query to select all the courses
    // owned by the user
    $query = 'SELECT courses.id, courses.image, courses.name FROM courses JOIN sales ON courses.id = sales.course_id WHERE sales.email = ?';
    $stmt = $mysql->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $res = $stmt->get_result();
    $response['courses'] = $res->fetch_all();
    $stmt->close();
    $mysql->close();
// Catching all the exceptions
} catch (mysqli_sql_exception $e) {
    $response['error'] = true;
}
$json_response = json_encode($response);
echo $json_response;
?>