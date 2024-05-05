<?php
session_start();
include 'config.php'; // Include the file that contains database connection

if(isset($_POST['productId'])) {
    $productId = $_POST['productId'];

    // Fetch product details from the database
    $select = mysqli_prepare($conn, "SELECT productName, Price FROM products WHERE productID = ?");
    mysqli_stmt_bind_param($select, "i", $productId);
    mysqli_stmt_execute($select);
    mysqli_stmt_bind_result($select, $productName, $price);
    mysqli_stmt_fetch($select);

    if ($productName && $price) {
        // Initialize cart if it's not already set
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        // Check if the product is already in the cart
        $existingItem = array_filter($_SESSION['cart'], function ($item) use ($productId) {
            return $item['product_id'] == $productId;
        });

        if (!empty($existingItem)) {
            // Update quantity and total price if the product is already in the cart
            $_SESSION['cart'][$productId]['quantity']++;
            $_SESSION['cart'][$productId]['total_price'] += $price;
        } else {
            // Add product to the cart with quantity 1 if it's not in the cart
            $product = array(
                'product_id' => $productId,
                'product_name' => $productName,
                'price' => $price,
                'quantity' => 1,
                'total_price' => $price
            );
            $_SESSION['cart'][$productId] = $product;
        }

        // Update the database to reflect the added quantity
        $updateQuery = mysqli_prepare($conn, "UPDATE products SET Quantity = Quantity + 1 WHERE productID = ?");
        mysqli_stmt_bind_param($updateQuery, "i", $productId);
        mysqli_stmt_execute($updateQuery);
        mysqli_stmt_close($updateQuery);

        // Redirect back to the previous page or a specific cart page
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        echo "Product not found!";
    }

    mysqli_stmt_close($select);
} else {
    echo "Invalid request!";
}
?>
