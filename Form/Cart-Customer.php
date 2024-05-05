<?php

@include 'get_products.php';

if(isset($_POST['add_product'])){
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $image = $_POST['image'];
    $quantity = 1; // Assuming initially adding one quantity

    // Check if the product is already in the cart
    $check_query = "SELECT * FROM cart WHERE product_id = $product_id";
    $check_result = mysqli_query($conn, $check_query);

    if(mysqli_num_rows($check_result) > 0){
        $message[] = 'Product already exists in the cart';
    } else {
        $insert = "INSERT INTO cart (product_id, product_name, price, quantity, image) 
                   VALUES ($product_id, '$product_name', $price, $quantity, '$image')";
        $upload = mysqli_query($conn, $insert);

        if($upload){
            $message[] = 'Product added to cart successfully';
        } else {
            $message[] = 'Could not add the product to cart';
        }
    }
}


if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM cart WHERE product_id = $id");
    //header('location:Browse.php');
 };
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="dashboard-style.css">
<link rel="stylesheet" type="text/css" href="browse-admin-style.css">
<title>SoftyBevy MyCart</title>
<script>
function updatePrice(input) {
    const quantity = input.value;
    const basePrice = input.closest('tr').querySelector('.price').getAttribute('data-base-price');
    const totalPrice = quantity * basePrice;
    input.closest('tr').querySelector('.price').textContent = 'P' + totalPrice;
}
</script>
</head>
<body>
    <div class="headercontainer">
        <header class="Dashheader">
            <span class="headerspan"><a class="a1" href="Dashboard.html">SoftyBevy</a></span>
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a href="Dashboard-Loggedin-customer.php" class="nav-link" aria-current="page">Home</a>
                </li>
                <li class="nav-item">
                    <a href="Cart-Customer.php" class="nav-link active">Cart</a>
                </li>
                <li class="nav-item">
                    <a href="Signin.php" class="nav-link">Signout</a>
                </li>
            </ul>
        </header>
    </div>
    <div class="browse-container" style="display:flex; padding:80px; margin:auto;justify-content: center;flex-direction: column; align-items:center;">
    <h2>Your Cart</h2>
    <button class="add-product-button" style="display:block; margin: auto;">Order Now!</button>
    <div class="product-display">
    <table class="product-display-table">
    <thead>
        <tr>
            <th>Product Image</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Fetch cart items from the database
        $cart_items_query = "SELECT * FROM cart";
        $cart_items_result = mysqli_query($conn, $cart_items_query);

        if(mysqli_num_rows($cart_items_result) > 0){
            while($row = mysqli_fetch_assoc($cart_items_result)){
                echo '<tr>';
                echo '<td><img src="Uploaded_Imgs/'.$row['image'].'" height="100" alt=""></td>';
                echo '<td>'.$row['product_name'].'</td>';
                echo '<td>
                    <form method="post" action="">
                        <input type="number" min="1" name="quantity" value="'.$row['quantity'].'" onchange="updatePrice(this)" style="background-color:black; color:white;width:35px; text-align: center; font-size: 20px">
                        <input type="hidden" name="product_id" value="'.$row['product_id'].'">
                        <input type="hidden" name="action" value="update_quantity">
                    </form>
                    </td>';
                echo '<td class="price" data-base-price="'.$row['price'].'">P'.$row['price'] * $row['quantity'].'</td>';
                echo '<td><a href="Cart-Customer.php?delete='.$row['product_id'].'" class="btn">Delete</a></td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="5">Your cart is empty.</td></tr>';
        }
        ?>
    </tbody>
</table>
    </div>
</div>
</body>
</html>