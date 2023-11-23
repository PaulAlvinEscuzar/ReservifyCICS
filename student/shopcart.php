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
    </style>
</head>
<?php
include('../includes/db.php');
include('../includes/header.php');
// this is for session 
session_start();

if (isset($_SESSION['SR_Code'])) {

    // For Check Out button
    if (isset($_GET['checkout'])) {
        $total = $_GET['checkout'];
        $status = "For approval";
        $date = date('Y-m-d H:i:s');
        $srcode = $_SESSION['SR_Code'];
        $order_query = "INSERT INTO orderdb(SR_Code, Orderdate, Status, OrderCost) VALUES('{$srcode}','{$date}','{$status}','{$total}')";

        if (mysqli_query($conn, $order_query)) {
            $orderID = mysqli_insert_id($conn);

            // select for db shopcart
            $query = "SELECT * FROM shopcart WHERE SR_CODE = '$srcode'";
            $select = mysqli_query($conn, $query);

            if (mysqli_num_rows($select) > 0) {
                while ($row = mysqli_fetch_assoc($select)) {
                    $productid = $row['ProductID'];
                    $quan = $row['Quantity'];

                    // select for db productdb
                    $product_query = "SELECT Price, AvailStocks FROM productdb WHERE ProductID = $productid";
                    $product = mysqli_query($conn, $product_query);

                    if ($product && $product_row = mysqli_fetch_assoc($product)) {
                        $price = $product_row['Price'] * $quan;
                        $stocks = $product_row['AvailStocks'];

                        // Insert into orderitems
                        $orderitem_query = "INSERT INTO orderitems(OrderID, ProductID, Quantity, TotalPrice) VALUES('$orderID', '$productid', '$quan', '$price')";
                        $order = mysqli_query($conn, $orderitem_query);

                        if ($order) {
                            header("Location: ../student/home.php?message=Thankyou for Reserving, Your Order is now For Approval");
                            $newstocks = $stocks - $quan;
                            $delete_query = "DELETE FROM shopcart WHERE SR_Code = '$srcode'";
                            $delete = mysqli_query($conn, $delete_query);
                            $update_query = "UPDATE productdb SET AvailStocks = '{$newstocks}' WHERE ProductID = '$productid'";
                            $update_stocks = mysqli_query($conn, $update_query);

                            if (!$update_stocks) {
                                // Handle the update error if needed
                                echo "Error updating stocks: " . mysqli_error($conn);
                            }
                        }
                    }
                }
            } else {
                header("Location: ../student/home.php?message=Your cart is empty.");
            }
        }
    }
    // For updating the quantity of Product
    if (isset($_POST['update_product'])) {
        $id = $_POST['up_quan_id'];
        $up_quan = $_POST['up_quan'];

        $query = "UPDATE shopcart SET Quantity = '$up_quan' WHERE CartID = '$id'";
        $update_quan = mysqli_query($conn, $query);

        if ($update_quan) {
            header("Location:../student/shopcart.php?message=Quantity Successfully Update");
        }
    }

    if (isset($_GET['cartid'])) {
        $cartid = $_GET['cartid'];

        $query = "DELETE FROM shopcart WHERE cartid = '$cartid'";
        $delete = mysqli_query($conn, $query);

        if ($delete) {
            header("Location:../student/shopcart.php?message=Product Successfully Delete");
        }
    }
?>
    <!--nav bar-->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand ms-3" href="#">Add to Cart</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="mx-auto"></div>
                <div class="navbar-nav">
                    <a class="nav-link me-3" href="../student/home.php">
                        <h4><i class="bi bi-house-door-fill"></i></h4>
                    </a>
                    <a class="nav-link me-3" href="../student/trackorder.php">
                        <h4><i class="bi bi-geo-alt-fill"></i></h4>
                    </a>
                    <a class="nav-link me-3" href="../student/contactus.php">
                        <h4><i class="bi bi-envelope-fill"></i></h4>
                    </a>
                    <div class="container">
                        <a type="button" aria-current="page" class="me-3 btn btn-outline-success btn-rounded w-100 active" href="../student/cart.php">
                            <h4><i class="bi bi-cart-fill"></i></h4>
                        </a>
                    </div>
                    <a href="logout.php" class="btn btn-danger mb-3 mt-2 fw-bold">Logout</a>
                </div>
            </div>
        </div>
    </nav>
    <!--end of nav bar-->
    <div class="bg-container">
    <?php if (isset($_GET['message'])) { ?>
                <p class="text-center bg-primary-subtle p-4 error"><?php echo $_GET['message']; ?></p>
            <?php } ?>
        <div class="container-xxl">

            <body>


                <div class="row-mt-5">
                    <h3 class="text-center p-3"> Shopping Cart</h3>
                    <!--Table Start-->

                    <table class="table table-striped table-bordered table-hover mt-5">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">Product Image</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Price</th>
                                <th scope="col" colspan="2">Quantity</th>
                                <th scope="col">Cost</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $srcode = $_SESSION['SR_Code'];
                            $query = "SELECT * FROM shopcart WHERE SR_Code = '$srcode'";
                            $display = mysqli_query($conn, $query);
                            $total = 0;

                            if (mysqli_num_rows($display) > 0) {
                                while ($row = mysqli_fetch_assoc($display)) {
                                    $productID = $row['ProductID'];
                                    $quan = $row['Quantity'];
                                    $cartid = $row['CartID'];
                                    $product_sql = "SELECT ProductName, Price, image FROM productdb WHERE ProductID = '$productID'";
                                    $select_product = mysqli_query($conn, $product_sql);

                                    if (mysqli_num_rows($select_product) > 0) {
                                        while ($row = mysqli_fetch_assoc($select_product)) {
                                            $pimage = $row['image'];

                            ?>
                                            <tr>
                                                <td><img src="../uploadedimg/<?php echo "$pimage"; ?>" height="108px"></td>
                                                <td><?php echo $row['ProductName'] ?></td>
                                                <td>&#8369;<?php echo $row['Price'] ?>.00</td>
                                                <td>
                                                    <form action="" method="POST">
                                                        <input type="hidden" value="<?php echo $cartid ?>" name="up_quan_id">
                                                        <div class="input-group mb-3">
                                                            <input type="number" min="1" value="<?php echo $quan ?>" name="up_quan" class="form-control text-center p-1">
                                                        </div>
                                                <td>
                                                    <div class="container d-grid">
                                                        <input type="submit" value="Update" class="btn btn-success mb-3" name="update_product">
                                                    </div>
                                                </td>
                                                </form>
                                                </td>
                                                <!--For the pricing of product-->
                                                <td>&#8369;<?php echo $subtotal =  $row['Price'] * $quan ?>.00</td>
                                                <td class="text-center">
                                                    <a href="../student/shopcart.php?cartid=<?php echo $cartid; ?>" class='btn btn-success'>Delete</a>
                                                </td>
                                            </tr>
                            <?php
                                            $total += $subtotal;
                                        }
                                    }
                                };
                            };
                            ?>
                            <tr>
                                <td colspan="5" class="pt-3 pb-3">
                                    <h3 class="text-center text-monospace">Total:</h3>
                                </td>
                                <td>
                                    <h3 class="text-center text-monospace">&#8369;<?php echo $total ?>.00</h3>
                                </td>
                                <td class="text-center">
                                    <a href="../student/shopcart.php?checkout=<?php echo $total; ?>" class='btn btn-outline-danger mt-2' name="done_shop">Done Shopping</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!--End of the table-->
                </div>
            </body>
        </div>
    </div>
<?php
} else {
    header('Location:login.php');
    exit();
}
?>