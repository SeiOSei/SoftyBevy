<?php
session_start();
include 'get_products.php'; // Include the file that contains database connection

// Fetch orders from the database
$select_orders = mysqli_query($conn, "SELECT * FROM checkout");

// Check if there are orders to display
if (mysqli_num_rows($select_orders) > 0) {
    $orders = mysqli_fetch_all($select_orders, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="dashboard-style.css">
<link rel="stylesheet" type="text/css" href="browse-admin-style.css">
<title>SoftyBevy Orders</title>
<style>
    .order-details-container {
    display: none; /* Initially hide order details */
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    border: 1px solid #ccc;
    padding: 10px;
    background-color: white;
    z-index: 1;
}

.order-details {
    text-align: center; /* Center the content */
}

.order-actions {
    text-align: center;
}

.btn {
    margin-bottom: 5px;
}

.product-table {
    width: 100%; /* Make the product table take full width */
    border-collapse: collapse;
}

.product-table th, .product-table td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: center; /* Center the content */
}
</style>
<script>
function toggleOrderDetails(orderId) {
    var orderDetails = document.getElementById('orderDetails_' + orderId);
    if (orderDetails.style.display === 'none') {
        orderDetails.style.display = 'block';
    } else {
        orderDetails.style.display = 'none';
    }
}
function confirmCancelOrder(orderId) {
    if (confirm('Do you want to cancel this order?')) {
        window.location.href = 'Orders.php?order_id=' + orderId;
    }
}
</script>
</head>
<body>
    <div class="headercontainer">
        <header class="Dashheader">
            <span class="headerspan"><a class="a1" href="Dashboard.html">SoftyBevy</a></span>
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a href="Dashboard-Loggedin.php" class="nav-link" aria-current="page">Home</a>
                </li>
                <li class="nav-item">
                    <a href="Browse.php" class="nav-link">Products</a>
                </li>
                <li>
                    <a href="Orders.php" class="nav-link active">Orders</a>
                </li>
                <li class="nav-item">
                    <a href="Signin.php" class="nav-link">Signout</a>
                </li>
            </ul>
        </header>
    </div>
    <div class="browse-container" style="display:flex; padding:80px; margin:auto;justify-content: center;flex-direction: column; align-items:center;">
        <h2>Your Orders</h2>
        <div class="product-display">
        <table class="product-display-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Total Price</th>
                    <th>Payment Method</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $orders_query = "SELECT * FROM checkout ORDER BY checkout_id DESC"; // Modify query as per your database structure
                $orders_result = mysqli_query($conn, $orders_query);
                if (isset($_GET['order_id']) && is_numeric($_GET['order_id'])) {
                    $order_id = $_GET['order_id'];
                
                    // Perform deletion from the database
                    $delete_query = "DELETE FROM checkout WHERE checkout_id = $order_id";
                    $delete_items_query = "DELETE FROM checkout_items WHERE checkout_id = $order_id";
                
                    // Execute the queries
                    if (mysqli_query($conn, $delete_query) && mysqli_query($conn, $delete_items_query)) {
                        echo "Order successfully canceled.";
                        header("Location: Orders.php?message=Order+successfully+canceled");
                        exit();
                    } else {
                        echo "Error canceling order: " . mysqli_error($conn);
                    }
                } else {
                    echo "Invalid order ID provided.";
                }

                if (mysqli_num_rows($orders_result) > 0) {
                    while ($row = mysqli_fetch_assoc($orders_result)) {
                        $order_id = $row['checkout_id'];
                        $customer_name = $row['customer_name'];
                        $total_price = $row['total_price'];
                        $payment_method = $row['payment_method'];

                        echo '<tr>';
                        echo '<td>'.$order_id.'</td>';
                        echo '<td>'.$customer_name.'</td>';
                        echo '<td>P'.number_format($total_price, 2).'</td>';
                        echo '<td>'.$payment_method.'</td>';
                        echo '<td><button class="btn" onclick="toggleOrderDetails('.$order_id.')">Show Order</button>';
                        echo '<br>';
                        echo '<button class="btn" onclick="confirmCancelOrder('.$order_id.')">Cancel Order</button>';
                        echo '</td>';
                        echo '</tr>';

                        // Display order details section as a separate table
                        echo '<tr class="order-details-container" id="orderDetails_' . $order_id . '" style="display: none;">';
                        echo '<td colspan="5">'; 
                        echo '<div class="order-details">';
                        echo '<strong>Order Details:</strong><br>';

                        // Fetch and display order items
                        $order_items_query = "SELECT * FROM checkout_items WHERE checkout_id = $order_id";
                        $order_items_result = mysqli_query($conn, $order_items_query);

                        if ($order_items_result && mysqli_num_rows($order_items_result) > 0) {
                            // Start product table
                            echo '<table class="product-table">';
                            echo '<thead>';
                            echo '<tr>';
                            echo '<th>Product Name</th>';
                            echo '<th>Quantity</th>';
                            echo '<th>Price</th>';
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';

                            // Loop through each order item
                            while ($item = mysqli_fetch_assoc($order_items_result)) {
                                echo '<tr>';
                                echo '<td>' . $item['product_name'] . '</td>';
                                echo '<td>' . $item['quantity'] . '</td>';
                                echo '<td>' . $item['price'] . '</td>';
                                echo '</tr>';
                            }

                            echo '</tbody>';
                            echo '</table>'; // End product table
                        } else {
                            echo 'No items found.';
                        }

                        echo '</div>'; // End of order details div
                        echo '</td></tr>';

                    }
                } else {
                    echo '<tr><td colspan="8">No orders found.</td></tr>';
                }
                ?>
            </tbody>
        </table>
        </div>
    </div>
    <script src="dashboard-script.js"></script>
</body>
</html>
