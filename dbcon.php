<?php

$host = "localhost";
$name = "root";
$password = "";
$dbname = "pigeon";

$con = mysqli_connect($host,$name,$password,$dbname);

if(!$con)
{
     echo "database connection error";
}

?>