<?php
// Fetched by cart.js
require_once('./../include/db_connection.php');
require_once('./../include/session_ctr_include.php');
require_once('./../include/tools.php');

if (!isset($_SESSION['email'])) exit();
else $email = $_SESSION['email'];

$mysql = db_connection();
$code = 0;
$Allid = array();
$OneId = 0;
if (!$mysql) {
    error_log('cart_loading.php - unable to access to the database: ' . mysqli_connect_error() .'\n',3, './../../error_log.txt');
    $code=-1;
    echo json_encode($code);
    exit();
} else{
    $query = "SELECT course_id FROM carts WHERE email=?";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $res = $stmt->get_result();
    // If the query results in more than 1 rows
    if ($res->num_rows>1){
        $code = 2;
        $allrow = $res->fetch_all(MYSQLI_ASSOC);
        foreach ($allrow as $row){
            // Fill with all the courses id the array
            $Allid[] = $row['course_id'];
        }
    }
    // If the query results in just row
    else if ($res->num_rows==1){
        $result = $res->fetch_assoc();
        $OneId = $result['course_id'];
        $code = 1;
    }
    else{
        $code = -1;
    }
        $stmt->close();
}

// If everything goes right go ahead, instead the code would be -1
if ($code > 0){
    // First query, add to the sales table new courses just shopped
    $query1 = "INSERT INTO sales (course_id, email) VALUES (?,?)";
    // Second query, delete from the carts table the courses just shopped
    $query2 = "DELETE FROM carts WHERE course_id=? AND email=? ";
    $stmt1 = $mysql->prepare($query1);
    $stmt2 = $mysql->prepare($query2);
    if ($code == 1){
        $stmt1->bind_param("is",$OneId,$email);
        $stmt2->bind_param("is",$OneId,$email);

        $stmt1->execute();
        if ($stmt1->affected_rows != 1) {
            $code = -1;
            $stmt2->close();
            $mysql->close();

            echo json_encode($code);
            exit();
        }else {
            $stmt2->execute();
        }
    }
    else{
        foreach ($Allid as $id){
            $stmt1->bind_param("is",$id,$email);
            $stmt2->bind_param("is",$id,$email);
    
            $stmt1->execute();
            if ($stmt1->affected_rows != 1) {
                $code = -1;
                $stmt2->close();
                $mysql->close();
    
                echo json_encode($code);
                exit();
            }else {
                $stmt2->execute();
            }
        }    
    }
    
    $stmt1->close();
    $stmt2->close();
    $mysql->close();
}

echo json_encode($code);
