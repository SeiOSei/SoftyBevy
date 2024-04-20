<?php
$host = 'localhost';
$db = 'softdrinks_db';
$user = 'root';
$password = '';

//Connection to database
$connection = mysqli_connect($host, $user, $password, $db);
//connection checking if success
if (!$connection){
    die ("Connection Failure: " . mysqli_connect_error());
}
// Process the registration form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    // Create a query to insert the user into the database
    $query = "INSERT INTO user (firstname, lastname, email, password) VALUES ('$firstname', '$lastname', '$email', '$password')";
    // Execute the query
    if (mysqli_query($connection, $query)) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($connection);
    }
}
// Close the database connection
mysqli_close($connection);
?>