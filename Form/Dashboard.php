<?php
session_start();
include 'get_products.php'; // Include the file that contains database connection

// Fetch products from the database
$select = mysqli_query($conn, "SELECT * FROM products");

// Check if there are products to display
if (mysqli_num_rows($select) > 0) {
    $products = mysqli_fetch_all($select, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="dashboard-style.css">
<link rel="stylesheet" type="text/css" href="browse-admin-style.css">
<title>SoftyBevy Dashboard</title>
</head>
<body>
    <div class="headercontainer">
        <header class="Dashheader">
            <span class="headerspan"><a class="a1" href="Dashboard.html">SoftyBevy</a></span>
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a href="Dashboard.php" class="nav-link active" aria-current="page">Home</a>
                </li>
                <li class="nav-item">
                    <a href="Signin.php" class="nav-link">Log-in/Sign-up</a>
                </li>
            </ul>
        </header>
    </div>
    <div class="product-container" style="padding:80px">
        <h2 style="color:aquamarine; font-size: 20px;">Featured Products</h2>
        <div class="product-grid" style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: flex-start;">
            <?php if(isset($products) && !empty($products)) {
                foreach ($products as $product) {
                    echo '<div class="product-item" style="width: 200px; margin: 10px; padding: 10px; border: 1px solid black; text-align: center; background-color: #6495ed;">';
                    echo '<img src="Uploaded_Imgs/'.$product['image'].'" alt="'.$product['productName'].'" style="max-width: 100%; height: auto;">';
                    echo '<div class="product-info" style="padding: 10px;">';
                    echo '<h3 style="margin-bottom: 5px;color: #fff">'.$product['productName'].'</h3>';
                    echo '<p style="margin-top: 0; margin-bottom: 5px;">Price: P'.$product['Price'].'</p>';
                    echo '<a href="Signin.php?id='.$product['productID'].'" class="btn" style="margin-top: 5px; margin-bottom: 10px; padding: 5px; background-color: skyblue; color: white; border: none; border-radius: 5px; cursor: pointer;">Add to Cart</a>';
                    echo '</div>'; // Close product-info div
                    echo '</div>'; // Close product-item div
                }
            } else {
                echo '<p>No products available.</p>';
                }
            ?>
        </div>
    </div>
<script src="dashboard-script.js"></script>
</body>
</html>