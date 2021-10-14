<?php
session_start();

unset($_SESSION['auth']);
unset($_SESSION['login']);
unset($_SESSION['status']);
header('location: login.php');
die();
?>