<?php
// Connect to your database (replace with your actual database credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "softdrinks_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


?>
