<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dim"; // Make sure this is the correct database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>