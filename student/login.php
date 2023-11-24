<?php

include '../includes/db.php';
session_start();
if (isset($_POST['srcode']) && isset($_POST['password'])) {
    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }
    $srcode = validate($_POST['srcode']);
    $password = validate($_POST['password']);

    if (empty($srcode)) {
        header('Location:../student/loginstudent.php?error=SR-Code is Required');
        exit();
    } elseif (empty($password)) {
        header('Location:../student/loginstudent.php?error=Password is Required');
        exit();
    } else {
        $query = "SELECT * FROM student_record WHERE SR_Code = '$srcode' AND pass = '$password'";
        $login = mysqli_query($conn, $query);
        if (mysqli_num_rows($login) === 1) {
            $row = mysqli_fetch_assoc($login);
            if ($row['SR_Code'] === $srcode && $row['pass'] === $password) {
                echo "<script type = 'text/javascript'>alert('Login Successfully')</script>";
                $id = $row['studid'];
                $_SESSION['SR_Code'] = $row['SR_Code'];
                
                $query_info = "SELECT * FROM tbstudinfo WHERE studid = '$id'";
                $info = mysqli_query($conn, $query_info);

                if ($info && mysqli_num_rows($info) > 0) {
                    $inforow = mysqli_fetch_assoc($info);
                    $_SESSION['firstname'] = $inforow['firstname'];
                    $_SESSION['lastname'] = $inforow['lastname'];
                    $_SESSION['course'] = $inforow['course'];
                } 
                
                header('Location:../student/home.php');
                exit();
            } else {
                header('Location:../student/loginstudent.php?error=Incorrect SR-Code or Password');
                exit();
            }
        } else {
            header('Location:../student/loginstudent.php?error=Incorrect SR-Code or Password');
            exit();
        }
    }
} else {
    header('Location:../student/loginstudent.php?error=Incorrect SR-Code or Password');
    exit();
}