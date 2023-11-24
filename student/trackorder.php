<?php
include('../includes/db.php');
include('../includes/header.php');
?>

<head>
    <style>
    body {
        background-image: url('../img/bg-bsu.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .bg-container {
        /* Add some padding or styling for better visibility */
        background-color: rgba(255, 255, 255, 0.8);
        width: 100%;
        height: 100%;
    }

    .card-container {
        display: flex;
        flex-wrap: nowrap;
        justify-content: center;
    }

    .card {
        margin: 1rem;
    }
    </style>
</head>
<?php
session_start();
if (isset($_SESSION['course'])) {
?>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand ms-3" href="#">Track my Order</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
            aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="mx-auto"></div>
            <div class="navbar-nav">
                <a class="nav-link me-3" aria-current="page" href="../student/home.php">
                    <h4><i class="bi bi-house-door-fill"></i></h4>
                </a>
                <a class="nav-link me-3 active" aria-current="page" href="../student/trackorder.php">
                    <h4><i class="bi bi-geo-alt-fill"></i></h4>
                </a>
                <a class="nav-link me-3" href="../student/contactus.php">
                    <h4><i class="bi bi-envelope-fill"></i></h4>
                </a>

                <div class="container">
                    <a type="button" class="me-3 btn btn-outline-success btn-rounded w-100"
                        href="../student/shopcart.php">
                        <h4><i class="bi bi-cart-fill"></i></h4>
                    </a>
                </div>
                <a href="logout.php" class="btn btn-danger mb-3 mt-2 fw-bold">Logout</a>
            </div>
        </div>
    </div>
</nav>
<div class="bg-container">
    <div class="container-xl">

        <body>
            <h1 class="text-center p-3">My Orders</h1>
            <?php
                 $ordercost = 0;
                 $srcode = $_SESSION['SR_Code'];
     
                 $query1 = "SELECT OrderID, Status, OrderCost FROM orderdb WHERE SR_Code = '$srcode' ";
                 $select_order = mysqli_query($conn, $query1);
     
                 if (mysqli_num_rows($select_order) > 0) {
                     echo '<div class="row card-container">';
                     while ($order_row = mysqli_fetch_assoc($select_order)) {
                         $orderid = $order_row['OrderID'];
                         $status = $order_row['Status'];
     
                         if($status == "Approved"){
                             $status = "Your order is approved you can get it now in 5th floor of CICS Building";
                         }
     
                         $query2 = "SELECT ProductID, Quantity, TotalPrice FROM orderitems WHERE OrderID = '$orderid'";
                         $select_order_items = mysqli_query($conn, $query2);
     
                         if (mysqli_num_rows($select_order_items)) {
                             while ($items_row = mysqli_fetch_assoc($select_order_items)) {
                                 $productid = $items_row['ProductID'];
                                 $quan = $items_row['Quantity'];
                                 $totalprice = $items_row['TotalPrice'];
     
                                 $query3 = "SELECT * FROM productdb WHERE ProductID = '$productid'";
                                 $select_product = mysqli_query($conn, $query3);
                 ?>
                                 <?php
     
                                 if (mysqli_num_rows($select_product) > 0) {
                                     while ($product_row = mysqli_fetch_assoc($select_product)) {
                                         $product_name = $product_row['ProductName'];
                                         $price = $product_row['Price'];
                                         $image = $product_row['image'];
                                 ?>
                                         <div class="card my-3 col-4 border border-dark p-3" style="width: 17rem;">
                                             <img src="../uploadedimg/<?php echo "$image" ?>" class="card-img-top" alt="...">
                                             <div class="card-body">
                                                 <h5 class="card-title"><?php echo "$product_name" ?></h5>
                                                 <p class="card-text">Price: &#8369; <?php echo "$price" ?>.00</p>
                                                 <p class="card-text">Quantity: <?php echo "$quan" ?></p>
                                                 <p class="card-text">Total Price: &#8369; <?php echo "$totalprice" ?>.00</p>
                                                 <p class="card-text">Status: <?php echo "$status" ?></p>
                                             </div>
                                         </div>
                                         
                 <?php 
                 $ordercost+=  $totalprice;
                 }
                                 }
                             }
                         }
                     }
                     echo '</div>';
                } ?>
            <?php
        } else {
            header('Location:login.php');
            exit();
        }
            ?>

        </body>

    </div>

</div>
<div class="bg-primary-subtle p-5">
    <h3 class="text-center"> Order Cost: &#8369;<?php echo "$ordercost" ?>.00 </h3>
</div>