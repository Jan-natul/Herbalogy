<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "website";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    error_log("DB connect error: " . $conn->connect_error);
    die("Connection failed");
}
$conn->set_charset('utf8mb4'); 
?>
