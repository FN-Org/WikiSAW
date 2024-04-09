<?php
// Fetched by course.js
require_once('./../include/tools.php');
require_once('./../include/db_connection.php');
require_once('./../include/session_ctr_include.php');

if (!isset($_GET['id'])) $id = -1;
else $id = test_int($_GET['id']);

if (!isset($_SESSION['email'])) $email = -1;
else $email = $_SESSION['email'];

header("Content-Type: application/json");

$exist = courseExist($id);
$result = -1;

if ($exist == 0){
    $result = addToCart($id,$email);
    if ($result == 0){
        $message = "Successifully added to cart";
    }
    else if ($result==-2){
        $message = "You already added this course to your cart, go to the cart to complete the purchase";
    }
    else{
        $message = "Something went wrong";
    }
}
else {
    $message = "The course with id = $id doesn't exists";
}

echo json_encode($message);

/*
 * Function that returns 0 if the course exists, -1 for errors
 */
function courseExist($id){
    if ($id === -1) return -1;
    $mysql = db_connection();
    if (!$mysql) {
        error_log('add_to_cart.php - unable to access to the database: ' . mysqli_connect_error() .'\n',3, './../../error_log.txt');
        return -1;
    }
    $query = "SELECT COUNT(*) AS count FROM courses WHERE id=?";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows == 1){
        $code = 0;
    }
    else{
        $code=-1;
    }
    $stmt->close();
    $mysql->close();
    return $code;
}

/*
 * Function to add a course in the cart, so insert a new line in the carts table
 */
function addToCart($id,$email): int
{
    if ($email == -1 || $id == -1) return -1;
    else {
        $mysql = db_connection();
        if (!$mysql) {
            error_log('add_to_cart.php - unable to access to the database: ' . mysqli_connect_error() .'\n',3, './../../error_log.txt');
            return -1;
        }
        $query = "INSERT INTO carts (course_id, email) VALUES (?, '$email');";
        try{
            $stmt = $mysql->prepare($query);
            $stmt->bind_param("i",$id);
            $stmt->execute();
            if ($stmt->affected_rows != 1){
                error_log("add_to_cart.php - the insert, during addtocart, went wrong on query: $query\n", 3, './../../error_log.txt');
                $code = -1;
            }
            else{
                $code = 0;
            }
        }
        catch(mysqli_sql_exception $e){
            // Exception for duplicated key, you can't add twice
            // the same course into the cart
            if ($e->getCode() == 1062) {
                $code = -2;
            } else {
               $code = -3;
            }
        }
        return $code;
    }
}