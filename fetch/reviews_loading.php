<?php
// Fetched by course.js
require_once('./../include/tools.php');
require_once('./../include/db_connection.php');

if (!isset($_GET['id'])) $id = -1;
else $id = test_int($_GET['id']);

// Get a code from the fetch in GET
if (!isset($_GET['code'])) $code = 0;
else $code = test_int($_GET['code']);

if ($id==-1 || $id===false || ($code != 1 && $code!=0)) exit();

$mysql = db_connection();
if (!$mysql) {
    error_log('reviews_loading.php - unable to access to the database: ' . mysqli_connect_error() .'\n',3, './../../error_log.txt');
    $data = -1;
}
else{
    if ($code == 1) {
        // query to show only one review
        $query = "SELECT users.first_name,users.last_name,reviews.date, reviews.comment, reviews.rating FROM reviews JOIN users ON reviews.email = users.email WHERE reviews.course_id=? ORDER BY date DESC LIMIT 1";
    }
    else  {
        // query to show all the reviews
        $query = "SELECT users.first_name,users.last_name,reviews.date, reviews.comment, reviews.rating FROM reviews JOIN users ON reviews.email = users.email WHERE reviews.course_id=? ORDER BY date DESC";
    }
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $res = $stmt->get_result();
    $data = array();
    if ($code == 1) $data = $res->fetch_assoc();
    else{
        $allrow = $res->fetch_all(MYSQLI_ASSOC);
        foreach ($allrow as $row){
            $data[]=$row;
        }
    }
}
// Pass 1 or all the reviews as a response
$json_data = json_encode($data);
echo $json_data;



