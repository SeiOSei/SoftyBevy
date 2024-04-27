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
    //Check if the email exists in the database
    $checkQuery = "SELECT * FROM user WHERE email = '$email'";
    $checkResult = mysqli_query($connection, $checkQuery);
    if (mysqli_num_rows($checkResult) > 0) {
        //email exists
        echo json_encode(array("status" => "error", "message"=> "Email already exists. Please use a different email."));
    } else {
        // Create a query to insert the user into the database
        $insertQuery = "INSERT INTO user (firstname, lastname, email, password) VALUES ('$firstname', '$lastname', '$email', '$password')";
        // Execute the query
        if (mysqli_query($connection, $insertQuery)) {
            echo json_encode(array("status" => "success", "message" => "Registration successful!"));
            header("Location: Signin.php");
        } else {
            echo json_encode(array("status" => "error", "message" =>"Error: " . $query . "<br>" . mysqli_error($connection)));
        }
    }
}
// Close the database connection
mysqli_close($connection);
?>