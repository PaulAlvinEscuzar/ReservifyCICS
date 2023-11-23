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
                $_SESSION['SR_Code'] = $row['SR_Code'];
                $_SESSION['firstname'] = $row['firstname'];
                $_SESSION['lastname'] = $row['lastname'];
                $_SESSION['dept'] = $row['dept'];
                $_SESSION['prog_sec'] = $row['prog_sec'];

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
