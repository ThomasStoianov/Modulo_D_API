<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "sp_skills";

$conn = mysqli_connect($host, $user, $password, $database);

if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

?>