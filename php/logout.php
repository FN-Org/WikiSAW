<?php
require_once('./../include/db_connection.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$mysql = db_connection();
if (!$mysql) {
    error_log('Logout.php - unable to access to the database: ' . mysqli_connect_error() .'\n',3, './../../error_log.txt');
}
else {
    // If is set the remember me cookie, delete it from the database
    // and set the expiration date one hour before
    if (isset($_COOKIE['rememberme'])) {
        $default_s = '0';
        $default_n = 0;
        $query = 'UPDATE users SET cookie_id=?, cookie_exp_date=? WHERE email=?;';
        $stmt = $mysql->prepare($query);
        $stmt->bind_param('sis', $default_s, $default_n, $_SESSION['email']);
        $stmt->execute();
        if ($stmt->affected_rows != 1){
            error_log("Logout.php - the update went wrong on query: $query, affected rows: $stmt->errno\n", 3, './../../error_log.txt');
        }
        $stmt->close();
        $mysql->close();
        setcookie('rememberme', '', time()-3600, '/');
    }

    // Destroy session
    session_destroy();
    header('Location: ./../index.php');
}
?>