<?php

@include 'get_products.php';

$id = $_GET['edit'];

if(isset($_POST['update_product'])){

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_FILES['product_image']['name'];
   $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
   $product_image_folder = 'Uploaded_Imgs/'.$product_image;

   if(empty($product_name) || empty($product_price) || empty($product_image)){
      $message[] = 'Please fill out the empty boxes';    
   }else{

      $update_data = "UPDATE products SET productName='$product_name', Price='$product_price', image='$product_image'  WHERE productID = '$id'";
      $upload = mysqli_query($conn, $update_data);

      if($upload){
         move_uploaded_file($product_image_tmp_name, $product_image_folder);
         header('location:Browse.php');
      }else{
         $$message[] = 'Please fill out the empty boxes'; 
      }

   }
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="dashboard-style.css">
<link rel="stylesheet" type="text/css" href="browse-admin-style.css">
<title>SoftyBevy Update Items</title>
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
                <li>
                  <a href="Orders.php"class="nav-link">Orders</a>
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


<div class="admin-product-form-container centered">

   <?php
      
      $select = mysqli_query($conn, "SELECT * FROM products WHERE productID = '$id'");
      while($row = mysqli_fetch_assoc($select)){

   ?>
   
   <form action="" method="post" enctype="multipart/form-data">
      <h3 class="title">update the product</h3>
      <input type="text" class="box" name="product_name" value="<?php echo $row['productName']; ?>" placeholder="enter the product name">
      <input type="number" min="0" class="box" name="product_price" value="<?php echo $row['Price']; ?>" placeholder="enter the product price">
      <input type="file" class="box" name="product_image"  accept="image/png, image/jpeg, image/jpg">
      <input type="submit" value="update product" name="update_product" class="btn">
      <a href="Browse.php" class="btn">go back!</a>
   </form>
   


   <?php }; ?>

   

</div>

</div>

</body>
</html>