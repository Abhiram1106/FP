<?php
$host = "localhost:1106";
$user = "root";      
$pass = "";           
$dbname = "user_system";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
