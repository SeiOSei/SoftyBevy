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
        // Product already exists, increase the quantity by 1
        $row = mysqli_fetch_assoc($check_result);
        $new_quantity = $row['quantity'] + 1;
        $update_query = "UPDATE cart SET quantity = $new_quantity WHERE product_id = $product_id";
        if (mysqli_query($conn, $update_query)) {
            $message[] = 'Product quantity increased by 1';
        } else {
            $message[] = 'Could not update the product quantity';
        }
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

if(isset($_POST['update_quantity'])){
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    $update_query = "UPDATE cart SET quantity = $quantity WHERE product_id = $product_id";
    mysqli_query($conn, $update_query);
}

if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM cart WHERE product_id = $id");
 }

$total_price_query = "SELECT SUM(price * quantity) AS total_price FROM cart";
$total_price_result = mysqli_query($conn, $total_price_query);
$total_price_row = mysqli_fetch_assoc($total_price_result);
$total_price = $total_price_row['total_price'];

$order_details = [];
$order_placed = false;

if (isset($_POST['checkout'])) {
    $customer_name = $_POST['customer_name'];
    $payment_method = $_POST['payment_method'];

    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Insert checkout details
    $insert_checkout = "INSERT INTO checkout (customer_name, payment_method, total_price) 
                        VALUES ('$customer_name','$payment_method', '$total_price')";

        
        if (mysqli_query($conn, $insert_checkout)) {
            $checkout_id = mysqli_insert_id($conn); // Get the ID of the newly inserted checkout

            // Fetch cart items
            $cart_items_query = "SELECT * FROM cart";
            $cart_items_result = mysqli_query($conn, $cart_items_query);

            while ($row = mysqli_fetch_assoc($cart_items_result)) {
                $product_id = $row['product_id'];
                $product_name = $row['product_name'];
                $price = $row['price'];
                $quantity = $row['quantity'];

                // Insert cart items into checkout_items table
                $insert_item = "INSERT INTO checkout_items (checkout_id, product_id, product_name, price, quantity) 
                                VALUES ('$checkout_id', '$product_id', '$product_name', '$price', '$quantity')";
                
                if (!mysqli_query($conn, $insert_item)) {
                    throw new Exception('Error inserting checkout item: ' . mysqli_error($conn));
                }

                // Collect order details for the popup
                $order_details[] = [
                    'product_name' => $product_name,
                    'price' => $price,
                    'quantity' => $quantity
                ];
            }

            // Clear the cart
            mysqli_query($conn, "DELETE FROM cart");

            // Commit transaction
            mysqli_commit($conn);
            $order_placed = true;
        } else {
            throw new Exception('Error inserting checkout: ' . mysqli_error($conn));
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo $e->getMessage();
    }
}
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
function showOrderSuccessAlert() {
    alert("Order is successful, please check your order information on Orders page. Thank you!");
}

function showOrderDetails(orderId, orderDetails, totalPrice) {
    let detailsHtml = '<h3>Order Number: ' + orderId + '</h3>';
    detailsHtml += '<hr>'
    detailsHtml += '<h4>Order Details</h4>';
    detailsHtml += '<table>';
    detailsHtml += '<tr><th>Product Name</th><th>Quantity</th><th>Price</th></tr>';

    orderDetails.forEach(item => {
        detailsHtml += '<tr>';
        detailsHtml += '<td>' + item.product_name + '</td>';
        detailsHtml += '<td>' + item.quantity + '</td>';
        detailsHtml += '<td>P' + item.price * item.quantity + '</td>';
        detailsHtml += '</tr>';
    });

    detailsHtml += '</table>';
    detailsHtml += '<h4>Total Price: P' + totalPrice + '</h4>';

    const popup = document.createElement('div');
    popup.style.position = 'fixed';
    popup.style.top = '25%';
    popup.style.left = '25%';
    popup.style.transform = 'translate(-50%, -50%)';
    popup.style.backgroundColor = 'white';
    popup.style.padding = '20px';
    popup.style.boxShadow = '0 0 10px rgba(0, 0, 0, 0.1)';
    popup.style.zIndex = '1000';
    popup.innerHTML = detailsHtml;

    document.body.appendChild(popup);
}

<?php if ($order_placed): ?>
document.addEventListener('DOMContentLoaded', function() {
    const orderId = <?php echo $checkout_id; ?>;
    const orderDetails = <?php echo json_encode($order_details); ?>;
    const totalPrice = <?php echo $total_price; ?>;
    showOrderDetails(orderId, orderDetails, totalPrice);
});
<?php endif; ?>
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
        <p style="color: aquamarine; font-size: 20px;font-weight: bold; text-shadow: 1px 1px cornflowerblue;">
            Total Price: <u><span class="price-amount" style="color: white;">P<?php echo number_format($total_price, 2); ?></span></u>
        </p>
        <button class="add-product-button" onclick="toggleADDForm()" style="display:block; margin: auto;">Order Now!</button>
        
        <div class="admin-product-form-container hidden">>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="showOrderSuccessAlert()">
                <h3>Checkout</h3>
                <input type="text" placeholder="Enter Your Name" name="customer_name" class="box" required>
                <select name="payment_method" class="box" required>
                    <option value="">Select Payment Method</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Debit Card">Debit Card</option>
                    <option value="paypal">PayPal</option>
                    <option value="gcash">GCash</option>
                    <option value="cash">Cash</option>
                </select>
                <input type="submit" class="btn" name="checkout" value="Place Order">
            </form>
        </div>

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
                    <form method="post" action="Cart-Customer.php">
                        <input type="number" min="1" name="quantity" value="'.$row['quantity'].'" onchange="updatePrice(this)" style="background-color:black; color:white;width:35px; text-align: center; font-size: 20px">
                        <input type="hidden" name="product_id" value="'.$row['product_id'].'">
                        <input type="hidden" name="update_quantity" value="1">
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
<script src="browse-admin-script.js"></script>
</body>
</html>
