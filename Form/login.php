<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "softdrinks_db";

//Create Connection
$conn = new mysqli($servername, $username, $password, $dbname);
//Check Connection
if ($conn->connect_error) {
    die ("Connection Failure: " . $conn->connect_error);
}
//Get user input
$email = $_POST['email'];
$password = $_POST['password'];
//Check if the user exists in the database
$sql = "SELECT * FROM user WHERE email='$email' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User exists
    $user = $result->fetch_assoc();
    if ($user['is_admin'] == 1) {
        echo "Admin login successful.";
        // Redirect to admin dashboard
        header("Location: Dashboard-Loggedin.php");
    } else {
        //Customer login successful
        // Redirect to customer dashboard
        header("Location: Dashboard-Loggedin-customer.php");
        exit();
    }
} else {
    echo "Invalid email or password.";
}


$conn->close();
?>