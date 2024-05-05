<?php
// Include database connection
include 'config.php';

// Fetch products from the database
$select = mysqli_query($conn, "SELECT productID, productName, Price, image FROM products");

if (mysqli_num_rows($select) > 0) {
    $products = mysqli_fetch_all($select, MYSQLI_ASSOC);
} else {
    $products = array(); // Empty array if no products found
}
?>
