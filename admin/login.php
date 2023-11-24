<?php

include '../includes/db.php';
session_start();
if (isset($_POST['adminid']) && isset($_POST['password'])) {
    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }
    $adminid = validate($_POST['adminid']);
    $password = validate($_POST['password']);

    if (empty($adminid)) {
        header('Location:../admin/loginadmin.php?error=Admin ID is Required');
        exit();
    } elseif (empty($password)) {
        header('Location:../admin/loginadmin.php?error=Password is Required');
        exit();
    } else {
        $query = "SELECT * FROM admindb WHERE adminID = '$adminid' AND password = '$password'";
        $login = mysqli_query($conn, $query);
        if (mysqli_num_rows($login) === 1) {
            $row = mysqli_fetch_assoc($login);
            if ($row['adminID'] === $adminid && $row['password'] === $password) {
                echo "<script type = 'text/javascript'>alert('Login Successfully')</script>";
                $id = $row['empid'];

                $query_info = "SELECT * FROM tbempinfo WHERE empid = '$id'";
                $info = mysqli_query($conn, $query_info);

                if ($info && mysqli_num_rows($info) > 0) {
                    $inforow = mysqli_fetch_assoc($info);
                    $_SESSION['firstname'] = $inforow['firstname'];
                    $_SESSION['lastname'] = $inforow['lastname'];
                    $_SESSION['department'] = $inforow['course'];
                } 
                header('Location:../admin/admin.php');
                exit();
            } else {
                header('Location:../admin/loginadmin.php?error=Incorrect Admin ID or Password');
                exit();
            }
        } else {
            header('Location:../admin/loginadmin.php?error=Incorrect Admin ID or Password');
            exit();
        }
    }
} else {
    header('Location:../admin/loginadmin.php?error=Incorrect Admin ID or Password');
    exit();
}
