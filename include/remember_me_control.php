<?php
/*
 * This script controls if remember me cookie is set
 */
    require_once ('./../include/db_connection.php');
    if(!isset($_COOKIE['rememberme'])){
        return NULL;
    }
    else {
        $mysql = db_connection();
        if (!$mysql) {
            error_log('Remember_me_control.php - unable to access to the database: ' . mysqli_connect_error() .'\n',3, './../../error_log.txt');
            return NULL;
        } else {
            // SELECT query to select all the user information from the DB
            // included cookies information
            $query = 'SELECT * from users where cookie_id=?';
            $stmt = $mysql->prepare($query);
            // Using the COOKIE PHP variables
            $stmt->bind_param('s',$_COOKIE['rememberme']);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows != 1) {
                error_log("Remember_me_control.php - the selection went wrong on query: $query, affected rows: $stmt->errno\n", 3, './../../error_log.txt');
                return NULL;
            }
            $row = $res->fetch_assoc();
            $stmt->close();
            $mysql->close();

            // Checking if the cookie is expired, then change the cookie
            // expiration date one hour before
            if (time() > $row['cookie_exp_date']){
                setcookie('rememberme', '', time()-3600, '/');
                return NULL;
            }

            // Checking if the user is banned
            if ($row['ban']) {
                return NULL;
            }

            // If all checks have been passed, setting the SESSION variables
            // (and do the user log in)
            session_start();
            $_SESSION['login'] = true;
            $_SESSION['f_name'] = $row['first_name'];
            $_SESSION['l_name'] = $row['last_name'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['birth'] = $row['birth'];
            $_SESSION['mobile'] = $row['mobile'];
            $_SESSION['bio'] = $row['bio'];
            $_SESSION['teacher'] = $row['teacher'];
            $_SESSION['admin'] = $row['admin'];
            $_SESSION['newsletter'] = $row['newsletter'];
        }
    }
?>