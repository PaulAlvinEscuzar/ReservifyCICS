<?php
include '../includes/header.php';
include '../includes/db.php';
?>
<head>
    <style>
        .table-striped tbody tr td {
    max-width: 200px;
    word-wrap: break-word;
}
    </style>
</head>
<div class="container">
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
                    <div class="navbar-nav">
                    <a class="nav-link me-3" href="../admin/admin.php">
                            <h4> Home</h4>
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
                        <a class="nav-link active me-3 btn btn-outline-primary btn-rounded text-wrap"
                            style="color:white ;" aria-current="page" href="../admin/contactus.php">
                            <h4>Messages</h4>
                        </a>
                        <a href="loginadmin.php" class="btn btn-danger fs-5 fw-bold">Logout</a>
                    </div>
                </div>
            </div>
        </nav>

    <table class="table table-striped table-bordered table-hover mt-5">
            <thead class="table-dark">
                <tr>
                    <th scope="col">SR_Code</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Subject</th>
                    <th scope="col">Message</th>
                </tr>
            </thead>
            <tbody>
            <?php
            
            $mail = "SELECT * FROM contactus";
            $selectmail = mysqli_query($conn,$mail);

            if(mysqli_num_rows($selectmail)>0){
                while($row_mail = mysqli_fetch_assoc($selectmail)){
                    $srcode = $row_mail['SR_Code'];
                    $subject = $row_mail['subject'];
                    $message = $row_mail['message'];

                    $query = "SELECT studid, email FROM student_record WHERE SR_Code = '$srcode'";
                    $studentview = mysqli_query($conn,$query);
                    if(mysqli_num_rows($studentview) > 0){
                        while($row = mysqli_fetch_assoc($studentview)){
                            $id = $row['studid'];
                            $email = $row['email'];
                            $info = "SELECT * FROM tbstudinfo WHERE studid = '$id'";
                            $query_info = mysqli_query($conn, $info);
                            if(mysqli_num_rows($query_info)>0){
                                while($inforow = mysqli_fetch_assoc($query_info)){
                                    $fname = $inforow['firstname'];
                                    $lname = $inforow['lastname'];
                                }
                            }
                        ?>
                        <tr>
                            <td><?php echo"$srcode"?></td>
                            <td><?php echo"$fname"?> <?php echo"$lname"?></td>
                            <td><?php echo"$email"?></td>
                            <td><?php echo"$subject"?></td>
                            <td><?php echo "$message"?></td>
                        </tr>

                        <?php
                        }
                    }
                }
            } 
            
            
            
            
            ?>
            </tbody>
    </table>
    </div>
</div>