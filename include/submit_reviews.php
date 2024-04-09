<?php
require_once ('./../include/db_connection.php');
require_once ('./../include/session_ctr_include.php');
require_once ('./../include/tools.php');
// Checking if the form from course.php had some missed inputs
if ((!isset($_GET['rating']))||!isset($_GET['comment'])||!isset($_GET['id'])||!isset($_SESSION['email'])) {
    header('Location: ./../php/catalog.php');
    exit();
}
// Creating variables
$id = test_int($_GET['id']);
$email = $_SESSION['email'];
$rating = $_GET['rating']* 2;
$rating = isRating(test_int($rating)); // tools.php
$comment = test_input($_GET['comment']); // tools.php
if ($id ==-1 || $id === false || $rating === false || empty($comment)) {
    header('Location: ./../php/course.php?id='.$id);
    exit();
}

$owned = owningControl($id,$email,''); // tools.php

// If the user has bought the course
if ($owned==1) {
    // Format the date to pass to the DB
    $date = date("Ymd"); 
    $mysql = db_connection();
    if (!$mysql) {
        error_log('submit_reviews.php - unable to access to the database: ' . mysqli_connect_error() .'\n',3, './../../error_log.txt');
        exit();
    }
    else{
        // INSERT query
        $query = "INSERT INTO reviews (course_id, email, rating, comment,date) VALUES (?, ?, ?, ?,$date)";
        // Try to execute the query
        try {
            $stmt = $mysql->prepare($query);
            $stmt->bind_param("isis", $id, $_SESSION['email'], $rating, $comment);
            $stmt->execute();
        }
        // Catching all the exceptions
        catch (mysqli_sql_exception $e){
            $stmt->close();
            $mysql->close();
            header('Location: ./../php/course.php?id='.$id.'&message=1');
        }
        
        if ($stmt->affected_rows != 1) {
            error_log("submit_reviews.php - the selection, during insert, query: $query\n", 3, './../../error_log.txt');
        }
        // Unfortunately we aren't allowed to use triggers in the DB, so we opted
        // to do this other query to UPDATE the rating of the course
        // after a review, so after an INSERT on the reviews table
        $query = "UPDATE courses 
        SET rating = (SELECT AVG(rating)
        FROM reviews
        WHERE course_id = ?)
        WHERE id = ?";
        $stmt = $mysql->prepare($query);
        $stmt->bind_param("ii", $id,$id);
        $stmt->execute();
        if ($stmt->affected_rows != 1){
            error_log("submit_reviews.php - the selection, during insert, query: $query\n", 3, './../../error_log.txt');
        }
        $stmt->close();
        $mysql->close();
    }
}
header('Location: ./../php/course.php?id='.$id);
