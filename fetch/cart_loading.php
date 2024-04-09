<?php
// Fetched by cart.js
require_once('./../include/db_connection.php');
require_once('./../include/session_ctr_include.php');
require_once('./../include/tools.php');
if (!isset($_SESSION['email'])) exit();
else $email=$_SESSION['email'];
$info = array();
$rows = array();
$mysql = db_connection();
$info['error'] = false;
$info['rows'] = 0;
if (!$mysql) {
    error_log('cart_loading.php - unable to access to the database: ' . mysqli_connect_error() .'\n',3, './../../error_log.txt');
    $info['error'] = true;
}
else{
    // SELECT query for all the courses information that are in the carts table with the email of the user
    $query = "SELECT courses.id,courses.name,courses.image,courses.description,courses.price,courses.rating FROM carts JOIN courses ON carts.course_id=courses.id WHERE carts.email=?";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows==1){
        $rows=$res->fetch_assoc();
        $info['rows'] = 1;
    }
    else if ($res->num_rows < 1){
        $info['rows'] = -1;
    }
    else{
        $allrow = $res->fetch_all(MYSQLI_ASSOC);
        foreach ($allrow as $row){
            $rows[]=$row;
        }
        $info['rows'] = 2;
    }
}
// Print as a json element all the courses information
$info['datas']=$rows;
$json_info = json_encode($info);
echo $json_info;

