<?php
session_start();

if(!isset($_SESSION['login']) && $_SESSION['login'] != true)
{
    $_SESSION['status'] = "Login First";
    header('location: login.php');
    exit(0);
}

?>
