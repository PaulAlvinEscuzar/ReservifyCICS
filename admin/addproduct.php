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
<?php 
include '../includes/header.php';
include('../includes/db.php');

$add = 0;
$remove = 0;
$new = 0;
$message = array(); 
if(isset($_POST['submit'])){
    $pname = $_POST['pname'];
    $price = $_POST['price'];
    $pimage = $_FILES['pimage']['name'];
    $pimage_tmp_name = $_FILES['pimage']['tmp_name'];
    $pimage_folder = '../uploadedimg/'.$pimage;

    $query = "INSERT INTO `productdb` (ProductName,Price,image) VALUES ('{$pname}','{$price}','{$pimage}')";
    $insert = mysqli_query($conn,$query);
    
    if($insert){
        header("Location:../admin/addproduct.php?message=Product Add Successfully");
        move_uploaded_file($pimage_tmp_name, $pimage_folder);
        
    }
};

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    $query = "DELETE FROM productdb WHERE ProductID = '$delete_id' ";
    $delete = mysqli_query($conn,$query);

    if($delete){
        header("Location:../admin/addproduct.php?message=Product Delete Successfully");
    }
}
if(isset($_POST['addStocks'])){
    $productid = $_POST['productid'];
    $addstocks = $_POST['add_stocks'];
    
    $sql = "UPDATE productdb SET AvailStocks = '$addstocks' WHERE ProductID ='$productid'";
    $add_stocks = mysqli_query($conn,$sql);
     if($add_stocks){
        header("Location:../admin/addproduct.php?message=Successfully Add Stocks");
     }
}
if(isset($_POST['update_product'])){

    $update_id = $_POST['update_id'];
    $up_pname = $_POST['up_pname'];
    $up_price = $_POST['up_price'];
    $up_pimage = $_FILES['up_pimage']['name'];
    $up_pimage_tmp_name = $_FILES['up_pimage']['tmp_name'];
    $up_pimage_folder = '../uploadedimg/'.$up_pimage;

    $query = "UPDATE productdb SET ProductName = '{$up_pname}', Price = '{$up_price}', image = '{$up_pimage}' WHERE ProductID = $update_id";
    $update = mysqli_query($conn,$query);

    if($update){
        move_uploaded_file($up_pimage_tmp_name, $up_pimage_folder);
        header("Location:../admin/addproduct.php?message=Product Updated Successfully");
    }
}
?>
<div class="container">
    <div class = "row-mt-5">
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
                    <a class="nav-link active me-3 btn btn-outline-primary btn-rounded text-wrap" style="color:white ;" aria-current="page"  href="../admin/addproduct.php"><h4> Add Product</h4></a>
                    <a class="nav-link me-3" href="../admin/customerDetails.php"><h4> Customer Details</h4></a>
                    <a class="nav-link me-3" href="../admin/orders.php"><h4> Orders</h4></a>
                    <a class="nav-link me-3" href="../admin/contactus.php"><h4> Messages</h4></a>
                    <a href="loginadmin.php" class="btn btn-danger fs-5 fw-bold">Logout</a>
                    
                </div>
            </div>
        </div>
    </nav>
    <?php if (isset($_GET['message'])) { ?>
            <p class="text-center bg-primary-subtle p-4 mt-3 error"><?php echo $_GET['message']; ?></p>
        <?php } ?>
        <div class="col-lg-4 bg-white m-auto border border-danger-subtle mt-5">
            <form action="" method="post" enctype="multipart/form-data">
                <h3 class="text-center">Add New Product</h3>
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" class="form-control" placeholder="Enter Product Name" name="pname" required>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="number" min="0"  class="form-control" placeholder="Enter Product Price" name="price" required>
                </div>
                <div class="input-group mb-3">
                    <input type="file" class="form-control" name="pimage" accept="image/png, image/jpg, image/jpeg" required>
                </div>
                <div class="container d-grid">
                    <input type="submit" class="btn btn-success mb-3" name="submit">
                    <a href="#"class="btn btn-warning">Back</a>
                </div>
            </form>
        </div>

        <table class="table table-striped table-bordered table-hover mt-5">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Product Image</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Available Stocks</th>
                    <th scope="col" colspan="2">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM productdb";
                $select = mysqli_query($conn,$query);

                if(mysqli_num_rows($select)>0){
                    while($row = mysqli_fetch_assoc($select)){
                        $productid = $row['ProductID'];
                        $pname = $row['ProductName'];
                        $price = $row['Price'];
                        $pimage = $row['image'];
                        $stocks = $row['AvailStocks'];
                    ?>
                    <tr>
                        <td class="text-center"><img src="../uploadedimg/<?php echo "$pimage"?>" width="120" height="150px"></td>
                        <td class="text-center"><?php echo "$pname"?></td>
                        <td class="text-center">&#8369;<?php echo " $price"?>.00</td>
                        <td class="text-center align-middle">
                            <form action="" method="POST">
                                <input type="hidden" value="<?php echo $productid?>" name="productid">
                                <div class="container d-grid mb-5">
                                <div class="input-group my-3">
                                    <input type="number" min="1" value="<?php echo $stocks?>" name="add_stocks" class="form-control text-center p-1">
                                </div>
                                    <input type="submit" value = "Add Stocks" class="btn btn-success mb-3" name="addStocks">
                                </div>
                            </form>
                        </td>
                        <td  class="text-center">
                            <a href="../admin/addproduct.php?delete=<?php echo $row['ProductID'];?>" class='btn btn-primary
                            ' onclick="return confirm('Are you sure to delete this?')">Delete</a>
                        </td>
                        <td class="text-center">
                            <a href="../admin/addproduct.php?edit=<?php echo $row['ProductID'];?>" class='btn btn-success' onclick="scrollToEditForm()">Update</a>
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

        if(isset($_GET['edit'])){
            $edit_id = $_GET['edit'];
            $query = "SELECT * FROM productdb WHERE ProductID = '{$edit_id}'";
            $edit = mysqli_query($conn,$query);
            if(mysqli_num_rows($edit) > 0){
                while($row = mysqli_fetch_assoc($edit)){
                    $pimage = $row['image'];
        ?>
        <div class="col-lg-4 bg-white m-auto border border-danger-subtle mt-5 mb-5">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="container-lg d-flex justify-content-center p-3 bg-light">
                    <img src="../uploadedimg/<?php echo "$pimage"; ?>" height="200">
                </div>
                <input type="hidden" name="update_id" value="<?php echo $row['ProductID']; ?>">
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-cart4"></i></span>
                    <input type="text" class="form-control" required value="<?php echo $row['ProductName']?>" name="up_pname" SS>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">Price</span>
                    <input type="number" min="0" required  class="form-control" value="<?php echo $row['Price']?>" name="up_price" >
                </div>
                <div class="input-group mb-3">
                    <input type="file" class="form-control" required name="up_pimage" accept="image/png, image/jpg, image/jpeg" >
                </div>
                <div class="container d-grid">
                    <input type="submit" value = "Update" class="btn btn-success mb-3" name="update_product">
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
        window.location.href = 'addproduct.php'
    }
</script>
