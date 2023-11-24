<head>
<style>
        .edit-form {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1100;
            background:rgba(0,0,0,.8);
            padding: 2rem;
            display: none;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            width: 100%;
        }
    </style>
</head>
<?php include('../includes/db.php');
include('../includes/header.php');

if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    

    $query1 = "DELETE FROM student_record WHERE studid = '$id'";
    $delete1 = mysqli_query($conn,$query1);

    $query2 = "DELETE FROM tbstudifo WHERE studid = '$id'";
    $delete2 = mysqli_query($conn,$query2);

    if($delete1 && $delete2){
        header("Location:../admin/customerDetails.php?message=The Customer Successfully Remove");
    }
}

if(isset($_POST['update_customer'])){
    $id = $_POST['id'];
    $srcode = $_POST['srcode'];
    $pass = $_POST['pass'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $dept = $_POST['dept'];
    $cnum = $_POST['cnum'];

    $query1 = "UPDATE student_record SET SR_Code = '$srcode', pass = '$pass', email = '$email', cnum = '$cnum' WHERE studid = '$id'";
    $update1 = mysqli_query($conn, $query1);

    // Update second table
    $query2 = "UPDATE tbstudinfo SET firstname = '$fname', lastname = '$lname', course = '$dept' WHERE studid = '$id'";
    $update2 = mysqli_query($conn, $query2);

    if($update1 && $update2){
        header("Location:../admin/customerDetails.php?message=The Customer Successfully Update");
    }else {
        mysqli_rollback($conn);
        echo "Something went wrong. Transaction rolled back.";
    }
}
?>

<div class="container">
    <div class="row-mt-5">
    <?php if (isset($_GET['message'])) { ?>
            <p class="text-center bg-primary-subtle p-4 mt-3 error"><?php echo $_GET['message']; ?></p>
        <?php } ?>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand ms-3" href="#">Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="mx-auto"></div>
                <div class="navbar-nav">
                    <a class="nav-link me-3" href="../admin/admin.php"><h4>Home</h4></a>
                    <a class="nav-link me-3" href="../admin/addproduct.php"><h4> Add Product</h4></a>
                    <a class="nav-link active me-3 btn btn-outline-primary btn-rounded text-wrap" style="color:white ;" aria-current="page"  href="../admin/customerDetails.php"><h4> Customer Details</h4></a>
                    <a class="nav-link me-3" href="../admin/orders.php"><h4> Orders</h4></a>
                    <a class="nav-link me-3" href="../admin/contactus.php"><h4> Messages</h4></a>
                    <a href="loginadmin.php" class="btn btn-danger fs-5 fw-bold">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <h1 class = "text-center mt-3">Customer Details</h4>
    <table class="table table-striped table-bordered table-hover mt-5">
            <thead class="table-dark">
                <tr>
                    <th scope="col">SR-Code</th>
                    <th scope="col">Password</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Department</th>
                    <th scope="col">Program and Section</th>
                    <th scope="col">Cellphone Number</th>
                    <th scope="col" colspan="3">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM student_record";
                $select = mysqli_query($conn,$query);

                if(mysqli_num_rows($select)>0){
                    while($row = mysqli_fetch_assoc($select)){
                        $id = $row['studid'];
                        $srcode = $row['SR_Code'];
                        $pass = $row['pass'];
                        $email = $row['email'];
                        $cnum = $row['cnum'];
                        
                $query_info = "SELECT * FROM tbstudinfo WHERE studid = '$id'";
                $info = mysqli_query($conn, $query_info);

                if ($info && mysqli_num_rows($info) > 0) {
                    $inforow = mysqli_fetch_assoc($info);
                    $fname = $inforow['firstname'];
                    $lname = $inforow['lastname'];
                    $course = $inforow['course'];
                } 
                    ?>
                    <tr>
                        <td><?php echo $srcode ?></td>
                        <td><?php echo $pass ?></td>
                        <td><?php echo $fname ?></td>
                        <td><?php echo $lname ?></td>
                        <td><?php echo $email ?></td>
                        <td><?php echo $course ?></td>
                        <td><?php echo $cnum?></td>
                        <td>
                            <a href = "../admin/customerDetails.php?update=<?php echo $row['SR_Code']?>" class="btn btn-primary">Update</a>
                        </td>
                        <td>
                            <a href = "../admin/customerDetails.php?delete=<?php echo $id?>" class="btn btn-primary">Delete</a>
                        </td>
                    </tr>
                <?php    
                    };
                }else{
                    echo "<span>No Product Added</span>";
                }
                
                ?>
            </tbody>
        </table>
        <section class="edit-form">
        <?php

        if(isset($_GET['update'])){
            $srcode = $_GET['update'];
            $query = "SELECT * FROM student_record WHERE SR_Code = '{$srcode}'";
            $edit = mysqli_query($conn,$query);
            if(mysqli_num_rows($edit) > 0){
                while($row = mysqli_fetch_assoc($edit)){
                    $id = $row['studid'];
                    $srcode = $row['SR_Code'];
                    $pass = $row['pass'];
                    $email = $row['email'];
                    $cnum = $row['cnum'];
                    $query_info = "SELECT * FROM tbstudinfo WHERE studid = '$id'";
                $info = mysqli_query($conn, $query_info);

                if ($info && mysqli_num_rows($info) > 0) {
                    $inforow = mysqli_fetch_assoc($info);
                    $fname = $inforow['firstname'];
                    $lname = $inforow['lastname'];
                    $course = $inforow['course'];
                } 
        ?>
        <div class="col-lg-4 bg-white m-auto border border-danger-subtle p-3">
            <form action="" method="post" enctype="multipart/form-data">
                
            <input type="text" class="form-control" hidden value="<?php echo $id?>" name="id" >

                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" class="form-control" required value="<?php echo $srcode?>" name="srcode">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text"  required  class="form-control" value="<?php echo $pass?>" name="pass" >
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text"  required  class="form-control" value="<?php echo $fname?>" name="fname" >
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text"  required  class="form-control" value="<?php echo $lname?>" name="lname" >
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text"  required  class="form-control" value="<?php echo $email?>" name="email" >
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" required  class="form-control" value="<?php echo $course?>" name="dept" >
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" required  class="form-control" value="<?php echo $cnum?>" name="cnum" >
                </div>
                <div class="container d-grid">
                    <input type="submit" value = "Update" class="btn btn-success mb-3" name="update_customer">
                    <a href="#"class="btn btn-warning" id="close_edit">Cancel</a>
                </div>
            </form>
        </div>
        <?php
            };
        };echo "<script>document.querySelector('.edit-form').style.display = 'flex';</script>";
    };
        ?>
    </section>
    </div>
</div>
<script>
    document.querySelector('#close_edit').onclick = () =>{
        document.querySelector('.edit-form').style.display = 'none';
        window.location.href = 'customerDetails.php'
    }

    function scrollToEditForm(){
        var target = document.getElementById("edit-form");

        if(target){
            target.scrollIntoView({behavior:"smooth"});
        }
    }
</script>
