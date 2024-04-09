<?php
// Function for validating input
function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        return $data;
}

// Test_email function, it returns false if the email is not valid and the email if it is valid
function test_email($email){
    test_input($email);
    $email = filter_var($email,FILTER_SANITIZE_EMAIL);
    $email = filter_var($email,FILTER_VALIDATE_EMAIL);
    return $email;
}

// Test_int function, it returns the number as an int if it is a number and false if it is not valid
function test_int($int){
    test_input($int);
    $int = filter_var($int,FILTER_SANITIZE_NUMBER_INT);
    $int = filter_var($int,FILTER_VALIDATE_INT);
    return $int;
}

// isRealDate function, it returns the date if it matches with the regex pattern, false instead
function isRealDate($date) {
    $pattern = '/^\d{4}-\d{2}-\d{2}$/';

    if (preg_match($pattern, $date)) {
        return $date;
    }
    else {
        return false;
    }
}

// isMobile function, it returns the mobile number if it matches with the regex pattern, false instead
function isMobile($phone) {
    $pattern = '/^\d{3}-\d{7}$/';

    if (preg_match($pattern, $phone)) {
        return $phone;
    } else {
        return false;
    }
}

// isCategory function, it returns the category if it matches with one of the category
// in the if statement, false instead
function isCategory($category) {
    if ($category == 'Life' ||
        $category == 'Coding' ||
        $category == 'Science' ||
        $category == 'Automation' ||
        $category == 'Languages' ||
        $category == 'Photography' ||
        $category == 'Music' ||
        $category == 'Business') {
        return $category;
    } else {
      return false;
    }
}

// isPrice function, it returns the price if it matches with the regex pattern, false instead
function isPrice($price) {
    $pattern = '/^(0|0\.00|\d+(\.\d{2})?)$/';

    if (preg_match($pattern, $price)) {
        return $price;
    } else {
        return false;
    }
}

// isDifficulty function, it returns the difficulty if it is a number from 1 to 10, false instead
function isDifficulty($difficulty) {
    if ($difficulty >= 1 && $difficulty <=10) {
        return $difficulty;
    } else {
        return false;
    }
}

// isRating function, it returns a rating if it is a number from 1 to 10, false instead
function isRating($rating) {
    if ($rating<1||$rating>10) return false;
    else return $rating;
}

// owningControl function, it returns 1 if the user with the email own the course with the id (or it is the creator)
// else return 0 if he doesn't,
// it returns -1 if there is an error
function owningControl($id, $email, $creator) {
    require_once('./../include/db_connection.php');
    if ($id == -1) return 0;
    // Checking if the user who wants to do something with the course with this id
    // is the teacher of the course itself
    if ($email == $creator) return 1;
    $mysql = db_connection();
    if (!$mysql) {
        error_log('course_loading.php - unable to access to the database: ' . mysqli_connect_error() .'\n',3, './../../error_log.txt');
        return -1;
    }
    else{
        $query = "SELECT * FROM sales WHERE email='$email' AND course_id=?"  ;
        $stmt = $mysql->prepare($query);
        $stmt->bind_param("i",$id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows == 1){
            $owned = 1;
        }
        else if ($res->num_rows < 1){
            $owned = 0;
        }
        else {
            error_log("course_loading.php - the selection, during owning control, went wrong on query: $query\n", 3, './../../error_log.txt');
            $owned = -1;
        }
        $stmt->close();
        $mysql->close();
        return $owned;
    }
}

?>