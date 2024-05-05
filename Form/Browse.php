<?php

@include 'get_products.php';

if(isset($_POST['add_product'])){
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_FILES['product_image']['name'];
    $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
    $product_image_folder = 'Uploaded_Imgs/'.$product_image;

    if(empty($product_name) || empty($product_price) || empty($product_image)){
        $message[] = 'Please fill out the empty boxes';
     }else{
        $insert = "INSERT INTO products(productName, Price, image) VALUES('$product_name', '$product_price', '$product_image')";
        $upload = mysqli_query($conn,$insert);
        if($upload){
           move_uploaded_file($product_image_tmp_name, $product_image_folder);
           $message[] = 'new product added successfully';
        }else{
           $message[] = 'could not add the product';
        }
     }
}

if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM products WHERE productID = $id");
    header('location:Browse.php');
 };
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="dashboard-style.css">
<link rel="stylesheet" type="text/css" href="browse-admin-style.css">
<title>SoftyBevy Browse Items</title>
</head>
<body>
    <div class="container">
    <div class="headercontainer">
        <header class="Dashheader">
            <span class="headerspan"><a class="a1" href="Dashboard.html">SoftyBevy</a></span>
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a href="Dashboard-Loggedin.php" class="nav-link" aria-current="page">Home</a>
                </li>
                <li class="nav-item">
                    <a href="Browse.php" class="nav-link active">Products</a>
                </li>
                <li class="nav-item">
                    <a href="Signin.php" class="nav-link">Signout</a>
                </li>
            </ul>
        </header>
    </div>

<?php
if(isset($message)){
   foreach($message as $message){
      echo '<span class="message">'.$message.'</span>';
   }
}

?>
    <div class="browse-container" style="padding:80px">
        <h2>Product List</h2>
        <button class="add-product-button" onclick="toggleADDForm()">Add New Product</button>
        <div class="admin-product-form-container hidden">
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                <h3>add new product</h3>
                <input type="text" placeholder="Enter Product Name" name="product_name" class="box">
                <input type="number" placeholder="Enter Product Price" name="product_price" class="box">
                <input type="file" accept="image/png, image/jpeg, image/jpg" name="product_image" class="box">
                <input type="submit" class="btn" name="add_product" value="add product">
            </form>
        </div>
        <?php

   $select = mysqli_query($conn, "SELECT * FROM products");
   
   ?>
   <div class="product-display">
    <table class="product-display-table">
       <thead>
       <tr>
          <th>product image</th>
          <th>product name</th>
          <th>product price</th>
          <th>action</th>
       </tr>
       </thead>
       <?php while($row = mysqli_fetch_assoc($select)){ ?>
       <tr>
          <td><img src="Uploaded_Imgs/<?php echo $row['image']; ?>" height="100" alt=""></td>
          <td><?php echo $row['productName']; ?></td>
          <td>P<?php echo $row['Price']; ?></td>
          <td>
             <a href="Browse-update.php?edit=<?php echo $row['productID']; ?>" class="btn">edit</a>
             <a href="Browse.php?delete=<?php echo $row['productID']; ?>" class="btn">delete</a>
          </td>
       </tr>
    <?php } ?>
    </table>
 </div>

    </div>
    </div>
<script src="browse-admin-script.js"></script>
</body>
</html>