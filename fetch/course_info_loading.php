<?php
// Fetched by modify_course.js
include('./../include/db_connection.php');
include('./../include/session_ctr_include.php');
include('./../include/tools.php');
if (isset($_GET['id'])) {
    $id = test_int($_GET['id']); // tools.php
    $email = $_SESSION['email'];
    $response = array();
    $mysql = db_connection();
    $response['error'] = false;
    if ($id === false) {
        $response['error'] = true;
        $json_course = json_encode($response);
        echo $json_course;
        exit();
    }
    if (!$mysql) {
        error_log('course_info_loading.php - unable to access to the database: ' . mysqli_connect_error() .'\n',3, './../../error_log.txt');
        $response['error'] = true;
    }
    else {
        // SELECT query to select all the courses information with
        // an id (get by the fetch in GET)
        // and a creator from the SESSION (the email of the creator)
        $query = "SELECT courses.id, courses.name, courses.description, courses.price, courses.difficulty, courses.category, courses.video FROM courses WHERE id = ? AND creator = ?";
        $stmt = $mysql->prepare($query);
        $stmt->bind_param('is', $id, $email);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows != 1) {
            error_log('course_info_loading.php - SELECT query went wrong', 3, './../../error_log.txt');
            $response['error'] = true;
            $stmt->close();
            $mysql->close();
            exit();
        }
        // Saving the result of the query also in the SESSION id
        // because it is needed to the script PHP to modify the course information
        // (to know the changes)
        $response['course'] = $_SESSION['id'] = $res->fetch_assoc();
        $stmt->close();
        $mysql->close();
    }
    $json_course = json_encode($response);
    echo $json_course;
    exit();
}


