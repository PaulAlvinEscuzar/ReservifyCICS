<?php include('../includes/db.php');
include('../includes/header.php');
?>

<div class="container-lg">
    <div class="row-mt-5">
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
                    <div class="navbar-nav mt-3">
                        <a class="nav-link active me-3 btn btn-outline-primary btn-rounded text-wrap"
                            style="color:white ;" aria-current="page" href="#">
                            <h4>Home</h4>
                        </a>
                        <a class="nav-link me-3" href="../admin/addproduct.php">
                            <h4> Add Product</h4>
                        </a>
                        <a class="nav-link me-3" href="../admin/customerDetails.php">
                            <h4> Customer Details</h4>
                        </a>
                        <a class="nav-link me-3" href="../admin/orders.php">
                            <h4> Orders</h4>
                        </a>
                        <a class="nav-link me-3" href="../admin/contactus.php"><h4> Messages</h4></a>
                        <a href="loginadmin.php" class="btn btn-danger fs-5 fw-bold">Logout</a>
                    </div>
                </div>
            </div>
        </nav>
        <h2 class="text-center">Reports</h2>
        <div class="col-lg-4 bg-white m-auto mt-5">
            <form action="" method="POST">
                <h2 class="text-center mb-5">Enter Begin and End Date</h2>
                <div class="input-group mb-3 d-flex justify-content-between">
                    <div class="input-group-text">
                        <input type="date" name="begindate">
                    </div>
                    <div class="input-group-text">
                        <input type="date" name="enddate">
                    </div>
                </div>
                <div class="container d-grid">
                    <input type="submit" id="btn-submit" class="btn btn-success mb-3" onclick="showbutton()" name="show_reports">
                </div>
                
            </form>
        </div>
        <button class="btn btn-success " id="btn-print" onclick="printTable()">Print Table</button>
        <table class="table table-striped table-bordered table-hover mt-5">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Department</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Total Cost</th>
                    <th scope="col">Order Date</th>

                </tr>
            </thead>
            <tbody>
        <?php
        if (isset($_POST['show_reports'])) {
            $begindate = $_POST['begindate'];
            $enddate = $_POST['enddate'];
            $total = 0;

            if ($begindate > $enddate) {
                echo "<script type = 'text/javascript'>alert('Your begin date is after your end date. Please try again.')</script>";
                exit();
            } else {
        ?>
        <h1 class="text-center" id="report-name"> Reports From <?php echo "$begindate" ?> to <?php echo "$enddate" ?></h1>
        <?php

                $query = "SELECT * FROM orderdb WHERE Orderdate BETWEEN '$begindate' AND '$enddate';";
                $select_date = mysqli_query($conn, $query);

                if (mysqli_num_rows($select_date) > 0) {
                    while ($row = mysqli_fetch_assoc($select_date)) {
                        $orderdate = $row['Orderdate'];
                        $orderid = $row['OrderID'];
                        $srcode = $row['SR_Code'];
                        $total = $row['OrderCost'];

                        $customer_query = "SELECT firstname, lastname, dept FROM student_record WHERE SR_Code = '$srcode'";
                        $view_customer = mysqli_query($conn, $customer_query);

                        if (mysqli_num_rows($view_customer) > 0) {
                            while ($customer_row = mysqli_fetch_assoc($view_customer)) {
                                $firstname = $customer_row['firstname'];
                                $lastname = $customer_row['lastname'];
                                $department = $customer_row['dept'];

                                // Code for displaying the products
                                $query2 = "SELECT * FROM orderitems WHERE OrderID = '$orderid';";
                                $select_products = mysqli_query($conn, $query2);

                                if (mysqli_num_rows($select_products) > 0) {
                                    while ($product_row = mysqli_fetch_assoc($select_products)) {
                                        $productid = $product_row['ProductID'];
                                        $quantity = $product_row['Quantity'];
                                        $totalprice = $product_row['TotalPrice'];

                                        $product_query = "SELECT * FROM productdb WHERE ProductID = '$productid';";
                                        $view_product = mysqli_query($conn, $product_query);


                                        while ($product_data = mysqli_fetch_assoc($view_product)) {
                                            $productname = $product_data['ProductName'];
                                            $image = $product_data['image'];

                                            ?>
                                            <tr>
                        <td><?php echo "$firstname"?> <?php echo "$lastname"?></td>
                        <td><?php echo "$department"?></td>
                        <td><?php echo "$productname"?></td>
                        <td><?php echo " $quantity"?></td>
                        <td class="bg-info-subtle">&#8369;<?php echo " $totalprice"?>.00</td>
                        <td><?php echo " $orderdate"?></td>
                    </tr>
                                        <?php
                                        }
                                    }
                                }

                            }
                        }
                    }
                } else {
                    echo "<script type = 'text/javascript'>alert('No reports to display.')</script>";
                }
            }
        }
        ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    var reportNameElement = document.getElementById('report-name');
    function printTable() {
        var printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Print Table</title>');
        printWindow.document.write('<link rel="stylesheet" href="../includes/bootstrap.css">');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<style>body {margin: 20px;} .table tr th{color: black;} </style>');
        printWindow.document.write(reportNameElement.innerHTML);
        printWindow.document.write(document.querySelector('.table').outerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
</script>