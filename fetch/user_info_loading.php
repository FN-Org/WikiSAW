<?php
// Fetched by update_profile.js
// and could be fetched also by the show_profile.js,
// but it's not caused automatic tests
require_once('./../include/session_ctr_include.php');
require_once('./../include/db_connection.php');
$response = array();
$mysql = db_connection();
$response['error'] = false;
if (!$mysql) {
    error_log('user_info_loading.php - unable to access to the database: ' . mysqli_connect_error() .'\n',3, 'error_log.txt');
    $response['error'] = true;
}
else {
    $email = $_SESSION['email'];
    // SELECT query to select all the user information with a particular email
    $query = "SELECT first_name, last_name, email, birth, mobile, bio, newsletter FROM users WHERE email= ?";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows != 1){
        error_log('user_info_loading.php - SELECT query went wrong', 3, './../../error_log.txt');
        $response['error'] = true;
    } else {
        $response['row'] = $res->fetch_assoc();
    }
    $stmt->close();
    $mysql->close();
}
$json_data = json_encode($response);
echo $json_data;
?>