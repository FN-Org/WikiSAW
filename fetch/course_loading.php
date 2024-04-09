<?php
require_once('./../include/tools.php');
require_once('./../include/db_connection.php');
// Fetched by course.js
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_GET['id'])) $id = -1;
else $id = test_int($_GET['id']);

$response = array();
$response['error'] = false;
$response = ObtainCourseInformations($id);

if (!isset($_SESSION['email'])) {
    $owned = 0;
    $login = false;
}
else {
    $email = $_SESSION['email'];
    $login = $_SESSION['login'];
    if (isset($response['course'])) {
        $owned = owningControl($id, $email, $response['course']['creator']); // tools.php
    }
    else {
        $response['error'] = true;
    }
}
$response['login'] = $login;

if ($response['error']) {
    $json_data = json_encode($response);
    echo $json_data;
}
else if ($owned === 1) {
    // We don't want to pass into the json element the
    // creator email
    $response['course']['creator'] == null;
    $json_data = json_encode($response);
    echo $json_data;
}
else if ($owned === 0) {
    // If you don't have bought the course,
    // you obviously can't see the video
    $response['course']['video'] = -1;
    // We don't want to pass into the json element the
    // creator email
    $response['course']['creator'] == null;
    $json_data = json_encode($response);
    echo $json_data;
}

/*
 * Function to obtain all the course information
 */
function ObtainCourseInformations($id) {
    $info = array();
    $info['error'] = false;
    if ($id === -1) {
        $info['error'] = true;
        return $info;
    }
    $mysql = db_connection();
    if (!$mysql) {
        error_log('course_loading.php - unable to access to the database: ' . mysqli_connect_error() .'\n',3, './../../error_log.txt');
        $info['error'] = true;
    }
    else{
        // SELECT query to select everything from the courses table and also
        // name and surname of the creator, because users need to know
        // who creates the course
        $query = "SELECT courses.*,users.first_name,users.last_name FROM courses JOIN users ON courses.creator = users.email WHERE courses.id=?";
        $stmt = $mysql->prepare($query);
        $stmt->bind_param("i",$id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows == 1){
            $info['course'] = $res->fetch_assoc();
        }
        else if ($res->num_rows < 1){
            error_log("course_loading.php - the selection, during Obtaining course informations, went wrong on query: $query\n", 3, './../../error_log.txt');
            $info['error'] = true;
        }
        $stmt->close();
        $mysql->close();
    }
    return $info;
}