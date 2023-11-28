<head>
    <style>
    .edit-form {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1100;
        background: rgba(0, 0, 0, .8);
        padding: 2rem;
        display: none;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        width: 100%;
    }

    .update-status {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1100;
        background: rgba(0, 0, 0, .8);
        padding: 2rem;
        display: none;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        width: 100%;
    }
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
<?php include('../includes/db.php');
include('../includes/header.php');

if (isset($_POST['update'])) {
    $orderid = $_POST['orderid'];
    $selected_status = $_POST['status'];

    $query = "UPDATE orderdb SET Status = '$selected_status' WHERE OrderID = '$orderid' ";
    $update = mysqli_query($conn, $query);

    if ($update) {
        header("Location:../admin/orders.php?message=Status has been successfully update");
    }
}

if (isset($_GET['delete'])) {
    $orderid = $_GET['delete'];

    $query = "DELETE FROM orderdb WHERE OrderID = '$orderid'";
    $delete = mysqli_query($conn, $query);

    if ($delete) {
        header("Location:../admin/orders.php?message=Order has been deleted");
    }
}
?>

        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand ms-3" href="#">Admin</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="mx-auto"></div>
                    <div class="navbar-nav">
                        <a class="nav-link me-3" href="../admin/admin.php">
                            <h4>Home</h4>
                        </a>
                        <a class="nav-link me-3" href="../admin/addproduct.php">
                            <h4> Add Product</h4>
                        </a>
                        <a class="nav-link me-3" href="../admin/customerDetails.php">
                            <h4> Customer Details</h4>
                        </a>
                        <a class="nav-link active me-3 btn btn-outline-primary btn-rounded text-wrap"
                            style="color:white ;" aria-current="page" href="../admin/orders.php">
                            <h4> Orders</h4>
                        </a>
                        <a class="nav-link me-3" href="../admin/contactus.php">
                            <h4> Messages</h4>
                        </a>
                        <a href="loginadmin.php" class="btn btn-danger fs-5 fw-bold">Logout</a>
                    </div>
                </div>
            </div>
        </nav>
        <div class="bg-container">
        <?php if (isset($_GET['message'])) { ?>
        <p class="text-center bg-primary-subtle p-4 pt-3 error"><?php echo $_GET['message']; ?></p>
        <?php } ?>
            <body>
                
 
        <div class="container-lg">
        <div class="row-mt-5">
        <h1 class="text-center pt-3">Customer Details</h4>
            <div class="container-lg">
                <div class="row-md-5">
                    <table class="table table-striped table-bordered table-hover mt-5">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">Order ID</th>
                                <th scope="col">SR-Code</th>
                                <th scope="col">Name</th>
                                <th scope="col">Order Date</th>
                                <th scope="col">Status</th>
                                <th scope="col" colspan="3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM orderdb";
                            $select = mysqli_query($conn, $query);

                            if (mysqli_num_rows($select) > 0) {
                                while ($row = mysqli_fetch_assoc($select)) {
                                    $srcode = $row['SR_Code'];
                                    $orderid = $row['OrderID'];
                                    $orderdate = $row['Orderdate'];
                                    $status = $row['Status'];
                                    $query = "SELECT studid FROM student_record WHERE SR_Code = '$srcode'";
                                    $studentview = mysqli_query($conn, $query);
                                    if (mysqli_num_rows($studentview) > 0) {
                                        while ($row = mysqli_fetch_assoc($studentview)) {
                                            $id = $row['studid'];
                                            $info = "SELECT * FROM tbstudinfo WHERE studid = '$id'";
                                            $query_info = mysqli_query($conn, $info);
                                            if (mysqli_num_rows($query_info) > 0) {
                                                while ($inforow = mysqli_fetch_assoc($query_info)) {
                                                    $fname = $inforow['firstname'];
                                                    $lname = $inforow['lastname'];
                                                }
                                            }
                            ?>
                            <tr>
                                <td class="text-center"><?php echo "$orderid" ?></td>
                                <td class="text-center"><?php echo "$srcode" ?></td>
                                <td class="text-center"><?php echo $fname ?> <?php echo $lname ?></td>
                                <td class="text-center"><?php echo "$orderdate" ?></td>
                                <td class="text-center"><?php echo "$status" ?></td>
                                <td class="text-center">
                                    <form action="" method="POST">
                                        <input type="hidden" name="orderid" value="<?php echo $orderid ?>">
                                        <select class="form-select text-center" aria-label="Default select example"
                                            name="status">
                                            <option selected>Update Status</option>
                                            <option value="Approved">Approved</option>
                                            <option value="Still Approving">Still Approving</option>
                                        </select>
                                        <button class="btn btn-primary mt-2" name="update">Update Status</button>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <a href="../admin/orders.php?delete=<?php echo $orderid ?>"
                                        class="btn btn-danger">Delete</a>
                                </td>
                                <td class="text-center">
                                    <a href="../admin/orders.php?view=<?php echo $orderid ?>"
                                        class="btn btn-success">View Order</a>
                                </td>
                            </tr>
                            <?php
                                        }
                                    }
                                };
                            } else {
                                echo "<span>No Product Added</span>";
                            }

                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
