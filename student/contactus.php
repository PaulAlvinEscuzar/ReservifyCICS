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
include '../includes/header.php';
include '../includes/db.php'; 

session_start();

if (isset($_SESSION['course'])) {

if(isset($_POST['submit'])){
    $srcode = $_POST['srcode'];
    $subject = $_POST['subject'];
    $message = $_POST['txt'];
    
    $query = "INSERT INTO contactus(SR_Code,subject,message) VALUES('{$srcode}','{$subject}','{$message}')";
    $insert = mysqli_query($conn,$query);
    
    if($insert){
        header("Location:../student/contactus.php?message=Your message sent successfully");
    }

}
?>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand ms-3" href="#">Contact Us</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="mx-auto"></div>
                <div class="navbar-nav">
                    <a class="nav-link active me-3" aria-current="page" href="../student/home.php"><h4><i class="bi bi-house-door-fill"></i></h4></a>
                    <a class="nav-link me-3" href="../student/trackorder.php"><h4><i class="bi bi-geo-alt-fill"></i></h4></a>
                    <a class="nav-link me-3" href="../student/contactus.php"><h4><i class="bi bi-envelope-fill"></i></h4></a>
                    <div class="container">
                    <a type="button" class="me-3 btn btn-outline-success btn-rounded w-100" href="../student/shopcart.php"><h4><i class="bi bi-cart-fill"></i></h4></a>
                    </div>
                    <a href="logout.php" class="btn btn-danger mb-3 mt-2 fw-bold">Logout</a>
                </div>
            </div>
        </div>
    </nav>
<div class="bg-container">
<?php if (isset($_GET['message'])) { ?>
            <p class="text-center bg-primary-subtle p-4 error"><?php echo $_GET['message']; ?></p>
        <?php } ?>
<div class="container-xl">
<body>
    <h1 class="text-center p-5 mb-2 fw-bold">Contact Us</h1>
        <div class="h-auto d-flex justify-content-center">
            <div class="d-grid bg-light-subtle h-50 p-5 w-50 mb-5">
                <form action="" method="post">
                    <input type="hidden" name="srcode" placeholder="SR-Code" value="<?php echo $_SESSION['SR_Code']?>" class="form-control mb-2" required>
                    <input type="text" name="subject" placeholder="Subject" class="form-control mb-2" required>
                    <textarea name="txt" placeholder="Message" class="form-control" required></textarea>
                    <div class="text-center mt-2">
                    <button type="submit" name="submit" class="btn btn-success text-center">Submit</button>
                    </div>
                </form>
                <a  class="text-decoration-none" href="https://www.facebook.com/JPCSBatStateULipa"><i class="bi bi-facebook"> You can contact us in our Facebook page</i></a>
            </div>                    
        </div>
</body>
</div>
</div>
<?php
}else {
        header('Location:login.php');
        exit();
    }
?>