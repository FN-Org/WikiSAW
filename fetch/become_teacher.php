<?php
    // Fetched by teacher_rules.js
    require_once('./../include/db_connection.php');
    require_once('./../include/session_ctr_include.php');

    $mysql = db_connection();
    if (!$mysql) {
        error_log('become_teacher.php - DB connection went wrong: ' . mysqli_connect_error() . '\n', 3, './../../error_log');
        echo -1;
        exit();
    }
    $teacherValue = 1;
    $query = 'UPDATE users SET teacher = ? WHERE email = ?';
    $stmt = $mysql->prepare($query);
    $stmt->bind_param('is', $teacherValue, $_SESSION['email']);
    $stmt->execute();
    if ($stmt->affected_rows != 1) {
        error_log('become_teacher.php - the UPDATE went wrong'."\n",3, './../../error_log.txt');
        echo -1;
        exit();
    }
    $stmt->close();
    $mysql->close();
    // Update the SESSION variable related to teacher
    $_SESSION['teacher'] = 1;
    echo 1;
    exit();
?>