</div>
            </body>
                        </div>

            </section>

            <!--For Viewing Order-->
            <section class="edit-form">
                <div class="col-lg-4 bg-white m-auto border border-danger-subtle mt-5 mb-5 p-3">
                    <button type="button" class="btn-close float-end" id="close" aria-label="Close"></button>
                    <h1 class="text-center mt-3">Order Details</h4>
                        <div class="container-lg" style="overflow-x: auto;">
                            <div class="row-md-5">
                                <table class="table table-striped table-bordered table-hover mt-5">
                                    <thead class="table-dark">
                                        <tr>
                                            <th scope="col">Order ID</th>
                                            <th scope="col">Product Name</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Total Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $price = 0;
                                        if (isset($_GET['view'])) {
                                            $view_order_id = $_GET['view'];
                                            $query = "SELECT * FROM orderitems WHERE OrderID = '$view_order_id'";
                                            $view = mysqli_query($conn, $query);
                                            if (mysqli_num_rows($view) > 0) {
                                                while ($row = mysqli_fetch_assoc($view)) {
                                                    $orderid = $row['OrderID'];
                                                    $productID = $row['ProductID'];
                                                    $quantity = $row['Quantity'];
                                                    $totalprice = $row['TotalPrice'];

                                                    $query_product = "SELECT ProductName, Price FROM productdb WHERE ProductID = '$productID'";
                                                    $view_product = mysqli_query($conn, $query_product);
                                                    if (!$view_product) {
                                                        die("Query failed: " . mysqli_error($conn));
                                                    }
                                                    if (mysqli_num_rows($view_product) > 0) {
                                                        while ($row_product = mysqli_fetch_assoc($view_product)) {
                                                            $price += $row_product['Price'] * $quantity;
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo "$orderid" ?></td>
                                            <td class="text-center"><?php echo $row_product['ProductName'] ?></td>
                                            <td class="text-center"><?php echo $quantity ?></td>
                                            <td class="text-center">&#8369;<?php echo "$totalprice" ?>.00</td>
                                        </tr>
                                        <?php
                                                            $total = $totalprice * $quantity;
                                                        }
                                                    }
                                                };
                                            };
                                            echo "<script>document.querySelector('.edit-form').style.display = 'flex';</script>";
                                        };
                                        ?>
                                        <tr>
                                            <td colspan="3" class="pt-3 pb-3">
                                                <h3 class="text-center text-monospace">Total:</h3>
                                            </td>
                                            <td>
                                                <h3 class="text-center text-monospace">&#8369;<?php echo $price ?>.00
                                                </h3>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
            </section>

<script>
document.querySelector('#close').onclick = () => {
    document.querySelector('.edit-form').style.display = 'none';
    window.location.href = 'orders.php'
}

function scrollToEditForm() {
    var target = document.getElementById("edit-form");

    if (target) {
        target.scrollIntoView({
            behavior: "smooth"
        });
    }
}
</script>