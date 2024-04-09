<?php
// Fetched by show_profile.js
require_once('./../include/db_connection.php');
require_once('./../include/session_ctr_include.php');

if (isset($_SESSION['email'])) {
    $user = $_SESSION['email'];
}

$mysql = db_connection();
if (!$mysql) {
    error_log('delete_profile.php - unable to access to the database: ' . mysqli_connect_error() .'\n',3, './../../error_log.txt');
}
else {
    // DELETE query to delete the user from the users table
    $query = "DELETE FROM users WHERE email=?";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param('s', $user);
    $stmt->execute();

    if ($stmt->affected_rows == 1) {
        echo "Profile successfully deleted!";
    } else {
        error_log("delete_profile.php - The deletion of the profile went wrong: Query: $query, Error: {$stmt->errno} - {$stmt->error}\n", 3, './../../error_log.txt');
        echo "Something went wrong";
    }
    $stmt->close();
    $mysql->close();

    // Destroy the session
    session_destroy();
}
?>