<?php
/*
 * Checking if the session is not set
 * or if the user is banned and so
 * redirect to the login page
 */
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['login'])) {
        header('Location: ./login.php');
    }
    if (isset($_SESSION['ban'])) {
        header('Location: ./login.php');
        session_destroy();
    }
?>