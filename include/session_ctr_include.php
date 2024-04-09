<?php
/*
 * Check if the session is not set,
 * quite the same as the session_control.php
 * but we changed the path for redirecting to
 * include this file in the 'include' folder
 */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
    if (!isset($_SESSION['login'])) header('Location: ./../php/home.php');
}
?>